<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use App\Models\BranchUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CashierController extends Controller
{
    public function index(Request $request): View
    {
        $today     = Carbon::today();
        $month_start = Carbon::now()->startOfMonth();
        $month_end   = Carbon::now()->endOfMonth();
        $user_id   = Auth::id();

        // Safe branch lookup — table may not be migrated yet
        $branch_id = null;
        try {
            if (Schema::hasTable('branch_users')) {
                $branch_user = BranchUser::where('user_id', $user_id)->first();
                $branch_id   = $branch_user?->branch_id;
            }
        } catch (\Throwable $e) {
            $branch_id = null;
        }

        $base = Transaction::when($branch_id, fn($q) => $q->where('branch_id', $branch_id));

        // ─── Today stats ──────────────────────────────────────────────────
        $today_orders  = (clone $base)->whereDate('created_at', $today)->count();
        $today_sales   = (clone $base)->whereDate('created_at', $today)->sum('total_amount');
        $today_paid    = TransactionPayment::where('status', 'accepted')
            ->whereDate('created_at', $today)
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->whereHas('transaction', fn($t) => $t->where('branch_id', $branch_id));
            })
            ->sum('amount_paid');

        // ─── Month stats ───────────────────────────────────────────────────
        $month_orders   = (clone $base)->whereBetween('created_at', [$month_start, $month_end])->count();
        $month_sales    = (clone $base)->whereBetween('created_at', [$month_start, $month_end])->sum('total_amount');
        $month_collected = TransactionPayment::where('status', 'accepted')
            ->whereBetween('created_at', [$month_start, $month_end])
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->whereHas('transaction', fn($t) => $t->where('branch_id', $branch_id));
            })
            ->sum('amount_paid');
        $month_balance  = (clone $base)->whereBetween('created_at', [$month_start, $month_end])->sum('balance');

        // ─── Status counts ─────────────────────────────────────────────────
        $unpaid_orders  = (clone $base)->where('payment_status', 'unpaid')->count();
        $partial_orders = (clone $base)->where('payment_status', 'partial')->count();
        $inqueue_orders = (clone $base)->where('claim_status', 'in-queue')->count();

        // ─── Daily sales chart (current month) ────────────────────────────
        $daily_sales = (clone $base)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as orders')
            ->whereBetween('created_at', [$month_start, $month_end])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Build a full array for every day of the month so chart has no gaps
        $chart_labels  = [];
        $chart_sales   = [];
        $chart_orders  = [];
        $day = $month_start->copy();
        while ($day->lte($today)) {
            $key             = $day->toDateString();
            $chart_labels[]  = $day->format('M d');
            $chart_sales[]   = isset($daily_sales[$key]) ? (float) $daily_sales[$key]->total  : 0;
            $chart_orders[]  = isset($daily_sales[$key]) ? (int)   $daily_sales[$key]->orders : 0;
            $day->addDay();
        }

        // ─── Upcoming deadlines (next 7 days, unpaid/partial) ─────────────
        $upcoming_deadlines = (clone $base)
            ->with('customer')
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [$today, $today->copy()->addDays(7)])
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->where('claim_status', 'in-queue')
            ->orderBy('deadline')
            ->limit(8)
            ->get();

        // ─── Recent orders ─────────────────────────────────────────────────
        $recent_orders = (clone $base)
            ->with('customer')
            ->latest()
            ->limit(10)
            ->get();

        return view('panels.staff.dashboard', compact(
            'today_orders', 'today_sales', 'today_paid',
            'month_orders', 'month_sales', 'month_collected', 'month_balance',
            'unpaid_orders', 'partial_orders', 'inqueue_orders',
            'chart_labels', 'chart_sales', 'chart_orders',
            'upcoming_deadlines', 'recent_orders'
        ));
    }
}
