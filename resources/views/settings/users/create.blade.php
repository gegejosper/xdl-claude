@extends('layouts.panel')

@section('content')
<h2>Create User</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name') }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
               value="{{ old('username') }}">
        @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>

    <div class="mb-3">
        <label for="role_id" class="form-label">Role</label>
        <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
            <option value="">-- Select Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                    {{ old('role_id', isset($user) && $user->roles->first() ? $user->roles->first()->id : '') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Permissions</label>
        <div class="row">
            @foreach($permissions as $permission)
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" name="permission_ids[]" value="{{ $permission->id }}"
                               class="form-check-input @error('permission_ids') is-invalid @enderror"
                               id="perm_{{ $permission->id }}"
                               {{ in_array($permission->id, old('permission_ids', isset($user) ? $user->permissions->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        @error('permission_ids')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-success">Save</button>
</form>
@endsection