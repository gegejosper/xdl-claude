@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Products</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Subcategory</th>
                <th>Category</th>
                <th>SKU</th>
                <th>Name</th>
                <th>Unit</th>
                <th>Cost Price</th>
                <th>Selling Price</th>
                <th>Reorder Level</th>
                 <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->subcategory->name }}</td>
                <td>{{ $product->subcategory->category->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->unit }}</td>
                <td>{{ $product->cost_price }}</td>
                <td>{{ $product->selling_price }}</td>
                <td>{{ $product->reorder_level }}</td>
                <td>
                    <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection