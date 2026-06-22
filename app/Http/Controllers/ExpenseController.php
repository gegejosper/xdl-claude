<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Branch;
use App\Models\BranchUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    // ─── Purchases List ───────────────────────────────────────────────────────

    public function purchases(Request $request)
    {
        $query = Expense::with(['branch', 'added_by_user'])
            ->purchases()
            ->when(Auth::user()->hasRole('staff'), function ($q) {
                $bu = BranchUser::where('user_id', Auth::id())->first();
                if ($bu) $q->where('branch_id', $bu->branch_id);
            });

        $this->apply_filters($query, $request);

        $expenses   = $query->latest('expense_date')->paginate(25)->withQueryString();
        $types      = Expense::PURCHASE_TYPES;
        $category   = 'purchase';
        $page_name  = 'Purchases';

        return view('expenses.index', compact('expenses', 'types', 'category', 'page_name'));
    }

    // ─── Expenses List ────────────────────────────────────────────────────────

    public function expenses(Request $request)
    {
        $query = Expense::with(['branch', 'added_by_user'])
            ->expenses()
            ->when(Auth::user()->hasRole('staff'), function ($q) {
                $bu = BranchUser::where('user_id', Auth::id())->first();
                if ($bu) $q->where('branch_id', $bu->branch_id);
            });

        $this->apply_filters($query, $request);

        $expenses  = $query->latest('expense_date')->paginate(25)->withQueryString();
        $types     = Expense::EXPENSE_TYPES;
        $category  = 'expense';
        $page_name = 'Expenses';

        return view('expenses.index', compact('expenses', 'types', 'category', 'page_name'));
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category'     => 'required|in:purchase,expense',
            'type'         => 'required|string|max:50',
            'description'  => 'nullable|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'remarks'      => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate type belongs to correct category
        $valid_types = $request->category === 'purchase'
            ? array_keys(Expense::PURCHASE_TYPES)
            : array_keys(Expense::EXPENSE_TYPES);

        if (!in_array($request->type, $valid_types)) {
            return response()->json(['errors' => ['type' => 'Invalid type for this category.']], 422);
        }

        $user_id   = Auth::id();
        $bu        = BranchUser::where('user_id', $user_id)->first();
        $branch_id = $bu ? $bu->branch_id : 1;

        DB::beginTransaction();
        try {
            $expense = Expense::create([
                'branch_id'    => $branch_id,
                'added_by'     => $user_id,
                'category'     => $request->category,
                'type'         => $request->type,
                'description'  => $request->description,
                'amount'       => $request->amount,
                'expense_date' => $request->expense_date,
                'remarks'      => $request->remarks,
            ]);

            DB::commit();
            Log::info("Expense #{$expense->id} ({$expense->category}/{$expense->type}) added by user {$user_id}");

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->category) . ' recorded.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Expense store failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to save record.']], 500);
        }
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, int $id)
    {
        $expense = Expense::findOrFail($id);

        $is_restricted = Auth::user()->hasRole(['staff', 'cashier']);

        if ($is_restricted) {
            if ($expense->added_by !== Auth::id()) {
                return response()->json(['errors' => ['general' => 'You can only edit records you added.']], 403);
            }
            if (!$expense->expense_date->isToday()) {
                return response()->json(['errors' => ['general' => 'You can only edit records added today. Past records can only be edited by an Admin.']], 403);
            }
        }

        $validator = Validator::make($request->all(), [
            'type'         => 'required|string|max:50',
            'description'  => 'nullable|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'remarks'      => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $expense->update([
                'type'         => $request->type,
                'description'  => $request->description,
                'amount'       => $request->amount,
                'expense_date' => $request->expense_date,
                'remarks'      => $request->remarks,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Record updated.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Expense update failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to update record.']], 500);
        }
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function destroy(int $id)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $expense = Expense::findOrFail($id);
        $expense->delete();

        Log::info("Expense #{$id} soft-deleted by " . Auth::id());

        return response()->json(['success' => true, 'message' => 'Record deleted.']);
    }

    // ─── Private ──────────────────────────────────────────────────────────────

    private function apply_filters($query, Request $request): void
    {
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }
    }
}
