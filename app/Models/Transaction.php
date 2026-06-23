<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id', 'cashier_id', 'customer_id',
        'or_number', 'transaction_number',
        'note', 'material', 'deadline', 'has_file_upload', 'remarks',
        'total_amount', 'discount_amount', 'balance',
        'payment_status', 'claim_status',
        'approved_by', 'approved_at', 'is_finalized', 'finalized_at',
    ];

    protected $casts = [
        'total_amount'    => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'balance'         => 'decimal:2',
        'has_file_upload' => 'boolean',
        'is_finalized'    => 'boolean',
        'deadline'        => 'date',
        'approved_at'     => 'datetime',
        'finalized_at'    => 'datetime',
    ];

    // ─── Constants ────────────────────────────────────────────────────────────

    public const PAYMENT_STATUS = [
        'unpaid'   => 'Unpaid',
        'partial'  => 'Partially Paid',
        'paid'     => 'Paid',
        'canceled' => 'Canceled',
    ];

    public const CLAIM_STATUS = [
        'in-queue' => 'In Queue',
        'ready'    => 'Ready',
        'claimed'  => 'Claimed',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function payments()
    {
        return $this->hasMany(TransactionPayment::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUS[$this->payment_status] ?? $this->payment_status;
    }

    public function getClaimStatusLabelAttribute(): string
    {
        return self::CLAIM_STATUS[$this->claim_status] ?? $this->claim_status;
    }

    public function recalculate_balance(): void
    {
        // Do not override a manually-canceled order.
        if ($this->payment_status === 'canceled') {
            return;
        }

        $total_paid = $this->payments()
            ->where('status', 'accepted')
            ->sum('amount_paid');

        $this->balance = max(0, $this->total_amount - $total_paid);

        if ($total_paid <= 0) {
            $this->payment_status = 'unpaid';
        } elseif ($this->balance > 0) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'paid';
        }

        $this->save();
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('transaction_number', 'like', "%{$term}%")
              ->orWhereHas('customer', fn($c) =>
                  $c->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
              );
        });
    }
}
