<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::all();
        return view('categories.categories', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
       $data = $request->validate([
            'name'   => 'required|string|unique:categories,name',
            'status' => 'required',
        ]);

        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // $request->validate(['name' => 'required|unique:categories,name,' . $category->id]);
        // $category->update($request->all());
        $data = $request->validate([
            'name'   => 'required|string|unique:categories,name,' . $category->id,
            'status' => 'required',
        ]);

        $category->update($data);

        return redirect()->route('categories.categories')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.categories')->with('success', 'Category deleted successfully.');
    }
}
