<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function outstanding_balances(Request $request)
    {
        $user     = Auth::user();
        $is_admin = $user->hasRole(['admin', 'superadmin']);

        $query = Transaction::with(['customer', 'branch'])
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->orderBy('created_at', 'asc');

        if (!$is_admin) {
            $branch_user = BranchUser::where('user_id', $user->id)->first();
            if ($branch_user) {
                $query->where('branch_id', $branch_user->branch_id);
            }
        } elseif ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->get();
        $branches     = $is_admin ? Branch::where('status', 'active')->get() : collect();

        $total_balance = $transactions->sum('balance');

        return view('reports.outstanding_balances', compact(
            'transactions', 'branches', 'is_admin', 'total_balance'
        ));
    }
}
