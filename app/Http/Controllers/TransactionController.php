<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionPayment;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Helpers\GlobalHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // ─── List ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'cashier', 'branch'])
            ->when(Auth::user()->hasRole('staff'), function ($q) {
                $branch_user = BranchUser::where('user_id', Auth::id())->first();
                if ($branch_user) {
                    $q->where('branch_id', $branch_user->branch_id);
                }
            });

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('claim_status')) {
            $query->where('claim_status', $request->claim_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(25)->withQueryString();

        $page_name = 'Transactions';
        return view('transactions.index', compact('transactions', 'page_name'));
    }

    // ─── Create ───────────────────────────────────────────────────────────────

    public function create()
    {
        $customers        = Customer::where('status', 'active')->orderBy('first_name')->get();
        $page_name        = 'New Job Order';
        $submission_token = (string) Str::uuid();
        session(['txn_submission_token' => $submission_token]);

        return view('transactions.create', array_merge(
            compact('customers', 'page_name', 'submission_token'),
            $this->address_data()
        ));
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        // Duplicate submission guard
        $token = $request->input('submission_token');
        if (!$token || session('txn_submission_token') !== $token) {
            return response()->json(['errors' => ['general' => 'Duplicate submission detected. Please reload the page and try again.']], 422);
        }
        session()->forget('txn_submission_token');

        $validator = Validator::make($request->all(), [
            'customer_id'        => 'required|exists:customers,id',
            'note'               => 'nullable|string|max:500',
            'material'           => 'nullable|string|max:100',
            'deadline'           => 'nullable|date',
            'has_file_upload'    => 'nullable|boolean',
            'remarks'            => 'nullable|string|max:500',
            'items'              => 'required|array|min:1',
            'items.*.item_type'  => 'required|string',
            'items.*.material'   => 'nullable|string|max:100',
            'items.*.width'      => 'nullable|numeric|min:0',
            'items.*.height'     => 'nullable|numeric|min:0',
            'items.*.quantity'   => 'nullable|integer|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount'   => 'nullable|numeric|min:0',
            'items.*.notes'      => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $user_id     = Auth::id();
            $branch_user = BranchUser::where('user_id', $user_id)->first();
            $branch_id   = $branch_user ? $branch_user->branch_id : 1;

            $transaction_number = $this->generate_job_order_number($branch_id);
            $or_number          = $this->generate_or_number();

            [$total_amount, $discount_amount] = $this->calculate_items_total($request->items);

            $transaction = Transaction::create([
                'branch_id'          => $branch_id,
                'cashier_id'         => $user_id,
                'customer_id'        => $request->customer_id,
                'or_number'          => $or_number,
                'transaction_number' => $transaction_number,
                'note'               => $request->note,
                'material'           => $request->material,
                'deadline'           => $request->deadline,
                'has_file_upload'    => (bool)$request->has_file_upload,
                'remarks'            => $request->remarks,
                'total_amount'       => $total_amount,
                'discount_amount'    => $discount_amount,
                'balance'            => $total_amount,
                'payment_status'     => 'unpaid',
                'claim_status'       => 'in-queue',
            ]);

            $this->save_items($transaction->id, $request->items);

            DB::commit();

            Log::info("Transaction created: {$transaction->transaction_number} by user {$user_id}");

            return response()->json([
                'success'  => true,
                'message'  => 'Job order created.',
                'redirect' => route('transactions.show', $transaction->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Transaction store failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to save job order. Please try again.']], 500);
        }
    }

    // ─── Show / Print ─────────────────────────────────────────────────────────

    public function show(int $id)
    {
        $transaction = Transaction::with(['customer', 'cashier', 'branch', 'items', 'payments', 'approver'])
            ->findOrFail($id);

        $this->authorize_transaction_access($transaction);

        $page_name = "Job Order #{$transaction->transaction_number}";
        return view('transactions.show', compact('transaction', 'page_name'));
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit(int $id)
    {
        $transaction = Transaction::with(['customer', 'items'])
            ->findOrFail($id);

        $this->authorize_transaction_access($transaction);

        if ($transaction->is_finalized) {
            return redirect()->route('transactions.show', $id)
                ->with('error', 'Finalized orders cannot be edited.');
        }

        $customers = Customer::where('status', 'active')->orderBy('first_name')->get();
        $page_name        = "Edit Job Order #{$transaction->transaction_number}";
        $submission_token = (string) Str::uuid();
        session(['txn_submission_token' => $submission_token]);

        return view('transactions.edit', array_merge(
            compact('transaction', 'customers', 'page_name', 'submission_token'),
            $this->address_data()
        ));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, int $id)
    {
        // Duplicate submission guard
        $token = $request->input('submission_token');
        if (!$token || session('txn_submission_token') !== $token) {
            return response()->json(['errors' => ['general' => 'Duplicate submission detected. Please reload the page and try again.']], 422);
        }
        session()->forget('txn_submission_token');

        $transaction = Transaction::findOrFail($id);
        $this->authorize_transaction_access($transaction);

        if ($transaction->is_finalized) {
            return response()->json(['errors' => ['general' => 'Finalized orders cannot be edited.']], 403);
        }

        $validator = Validator::make($request->all(), [
            'customer_id'        => 'required|exists:customers,id',
            'note'               => 'nullable|string|max:500',
            'material'           => 'nullable|string|max:100',
            'deadline'           => 'nullable|date',
            'has_file_upload'    => 'nullable|boolean',
            'remarks'            => 'nullable|string|max:500',
            'items'              => 'required|array|min:1',
            'items.*.item_type'  => 'required|string',
            'items.*.material'   => 'nullable|string|max:100',
            'items.*.width'      => 'nullable|numeric|min:0',
            'items.*.height'     => 'nullable|numeric|min:0',
            'items.*.quantity'   => 'nullable|integer|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount'   => 'nullable|numeric|min:0',
            'items.*.notes'      => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            [$total_amount, $discount_amount] = $this->calculate_items_total($request->items);

            // Recompute balance based on payments already made
            $total_paid  = $transaction->payments()->where('status', 'accepted')->sum('amount_paid');
            $new_balance = max(0, $total_amount - $total_paid);

            if ($total_paid <= 0) {
                $payment_status = 'unpaid';
            } elseif ($new_balance > 0) {
                $payment_status = 'partial';
            } else {
                $payment_status = 'paid';
            }

            $transaction->update([
                'customer_id'     => $request->customer_id,
                'note'            => $request->note,
                'material'        => $request->material,
                'deadline'        => $request->deadline,
                'has_file_upload' => (bool)$request->has_file_upload,
                'remarks'         => $request->remarks,
                'total_amount'    => $total_amount,
                'discount_amount' => $discount_amount,
                'balance'         => $new_balance,
                'payment_status'  => $payment_status,
            ]);

            // Replace all items
            $transaction->items()->delete();
            $this->save_items($transaction->id, $request->items);

            DB::commit();
            Log::info("Transaction updated: {$transaction->transaction_number} by user " . Auth::id());

            return response()->json([
                'success'  => true,
                'message'  => 'Job order updated.',
                'redirect' => route('transactions.show', $transaction->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Transaction update failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to update job order.']], 500);
        }
    }

    // ─── Receive Payment ──────────────────────────────────────────────────────

    public function receive_payment(Request $request, int $id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->authorize_transaction_access($transaction);

        if ($transaction->payment_status === 'paid') {
            return response()->json(['errors' => ['general' => 'Order is already fully paid.']], 422);
        }

        if ($transaction->payment_status === 'canceled') {
            return response()->json(['errors' => ['general' => 'This order has been canceled. Payments cannot be received.']], 422);
        }

        $validator = Validator::make($request->all(), [
            'amount_paid' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $amount_paid   = (float)$request->amount_paid;
        $change_amount = max(0, $amount_paid - $transaction->balance);

        DB::beginTransaction();
        try {
            $payment = TransactionPayment::create([
                'transaction_id' => $transaction->id,
                'customer_id'    => $transaction->customer_id,
                'amount_paid'    => $amount_paid,
                'change_amount'  => $change_amount,
                'status'         => 'accepted',
                'payment_type'   => 'payment',
            ]);

            $transaction->recalculate_balance();

            DB::commit();
            Log::info("Payment received for transaction #{$transaction->transaction_number}: {$amount_paid}");

            $fresh = $transaction->fresh();
            return response()->json([
                'success'        => true,
                'message'        => 'Payment recorded.',
                'payment_id'     => $payment->id,
                'receipt_url'    => route('transactions.payment.receipt', $payment->id),
                'new_balance'    => number_format($fresh->balance, 2),
                'payment_status' => $fresh->payment_status,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment receive failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to record payment.']], 500);
        }
    }

    // ─── Approve ──────────────────────────────────────────────────────────────

    public function approve(int $id)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $transaction = Transaction::findOrFail($id);

        if ($transaction->approved_by) {
            return response()->json(['errors' => ['general' => 'Already approved.']], 422);
        }

        DB::beginTransaction();
        try {
            $transaction->update([
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Approve failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to approve order.']], 500);
        }

        Log::info("Transaction #{$transaction->transaction_number} approved by " . Auth::id());

        return response()->json(['success' => true, 'message' => 'Order approved.']);
    }

    // ─── Finalize ─────────────────────────────────────────────────────────────

    public function finalize(int $id)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $transaction = Transaction::findOrFail($id);

        if ($transaction->is_finalized) {
            return response()->json(['errors' => ['general' => 'Already finalized.']], 422);
        }

        DB::beginTransaction();
        try {
            $transaction->update([
                'is_finalized' => true,
                'finalized_at' => now(),
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Finalize failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to finalize order.']], 500);
        }

        Log::info("Transaction #{$transaction->transaction_number} finalized by " . Auth::id());

        return response()->json(['success' => true, 'message' => 'Order finalized.']);
    }

    // ─── Update Claim Status ──────────────────────────────────────────────────

    public function update_claim(Request $request, int $id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->authorize_transaction_access($transaction);

        $validator = Validator::make($request->all(), [
            'claim_status' => 'required|in:in-queue,ready,claimed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $transaction->update(['claim_status' => $request->claim_status]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Claim status update failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to update claim status.']], 500);
        }

        return response()->json(['success' => true, 'message' => 'Claim status updated.']);
    }

    // ─── Cancel Order ─────────────────────────────────────────────────────────

    public function cancel_order(int $id)
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            return response()->json(['errors' => ['general' => 'Unauthorized.']], 403);
        }

        $transaction = Transaction::findOrFail($id);

        if ($transaction->payment_status === 'paid') {
            return response()->json(['errors' => ['general' => 'Cannot cancel an order that is already fully paid.']], 422);
        }

        if ($transaction->payment_status === 'canceled') {
            return response()->json(['errors' => ['general' => 'Order is already canceled.']], 422);
        }

        DB::beginTransaction();
        try {
            $transaction->update(['payment_status' => 'canceled']);
            DB::commit();
            Log::info(Auth::user()->name . " canceled order #{$transaction->transaction_number}");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Cancel order failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to cancel order.']], 500);
        }

        return response()->json(['success' => true, 'message' => 'Order canceled.']);
    }

    // ─── Payment Receipt ──────────────────────────────────────────────────────

    public function payment_receipt(int $payment_id)
    {
        $payment = TransactionPayment::with([
            'transaction.customer',
            'transaction.cashier',
            'transaction.branch',
            'transaction.items',
            'transaction.payments' => fn($q) => $q->where('status', 'accepted')->orderBy('id'),
        ])->findOrFail($payment_id);

        $this->authorize_transaction_access($payment->transaction);

        $page_name = 'Payment Receipt';
        return view('transactions.receipt', compact('payment', 'page_name'));
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function address_data(): array
    {
        $default_province = '0973';
        $default_citymun  = '097322';

        $provinces = DB::table('provinces')
            ->orderBy('prov_desc')
            ->get(['prov_code', 'prov_desc']);

        $municipalities = DB::table('citymunicipalities')
            ->where('prov_code', $default_province)
            ->orderBy('citymun_desc')
            ->get(['citymun_code', 'citymun_desc']);

        $barangays = DB::table('barangays')
            ->where('citymun_code', $default_citymun)
            ->orderBy('brgy_desc')
            ->get(['brgy_code', 'brgy_desc']);

        return compact('provinces', 'municipalities', 'barangays', 'default_province', 'default_citymun');
    }

    private function generate_job_order_number(int $branch_id): string
    {
        $prefix = 'JO-' . date('Ymd') . '-';
        $last   = Transaction::where('transaction_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->latest('id')
            ->value('transaction_number');

        $next = $last ? ((int)substr($last, -4) + 1) : 1;
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    private function generate_or_number(): string
    {
        $prefix = 'OR-' . date('Ym') . '-';
        $last   = Transaction::where('or_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->latest('id')
            ->value('or_number');

        $next = $last ? ((int)substr($last, -5) + 1) : 1;
        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    private function authorize_transaction_access(Transaction $transaction): void
    {
        // if (Auth::user()->hasRole('staff')) {
        //     $branch_user = BranchUser::where('user_id', Auth::id())->first();
        //     if (!$branch_user || $branch_user->branch_id !== $transaction->branch_id) {
        //         abort(403);
        //     }
        // }
    }

    // ─── Calculate totals from item payload ───────────────────────────────────
    // Returns [$total_amount, $discount_amount].
    // Handles both sized (items[i][sizes][SIZE]=qty) and plain (items[i][quantity]).
    private function calculate_items_total(array $items): array
    {
        $total_amount    = 0;
        $discount_amount = 0;

        foreach ($items as $item) {
            $price    = (float)($item['unit_price'] ?? 0);
            $discount = (float)($item['discount']   ?? 0);
            $type     = $item['item_type'] ?? '';

            if (in_array($type, TransactionItem::SQFT_TYPES, true)) {
                $sqft       = (float)($item['width'] ?? 0) * (float)($item['height'] ?? 0);
                $qty        = max(1, (int)($item['quantity'] ?? 1));
                $total_sqft = $sqft * $qty;
                $total      = ($total_sqft * $price) - $discount;
            } elseif (!empty($item['sizes']) && is_array($item['sizes'])) {
                $qty   = array_sum(array_map('intval', $item['sizes']));
                $total = ($qty * $price) - $discount;
            } else {
                $qty   = (int)($item['quantity'] ?? 0);
                $total = ($qty * $price) - $discount;
            }

            $total_amount    += max(0, $total);
            $discount_amount += $discount;
        }

        return [$total_amount, $discount_amount];
    }

    // ─── Persist item rows for a transaction ─────────────────────────────────
    // For sized items: one DB row per size with qty > 0, all sharing a group_id.
    // For tarpaulin/others: single DB row.
    // Discount stored on first row of each group; 0 on the rest.
    private function save_items(int $transaction_id, array $items): void
    {
        $sized_types = TransactionItem::SIZED_TYPES;

        foreach ($items as $item) {
            $price    = (float)($item['unit_price'] ?? 0);
            $discount = (float)($item['discount']   ?? 0);
            $type     = $item['item_type'] ?? '';

            if (in_array($type, TransactionItem::SQFT_TYPES, true)) {
                $qty        = max(1, (int)($item['quantity'] ?? 1));
                $sqft       = (float)($item['width'] ?? 0) * (float)($item['height'] ?? 0);
                $total_sqft = $sqft * $qty;
                $total      = max(0, ($total_sqft * $price) - $discount);

                TransactionItem::create([
                    'transaction_id' => $transaction_id,
                    'group_id'       => Str::uuid(),
                    'item_type'      => $type,
                    'material'       => $item['material'] ?? null,
                    'width'          => $item['width']    ?? null,
                    'height'         => $item['height']   ?? null,
                    'sqft'           => $sqft,
                    'quantity'       => $qty,
                    'unit_price'     => $price,
                    'discount'       => $discount,
                    'total'          => $total,
                    'notes'          => $item['notes'] ?? null,
                ]);

            } elseif (in_array($type, $sized_types, true) && !empty($item['sizes']) && is_array($item['sizes'])) {
                $group_id        = (string) Str::uuid();
                $first_row       = true;

                foreach ($item['sizes'] as $size => $qty) {
                    $qty = (int)$qty;
                    if ($qty <= 0) continue;

                    $row_discount = $first_row ? $discount : 0;
                    $row_total    = max(0, ($qty * $price) - $row_discount);
                    $first_row    = false;

                    TransactionItem::create([
                        'transaction_id' => $transaction_id,
                        'group_id'       => $group_id,
                        'item_type'      => $type,
                        'size'           => $size,
                        'material'       => $item['material'] ?? null,
                        'quantity'       => $qty,
                        'unit_price'     => $price,
                        'discount'       => $row_discount,
                        'total'          => $row_total,
                        'notes'          => $item['notes'] ?? null,
                    ]);
                }

            } else {
                // Non-sized: DTF, bags, others
                $qty   = (int)($item['quantity'] ?? 0);
                $total = max(0, ($qty * $price) - $discount);

                TransactionItem::create([
                    'transaction_id' => $transaction_id,
                    'group_id'       => Str::uuid(),
                    'item_type'      => $type,
                    'material'       => $item['material'] ?? null,
                    'quantity'       => $qty,
                    'unit_price'     => $price,
                    'discount'       => $discount,
                    'total'          => $total,
                    'notes'          => $item['notes'] ?? null,
                ]);
            }
        }
    }
}
