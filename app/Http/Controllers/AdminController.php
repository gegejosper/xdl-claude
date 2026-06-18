<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Customer;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $date_from = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : Carbon::now()->startOfMonth();

        $date_to = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : Carbon::now()->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$date_from, $date_to]);

        $total_sales      = (clone $transactions)->sum('total_amount');
        $total_collected  = (clone $transactions)->with('payments')
            ->get()
            ->flatMap(fn($t) => $t->payments->where('status', 'accepted'))
            ->sum('amount_paid');
        $total_balance    = (clone $transactions)->sum('balance');
        $unpaid_count     = (clone $transactions)->where('payment_status', 'unpaid')->count();
        $partial_count    = (clone $transactions)->where('payment_status', 'partial')->count();
        $paid_count       = (clone $transactions)->where('payment_status', 'paid')->count();
        $transaction_count = (clone $transactions)->count();

        $total_purchases = Expense::purchases()
            ->whereBetween('expense_date', [$date_from->toDateString(), $date_to->toDateString()])
            ->sum('amount');

        $total_expenses = Expense::expenses()
            ->whereBetween('expense_date', [$date_from->toDateString(), $date_to->toDateString()])
            ->sum('amount');

        $new_customers = Customer::whereBetween('created_at', [$date_from, $date_to])->count();

        // Daily breakdown for the range (up to 31 days)
        $daily_sales = Transaction::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereBetween('created_at', [$date_from, $date_to])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $page_name = 'Admin Dashboard';
        return view('panels.admin.dashboard', compact(
            'total_sales', 'total_collected', 'total_balance',
            'unpaid_count', 'partial_count', 'paid_count', 'transaction_count',
            'total_purchases', 'total_expenses', 'new_customers', 'daily_sales',
            'date_from', 'date_to', 'page_name'
        ));
    }
}
