<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    
    public function index()
    {
        $subcategories = SubCategory::with('category')->get();
        return view('sub-categories.sub-categories', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('sub-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|unique:sub_categories,name',
            'description' => 'nullable|string',
            'status' => 'required',
        ]);

        SubCategory::create($data);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function edit(SubCategory $subcategory)
    {
        $categories = Category::all();
        return view('sub-categories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subcategory)
    {
        $data = $request->validate([
            'name'        => 'required|string|unique:sub_categories,name,' . $subcategory->id,
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required',
        ]);

        $subcategory->update($data);


        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}
