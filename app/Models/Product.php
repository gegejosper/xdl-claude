<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'subcategory_id', 'sku', 'barcode', 'name', 
        'description', 'unit', 'cost_price', 'selling_price', 'reorder_level', 'status'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
