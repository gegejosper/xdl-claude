
@extends('layouts.panel')
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Toolbar-->
	<div class="toolbar" id="kt_toolbar">
		<!--begin::Container-->
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
			<!--begin::Page title-->
			<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap lh-1">
				<!--begin::Title-->
				<h1 class="d-flex align-items-center text-gray-900 fw-bold my-1 fs-3">Users List</h1>
				<!--end::Title-->
				<!--begin::Separator-->
				<span class="h-20px border-gray-200 border-start mx-4"></span>
				<!--end::Separator-->
				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">
						<a href="/panel/dashboard" class="text-muted text-hover-primary">Dashboard</a>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-300 w-5px h-2px"></span>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">User Management</li>
					<!--end::Item-->
					
				</ul>
				<!--end::Breadcrumb-->
			</div>
			<!--end::Page title-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Toolbar-->
	<!--begin::Post-->
	<div class="post d-flex flex-column-fluid" id="kt_post">
		<!--begin::Container-->
		<div id="kt_content_container" class="container-xxl">
			<!--begin::Card-->
			<div class="card">
				<!--begin::Card header-->
				<div class="card-header border-0 pt-6">
					<!--begin::Card title-->
					<div class="card-title">
						<!--begin::Search-->
						<div class="d-flex align-items-center position-relative my-1">
							<i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
							<input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search user" />
						</div>
						<!--end::Search-->
					</div>
					<!--begin::Card title-->
					<!--begin::Card toolbar-->
					<div class="card-toolbar">
						<!--begin::Toolbar-->
						<div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
						
							<!--begin::Menu 1-->
							
							<!--end::Menu 1-->
							<!--end::Filter-->
						
							<!--begin::Add user-->
							<button type="button" onclick="window.location.href='users/create';" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
							<i class="ki-outline ki-plus fs-2"></i>Add User</button>
							<!--end::Add user-->
						</div>
						<!--end::Toolbar-->
						<!--begin::Group actions-->
						<div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
							<div class="fw-bold me-5">
							<span class="me-2" data-kt-user-table-select="selected_count"></span>Selected</div>
							<button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete Selected</button>
						</div>
						<!--end::Group actions-->
					</div>
					<!--end::Card toolbar-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body py-4">
					<!--begin::Table-->
					<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
						<thead>
							<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
								<!-- <th class="w-10px pe-2">
									<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
										<input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_table_users .form-check-input" value="1" />
									</div>
								</th> -->
								<th class="min-w-125px">User</th>
								<th class="min-w-125px">Role</th>
								<th class="min-w-125px">Device Details</th>
                        		<th class="min-w-125px">Binding</th>
								<th class="min-w-125px">Restricted</th>
								<th class="min-w-125px">Status</th>
								<th class="text-end min-w-100px">Actions</th>
							</tr>
						</thead>
						<tbody class="text-gray-600 fw-semibold">
							@foreach($users as $user)
							  @if(auth()->user()->hasRole('superadmin') || !$user->hasRole('superadmin'))
								<tr>
									<!-- <td>
										<div class="form-check form-check-sm form-check-custom form-check-solid">
											<input class="form-check-input" type="checkbox" value="{{$user->id}}" />
										</div>
									</td> -->
									<td class="d-flex align-items-center">
										<!--begin:: Avatar -->
										<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
											<a href="/panel/users/{{ $user->id }}">
												<div class="symbol-label">
													<img src="{{asset('assets/media/avatars/blank.png')}}" alt="Emma Smith" class="w-100" />
												</div>
											</a>
										</div>
										<!--end::Avatar-->
										<!--begin::User details-->
										<div class="d-flex flex-column">
											<a href="apps/user-management/users/view.html" class="text-gray-800 text-hover-primary mb-1">{{ $user->name }}</a>
											<span>{{ $user->email }}</span>
										</div>
										<!--begin::User details-->
									</td>
									<td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
									<td>
										
									</td>
									<td></td>
									<td></td>
									<td>{{ $user->status }}</td>
									<td class="text-end">
										<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions 
										<i class="ki-outline ki-down fs-5 ms-1"></i></a>
										<!--begin::Menu-->
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="/panel/users/{{ $user->id }}" class="menu-link px-3">View</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="{{ route('users.edit', $user) }}" class="menu-link px-3">Edit</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
										@if($user->devices->first())
										<div class="menu-item px-3">
											<form action="{{ url('panel/users/unbind/' . optional($user->devices->first())->id) }}" method="POST" style="display:inline;">
												@csrf
												@method('DELETE') 
												<button type="submit" class="menu-link px-3 border-0 bg-transparent kt_unbind_alert">
													Unbind
												</button>
											</form>
										</div>
										@endif
										<!--end::Menu item-->
										
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<form action="{{ route('users.restricted', $user->id) }}" method="POST" style="display:inline;">
												@csrf
												@method('PATCH')
												<input type="hidden" id="restrict_msg" value="{{$user->restriction == 'yes' ? 'unrestrected' : 'restricted'}}"/>
												<button type="submit" class="menu-link px-3 border-0 bg-transparent kt_restrict_alert"> 
													{{ $user->restriction == 'yes' ? 'Unrestricted' : 'Restricted' }}
												</button>
											</form>
										</div>
										<!--end::Menu item-->
										</div>
										<!--end::Menu-->
									</td>
								</tr>
							   @endif
							 @endforeach
						</tbody>
					</table>
					<!--end::Table-->
				</div>
				<!--end::Card body-->
			</div>
			<!--end::Card-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Post-->
</div>
<!--end::Content-->
@endsection
@section('jslinks')
<script src="{{asset('assets/js/custom/apps/user-management/users/list/table.js')}}"></script>
<script src="{{asset('assets/js/custom/apps/user-management/users/list/export-users.js')}}"></script>
<script src="{{asset('assets/js/custom/apps/user-management/users/list/add.js')}}"></script>
<script src="{{asset('assets/js/widgets.bundle.js')}}"></script>
<script src="{{asset('assets/js/custom/widgets.js')}}"></script>
<!-- <script src="{{asset('assets/js/custom/apps/chat/chat.js')}}"></script>
<script src="{{asset('assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
<script src="{{asset('assets/js/custom/utilities/modals/create-app.js')}}"></script>
<script src="{{asset('assets/js/custom/utilities/modals/users-search.js')}}"></script> -->
@endsection