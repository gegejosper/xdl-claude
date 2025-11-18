@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Subcategory</h2>
    <form action="{{ route('subcategories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button class="btn btn-success">Save</button>
        <a href="{{ route('subcategories.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection