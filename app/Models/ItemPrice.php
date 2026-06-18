<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    protected $fillable = ['item_type', 'unit_price'];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public static function for_type(string $type): float
    {
        return (float)(static::where('item_type', $type)->value('unit_price') ?? 0);
    }
}
