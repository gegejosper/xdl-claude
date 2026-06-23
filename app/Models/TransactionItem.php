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
        // ── Clothing ──────────────────────────────────────────────
        'tshirt'                => 'T-Shirt',
        'jersey_upper'          => 'Jersey Upper',
        'jersey_lower'          => 'Jersey Lower',
        'polo_shirt'            => 'Polo Shirt',
        'jogging_pants'         => 'Jogging Pants',
        'jacket'                => 'Jacket',
        'long_sleeves'          => 'Long Sleeves',
        // ── Tarpaulin ─────────────────────────────────────────────
        'tarpaulin'             => 'Tarpaulin',
        // ── Sintraboard ───────────────────────────────────────────
        'sintraboard_a4_flat'   => 'Sintraboard — A4 Flat',
        'sintraboard_a4_box'    => 'Sintraboard — A4 Box Type',
        'sintraboard_a3_flat'   => 'Sintraboard — A3 Flat',
        'sintraboard_a3_box'    => 'Sintraboard — A3 Box Type',
        'sintraboard_sqft'      => 'Sintraboard (per sq. ft)',
        // ── Signage ───────────────────────────────────────────────
        'panaflex_no_light'     => 'Panaflex (w/o Light)',
        'panaflex_with_light'   => 'Panaflex (with Light)',
        'sticker_no_laminate'   => 'Sticker (w/o Laminate)',
        'sticker_with_laminate' => 'Sticker (with Laminate)',
        'sticker_per_inch'      => 'Sticker (per inch)',
        'vinyl_per_meter'       => 'Vinyl (per meter)',
        'plate_no'              => 'Plate No.',
        // ── Others ────────────────────────────────────────────────
        'mugs'                  => 'Mugs',
        'trophy'                => 'Trophy',
        'dtf'                   => 'DTF',
        'bags'                  => 'Bags',
        'others'                => 'Others',
    ];

    // Clothing types — show size grid (XS–KIDS)
    public const SIZED_TYPES = [
        'tshirt', 'jersey_upper', 'jersey_lower',
        'polo_shirt', 'jogging_pants', 'jacket', 'long_sleeves',
    ];

    // Types that use Width × Height × Qty (sq. ft) calculation
    public const SQFT_TYPES = [
        'tarpaulin',
        'sintraboard_sqft',
        'panaflex_no_light',
        'panaflex_with_light',
        'sticker_no_laminate',
        'sticker_with_laminate',
    ];

    public const SIZES = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'XXXXL', 'XXXXXL','KIDS'];

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
