@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subcategories</h2>
    <a href="{{ route('subcategories.create') }}" class="btn btn-primary mb-3">Add Subcategory</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subcategories as $subcategory)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $subcategory->category->name }}</td>
                <td>{{ $subcategory->name }}</td>
                <td>{{ $subcategory->description }}</td>
                <td>
                    <span class="badge bg-{{ $subcategory->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($subcategory->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('subcategories.edit', $subcategory->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this subcategory?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection