<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;
use Response;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('subcategory.category')->get();
        return view('products.products', compact('products'));
    }

    public function create()
    {
        $subcategories = Subcategory::with('category')->get();
        return view('products.create', compact('subcategories'));
    }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'subcategory_id' => 'required|exists:sub_categories,id',
    //         'sku' => 'required|string|unique:products,sku',
    //         'barcode' => 'nullable|string|unique:products,barcode',
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'unit' => 'required|string|max:50',
    //         'cost_price' => 'required|numeric|min:0',
    //         'selling_price' => 'required|numeric|min:0',
    //         'reorder_level' => 'nullable|integer|min:0',
    //         'status'      => 'required',
    //     ]);

    //     Product::create($data);

    //     return redirect()->route('products.index')->with('success', 'Product created successfully.');
    // }
    public function store(Request $request)
    {
        $data = $request->validate([
            'subcategory_id' => 'required|exists:sub_categories,id',
            'sku' => 'required|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'status' => 'required',
        ]);

        try {
            $product = Product::create($data);

            if ($product) {
                return redirect()
                    ->route('products.index')
                    ->with('success', 'Product created successfully.');
            } else {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Failed to save product. Please try again.']);
            }
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    public function edit(Product $product)
    {
        $subcategories = Subcategory::with('category')->get();
        return view('products.edit', compact('product', 'subcategories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'subcategory_id' => 'required|exists:sub_categories,id',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'status' => 'required',
        ]);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
