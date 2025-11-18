@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Category</h2>
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $category->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ old('status', $category->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $category->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection