@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Product</h2>
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Subcategory</label>
            <select name="subcategory_id" class="form-control" required>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" {{ $product->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                        {{ $subcategory->name }} ({{ $subcategory->category->name }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>SKU</label>
            <input type="text" name="sku" value="{{ $product->sku }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Barcode</label>
            <input type="text" name="barcode" value="{{ $product->barcode }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Unit</label>
            <input type="text" name="unit" value="{{ $product->unit }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Cost Price</label>
            <input type="number" step="0.01" name="cost_price" value="{{ $product->cost_price }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Selling Price</label>
            <input type="number" step="0.01" name="selling_price" value="{{ $product->selling_price }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Reorder Level</label>
            <input type="number" name="reorder_level" value="{{ $product->reorder_level }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection