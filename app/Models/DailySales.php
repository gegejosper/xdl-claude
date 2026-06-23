<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySales extends Model
{
    protected $fillable = [
        'branch_id', 'sales_date', 'total_sales', 'total_payments',
        'transaction_count', 'closed_by', 'close_type', 'closed_at', 'notes',
    ];

    protected $casts = [
        'sales_date'       => 'date',
        'closed_at'        => 'datetime',
        'total_sales'      => 'decimal:2',
        'total_payments'   => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
