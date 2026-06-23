<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\DailySales;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloseDailySales extends Command
{
    protected $signature   = 'app:close-daily-sales {--date= : Date to close (Y-m-d), defaults to yesterday}';
    protected $description = 'Auto-close daily sales for all branches at midnight.';

    public function handle(): int
    {
        // Default to yesterday (command runs at 00:00 so yesterday = the business day just ended)
        $date_str = $this->option('date');
        $date     = $date_str ? Carbon::parse($date_str) : Carbon::yesterday();

        $this->info("Closing daily sales for: {$date->toDateString()}");

        $branches = Branch::where('status', 'active')->get();

        foreach ($branches as $branch) {
            // Skip if already manually closed
            $already = DailySales::where('branch_id', $branch->id)
                ->where('sales_date', $date)
                ->exists();

            if ($already) {
                $this->line("  Branch #{$branch->id} ({$branch->name}): already closed — skipped.");
                continue;
            }

            DB::beginTransaction();
            try {
                $transactions = Transaction::where('branch_id', $branch->id)
                    ->whereDate('created_at', $date)
                    ->whereNotIn('payment_status', ['canceled'])
                    ->get();

                $txn_ids = $transactions->pluck('id');

                $total_payments = TransactionPayment::whereIn('transaction_id', $txn_ids)
                    ->where('status', 'accepted')
                    ->whereDate('created_at', $date)
                    ->sum('amount_paid');

                DailySales::create([
                    'branch_id'         => $branch->id,
                    'sales_date'        => $date,
                    'total_sales'       => $transactions->sum('total_amount'),
                    'total_payments'    => (float) $total_payments,
                    'transaction_count' => $transactions->count(),
                    'closed_by'         => null,
                    'close_type'        => 'auto',
                    'closed_at'         => now(),
                ]);

                DB::commit();
                $this->info("  Branch #{$branch->id} ({$branch->name}): closed OK.");
                Log::info("Auto-closed daily sales for branch #{$branch->id} on {$date->toDateString()}");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error("  Branch #{$branch->id}: FAILED — " . $e->getMessage());
                Log::error("Auto-close failed for branch #{$branch->id}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
