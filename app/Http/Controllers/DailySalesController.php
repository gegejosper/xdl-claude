<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\DailySales;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DailySalesController extends Controller
{
    // ─── Resolve branch for the authenticated user ─────────────────────────────

    private function resolve_branch(): ?Branch
    {
        $user = Auth::user();

        if ($user->hasRole(['admin', 'superadmin'])) {
            return null; // Admin can see all branches
        }

        $branch_user = BranchUser::where('user_id', $user->id)->first();
        return $branch_user ? Branch::find($branch_user->branch_id) : null;
    }

    // ─── Index (history list) ─────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user     = Auth::user();
        $is_admin = $user->hasRole(['admin', 'superadmin']);
        $branch   = $this->resolve_branch();

        $query = DailySales::with(['branch', 'closer'])
            ->orderBy('sales_date', 'desc');

        if (!$is_admin && $branch) {
            $query->where('branch_id', $branch->id);
        }

        $records = $query->paginate(30);
        $branches = $is_admin ? Branch::where('status', 'active')->get() : collect();

        return view('daily_sales.index', compact('records', 'is_admin', 'branch', 'branches'));
    }

    // ─── Close sales for today (manual by staff) ──────────────────────────────

    public function close(Request $request)
    {
        $user   = Auth::user();
        $today  = Carbon::today();

        // Determine branch
        if ($user->hasRole(['admin', 'superadmin'])) {
            $request->validate(['branch_id' => 'required|exists:branches,id']);
            $branch_id = $request->branch_id;
        } else {
            $branch_user = BranchUser::where('user_id', $user->id)->first();
            if (!$branch_user) {
                return response()->json(['errors' => ['general' => 'No branch assigned to your account.']], 403);
            }
            $branch_id = $branch_user->branch_id;
        }

        // Already closed?
        $existing = DailySales::where('branch_id', $branch_id)
            ->where('sales_date', $today)
            ->first();

        if ($existing) {
            return response()->json(['errors' => ['general' => 'Sales for today have already been closed.']], 422);
        }

        DB::beginTransaction();
        try {
            [$totals] = $this->compute_totals($branch_id, $today);

            $record = DailySales::create([
                'branch_id'         => $branch_id,
                'sales_date'        => $today,
                'total_sales'       => $totals['total_sales'],
                'total_payments'    => $totals['total_payments'],
                'transaction_count' => $totals['transaction_count'],
                'closed_by'         => $user->id,
                'close_type'        => 'manual',
                'closed_at'         => now(),
                'notes'             => $request->notes,
            ]);

            DB::commit();
            Log::info("{$user->name} manually closed daily sales for branch #{$branch_id} on {$today->toDateString()}");

            return response()->json([
                'success' => true,
                'message' => 'Daily sales closed.',
                'record'  => [
                    'id'                => $record->id,
                    'sales_date'        => $record->sales_date->format('M d, Y'),
                    'total_sales'       => number_format($record->total_sales, 2),
                    'total_payments'    => number_format($record->total_payments, 2),
                    'transaction_count' => $record->transaction_count,
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Daily sales close failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to close daily sales.']], 500);
        }
    }

    // ─── Show a single day's summary ──────────────────────────────────────────

    public function show(int $id)
    {
        $record = DailySales::with(['branch', 'closer'])->findOrFail($id);

        // Staff can only view their own branch
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'superadmin'])) {
            $branch_user = BranchUser::where('user_id', $user->id)->first();
            if (!$branch_user || $branch_user->branch_id !== $record->branch_id) {
                abort(403);
            }
        }

        // Load that day's transactions
        $transactions = Transaction::where('branch_id', $record->branch_id)
            ->whereDate('created_at', $record->sales_date)
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('daily_sales.show', compact('record', 'transactions'));
    }

    // ─── Today's stats (for dashboard widget) ─────────────────────────────────

    public function today_stats(Request $request)
    {
        $user      = Auth::user();
        $branch_id = null;

        if (!$user->hasRole(['admin', 'superadmin'])) {
            $branch_user = BranchUser::where('user_id', $user->id)->first();
            $branch_id   = $branch_user?->branch_id;
        } elseif ($request->has('branch_id')) {
            $branch_id = $request->branch_id;
        }

        [$totals, $already_closed] = $this->compute_totals($branch_id, Carbon::today());

        $breakdown_formatted = [];
        foreach ($totals['method_breakdown'] as $key => $item) {
            $breakdown_formatted[] = [
                'label'  => $item['label'],
                'amount' => number_format($item['amount'], 2),
            ];
        }

        return response()->json([
            'total_sales'       => number_format($totals['total_sales'], 2),
            'total_payments'    => number_format($totals['total_payments'], 2),
            'transaction_count' => $totals['transaction_count'],
            'unpaid_count'      => $totals['unpaid_count'],
            'partial_count'     => $totals['partial_count'],
            'paid_count'        => $totals['paid_count'],
            'method_breakdown'  => $breakdown_formatted,
            'already_closed'    => $already_closed,
        ]);
    }

    // ─── Shared totals computation ────────────────────────────────────────────

    private function compute_totals(?int $branch_id, Carbon $date): array
    {
        $txn_query = Transaction::whereDate('created_at', $date)
            ->whereNotIn('payment_status', ['canceled']);

        if ($branch_id) {
            $txn_query->where('branch_id', $branch_id);
        }

        $transactions = $txn_query->get();
        $txn_ids      = $transactions->pluck('id');

        $payments = TransactionPayment::whereIn('transaction_id', $txn_ids)
            ->where('status', 'accepted')
            ->whereDate('created_at', $date)
            ->get();

        // Breakdown by payment method
        $method_breakdown = [];
        foreach (\App\Models\TransactionPayment::PAYMENT_METHODS as $key => $label) {
            $amount = $payments->where('payment_method', $key)->sum('amount_paid');
            if ($amount > 0) {
                $method_breakdown[$key] = [
                    'label'  => $label,
                    'amount' => (float) $amount,
                ];
            }
        }
        // Catch any payments with null/unknown method under 'cash'
        $known_methods    = array_keys(\App\Models\TransactionPayment::PAYMENT_METHODS);
        $unclassified     = $payments->filter(fn($p) => !in_array($p->payment_method, $known_methods))->sum('amount_paid');
        if ($unclassified > 0) {
            $method_breakdown['cash']['amount'] = ($method_breakdown['cash']['amount'] ?? 0) + (float) $unclassified;
            $method_breakdown['cash']['label']  = 'Cash';
        }

        $totals = [
            'total_sales'        => $transactions->sum('total_amount'),
            'total_payments'     => (float) $payments->sum('amount_paid'),
            'transaction_count'  => $transactions->count(),
            'unpaid_count'       => $transactions->where('payment_status', 'unpaid')->count(),
            'partial_count'      => $transactions->where('payment_status', 'partial')->count(),
            'paid_count'         => $transactions->where('payment_status', 'paid')->count(),
            'method_breakdown'   => $method_breakdown,
        ];

        $already_closed = DailySales::where('sales_date', $date)
            ->when($branch_id, fn($q) => $q->where('branch_id', $branch_id))
            ->exists();

        return [$totals, $already_closed];
    }
}
