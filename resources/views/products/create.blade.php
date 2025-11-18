@extends('layouts.app')

@section('content')
<script>
document.addEventListener("DOMContentLoaded", function() {
    let inputs = document.querySelectorAll('.scan_input');

    inputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
            if (e.key === "Enter") {
                e.preventDefault(); // stop form submit
                let next_input = inputs[index + 1];
                if (next_input) {
                    next_input.focus();
                } else {

                    this.form.submit();
                }
            }
        });
    });
});
</script>
<div class="container">
    <h2>Add Product</h2>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Subcategory</label>
            <select name="subcategory_id" class="form-control" required>
                <option value="">-- Select Subcategory --</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}">
                        {{ $subcategory->name }} ({{ $subcategory->category->name }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control scan_input" required>
        </div>
        <div class="mb-3">
            <label>Barcode *</label>
            <input type="text" name="barcode" class="form-control scan_input">
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control scan_input" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control scan_input"></textarea>
        </div>
        <div class="mb-3">
            <label>Unit</label>
            <input type="text" name="unit" class="form-control scan_input" required>
        </div>
        <div class="mb-3">
            <label>Cost Price</label>
            <input type="text" inputmode="numeric" pattern="[0-9]*" step="0.01" name="cost_price" class="form-control scan_input"  required>
        </div>
        <div class="mb-3">
            <label>Selling Price</label>
            <input type="text" inputmode="numeric" pattern="[0-9]*" step="0.01" name="selling_price" class="form-control scan_input" required>
        </div>
        <div class="mb-3">
            <label>Reorder Level</label>
            
            <input type="text" inputmode="numeric" pattern="[0-9]*" name="reorder_level" class="form-control scan_input" value="0">
        </div>
       <input type="hidden" name="status" id="status" value="active">
        <button class="btn btn-success">Save</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection