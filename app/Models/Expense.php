<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id', 'added_by', 'category', 'type',
        'description', 'amount', 'expense_date', 'remarks',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    // ─── Constants ────────────────────────────────────────────────────────────

    public const PURCHASE_TYPES = [
        'tela'    => 'Tela',
        'paper'   => 'Paper',
        'ink'     => 'Ink',
        'needle'  => 'Needle',
        'thread'  => 'Thread',
        'sorted'  => 'Sorted',
        'lining'  => 'Lining',
        'others'  => 'Others',
    ];

    public const EXPENSE_TYPES = [
        'salary'      => 'Salary',
        'transport'   => 'Transport / Gasoline',
        'upload_load' => 'Upload / IP Load',
        'snacks_meal' => 'Snacks / Meal',
        'rent'        => 'Rent',
        'utilities'   => 'Water + Elec + Internet + Utilities',
        'commission'  => 'Commission',
        'reports'     => 'Reports',
        'others'      => 'Others',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function added_by_user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePurchases($query)
    {
        return $query->where('category', 'purchase');
    }

    public function scopeExpenses($query)
    {
        return $query->where('category', 'expense');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getTypeLabelAttribute(): string
    {
        $all = array_merge(self::PURCHASE_TYPES, self::EXPENSE_TYPES);
        return $all[$this->type] ?? $this->type;
    }
}
