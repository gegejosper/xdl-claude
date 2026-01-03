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
						 <h2>Permissions</h2>
					</div>
					<!--begin::Card title-->
					<!--begin::Card toolbar-->
					<div class="card-toolbar">
						<!--begin::Toolbar-->
						<div class="d-flex justify-content-end">
							<!--begin::Filter-->
							<a href="{{ route('permissions.create') }}" class="btn btn-light-primary me-3">
							<i class="fas fa-plus"></i>Create Permission</a>
                        </div>
					</div>
					<!--end::Card toolbar-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_permissions_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Name</th>
                                <th class="min-w-250px">Assigned to</th>
                                <th class="min-w-125px">Created Date</th>
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($permissions as $permission)
                                <tr>
                                    <!-- Permission Name -->
                                    <td>{{ ucfirst($permission->name) }}</td>

                                    <!-- Assigned Roles -->
                                    <td>
                                        @if($permission->roles->count() > 0)
                                            @foreach($permission->roles as $role)
                                                <a href="{{ route('roles.edit', $role) }}"
                                                class="badge badge-light-primary fs-7 m-1">
                                                    {{ ucfirst($role->name) }}
                                                </a>
                                            @endforeach
                                        @else
                                            <span class="badge badge-light fs-7 text-muted">Not assigned</span>
                                        @endif
                                    </td>

                                    <!-- Created Date -->
                                    <td>{{ $permission->created_at->format('d M Y, g:i a') }}</td>

                                    <!-- Actions -->
                                    <td class="text-end">
                                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this permission?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $permissions->links() }}
                    <!--end::Table-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
