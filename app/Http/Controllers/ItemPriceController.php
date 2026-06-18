<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemPrice;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Validator;

class ItemPriceController extends Controller
{
    // ─── Settings page ────────────────────────────────────────────────────────

    public function index()
    {
        $types  = TransactionItem::ITEM_TYPES;
        $prices = ItemPrice::whereIn('item_type', array_keys($types))
            ->pluck('unit_price', 'item_type');

        return view('item_prices.index', compact('types', 'prices'));
    }

    // ─── Bulk save prices ────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prices'   => 'required|array',
            'prices.*' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $valid_types = array_keys(TransactionItem::ITEM_TYPES);

        foreach ($request->prices as $type => $price) {
            if (!in_array($type, $valid_types, true)) continue;

            ItemPrice::updateOrCreate(
                ['item_type'  => $type],
                ['unit_price' => (float)$price]
            );
        }

        return response()->json(['success' => true, 'message' => 'Prices saved.']);
    }

    // ─── AJAX price lookup ────────────────────────────────────────────────────

    public function get_price(string $type)
    {
        $valid_types = array_keys(TransactionItem::ITEM_TYPES);

        if (!in_array($type, $valid_types, true)) {
            return response()->json(['unit_price' => 0]);
        }

        return response()->json([
            'unit_price' => ItemPrice::for_type($type),
        ]);
    }
}
