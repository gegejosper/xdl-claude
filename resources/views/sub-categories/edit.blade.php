@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Subcategory</h2>
    <form action="{{ route('subcategories.update', $subcategory->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $subcategory->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $subcategory->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ old('status', $subcategory->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $subcategory->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('subcategories.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection