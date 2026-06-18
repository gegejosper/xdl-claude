<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_name',
        'branch_code',
        'status',
        'type',
        'address',
        'contact_number',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function branch_users()
    {
        return $this->hasMany(BranchUser::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, BranchUser::class, 'branch_id', 'id', 'id', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ─── Constants ────────────────────────────────────────────────────────────

    public const TYPES = [
        'main'   => 'Main Branch',
        'sub'    => 'Sub Branch',
        'kiosk'  => 'Kiosk',
    ];

    public const STATUSES = [
        'active'   => 'Active',
        'inactive' => 'Inactive',
    ];
}
