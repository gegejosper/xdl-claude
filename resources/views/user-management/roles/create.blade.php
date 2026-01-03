@extends('layouts.panel')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Toolbar-->
	<div class="toolbar" id="kt_toolbar">
		<!--begin::Container-->
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
		</div>
		<!--end::Container-->
	</div>
	<!--end::Toolbar-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <!--begin::Card header-->
				<div class="card-header border-0 pt-6">
					<!--begin::Card title-->
					<div class="card-title">
						 <h2>Create Role</h2>
					</div>
					<!--begin::Card title-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body py-4">
                   @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="role_name" class="form-label fw-semibold">Role Name</label>
                            <input 
                                type="text" 
                                id="role_name" 
                                name="name" 
                                class="form-control" 
                                placeholder="Enter role name" 
                                value="{{ old('name') }}" 
                                required
                            >
                        </div>
                        <div class="col-md-4">
                            <label for="role_level" class="form-label fw-semibold">Role Level</label>
                            <input 
                                type="number" 
                                id="role_level" 
                                name="level" 
                                class="form-control" 
                                placeholder="Enter level" 
                                value="{{ old('level') }}" 
                                min="1" 
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Permissions</label>

                        <div class="form-check mb-3">
                            <input 
                                type="checkbox" 
                                id="select_all" 
                                class="form-check-input"
                            >
                            <label for="select_all" class="form-check-label fw-bold">
                                Select All Permissions
                            </label>
                        </div>

                        <div class="d-flex flex-wrap gap-3">
                            @foreach($permissions as $permission)
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        id="permission_{{ $permission->id }}" 
                                        name="permissions[]" 
                                        value="{{ $permission->id }}" 
                                        class="form-check-input permission_checkbox"
                                    >
                                    <label 
                                        for="permission_{{ $permission->id }}" 
                                        class="form-check-label text-capitalize"
                                    >
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="btn btn-success">Save</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select_all = document.getElementById('select_all');
        const checkboxes = document.querySelectorAll('.permission_checkbox');

        select_all.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = select_all.checked;
            });
        });

        // Optional: auto-update "Select All" when all/none are manually toggled
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const all_checked = Array.from(checkboxes).every(cb => cb.checked);
                select_all.checked = all_checked;
            });
        });
    });
</script>
@endsection