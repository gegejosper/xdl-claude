<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    public const PAYMENT_METHODS = [
        'cash'          => 'Cash',
        'gcash'         => 'GCash',
        'bank_transfer' => 'Bank Transfer',
        'maya'          => 'Maya',
        'others'        => 'Others',
    ];

    protected $fillable = [
        'transaction_id', 'customer_id', 'amount_paid',
        'change_amount', 'status', 'payment_type', 'payment_method',
    ];

    protected $casts = [
        'amount_paid'   => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
