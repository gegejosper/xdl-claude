
@extends('layouts.panel')
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Toolbar-->
	<div class="toolbar" id="kt_toolbar">
		<!--begin::Container-->
		<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack"></div>
		<!--end::Container-->
	</div>
	<!--end::Toolbar-->
	<!--begin::Post-->
	<div class="post d-flex flex-column-fluid" id="kt_post">
		<!--begin::Container-->
		<div id="kt_content_container" class="container-xxl">
			<!--begin::Card-->
			<div class="card p-5">
				  @if (session('success'))
                    <div class="alert alert-success" id ="success-alert">
                        {{ session('success') }}
                    </div>
                @endif
				<!--begin::Card header-->
				<div class="card-header border-0 pt-6">
					<!--begin::Card title-->
					<div class="card-title">
						<!--begin::Search-->
						<div class="d-flex align-items-center position-relative my-1">
							<i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
							<input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search" />
						</div>
						<!--end::Search-->
					</div>
					<!--begin::Card title-->
					<!--begin::Card toolbar-->
					<div class="card-toolbar">
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
								<th class="min-w-125px">Users</th>
                                <th>Browser</th>
                                <th>OS</th>
								<th>Screen Size</th>
                                <th>Status</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>
						<tbody class="text-gray-600 fw-semibold">
						@foreach ($users as $user)
						  @if($user->devices->first())
						  <tr>
								<td>{{ucwords($user->name)}}</td>
								<td>{{ optional($user->devices->first())->device_browser }}</td>
								<td>{{ optional($user->devices->first())->device_os }}</td>
								<td>{{ optional($user->devices->first())->device_resolution }}</td>
								<td>
									@if($user->devices->first())
										<span class="text-success">Bind</span>
									@else
										<span class="text-danger">Unbind</span>
									@endif
								</td>
								<td>
									<center>
									<form action="{{ url('panel/binding_devices/' . optional($user->devices->first())->id) }}" method="POST" style="display:inline;">
										@csrf
										@method('DELETE')
										<button type="button" class="btn btn-danger btn-sm rounded @if(!$user->devices->first()) disabled @endif kt_unbind_alert">
											Remove
										</button>
									</form>
									</center>
								</td>
							</tr>
							@endif
						@endforeach
						</tbody>
					</table>
					<!--end::Table-->
					<div class="d-flex justify-content-end">
						{{ $users->links() }}
					</div>
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
<script src="{{asset('assets/js/widgets.bundle.js')}}"></script>
<script src="{{asset('assets/js/custom/widgets.js')}}"></script>
<script src="{{asset('assets/js/custom/alert.js')}}"></script>
<!-- <script src="{{asset('assets/js/custom/apps/chat/chat.js')}}"></script>
<script src="{{asset('assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
<script src="{{asset('assets/js/custom/utilities/modals/create-app.js')}}"></script>
<script src="{{asset('assets/js/custom/utilities/modals/users-search.js')}}"></script> -->
@endsection