<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id', 'group_id', 'item_type', 'size', 'material',
        'width', 'height', 'sqft', 'quantity',
        'unit_price', 'discount', 'total', 'notes',
    ];

    protected $casts = [
        'width'      => 'decimal:2',
        'height'     => 'decimal:2',
        'sqft'       => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount'   => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    // ─── Constants ────────────────────────────────────────────────────────────

    public const ITEM_TYPES = [
        'tshirt'        => 'T-Shirt',
        'jersey_upper'  => 'Jersey Upper',
        'jersey_lower'  => 'Jersey Lower',
        'polo_shirt'    => 'Polo Shirt',
        'jogging_pants' => 'Jogging Pants',
        'jacket'        => 'Jacket',
        'long_sleeves'  => 'Long Sleeves',
        'dtf'           => 'DTF',
        'bags'          => 'Bags',
        'tarpaulin'     => 'Tarpaulin',
        'others'        => 'Others',
    ];

    // Clothing types that use standard sizes
    public const SIZED_TYPES = [
        'tshirt', 'jersey_upper', 'jersey_lower',
        'polo_shirt', 'jogging_pants', 'jacket', 'long_sleeves',
    ];

    public const SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL','KIDS'];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getItemTypeLabelAttribute(): string
    {
        return self::ITEM_TYPES[$this->item_type] ?? $this->item_type;
    }
}
