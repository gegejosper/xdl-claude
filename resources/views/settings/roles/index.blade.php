@extends('layouts.panel')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <!--begin::Card header-->
				<div class="card-header border-0 pt-6">
					<!--begin::Card title-->
					<div class="card-title">
						 <h2>Roles</h2>
					</div>
					<!--begin::Card title-->
					<!--begin::Card toolbar-->
					<div class="card-toolbar">
						<!--begin::Toolbar-->
						<div class="d-flex justify-content-end">
							<!--begin::Filter-->
							<a href="{{ route('roles.create') }}" class="btn btn-light-primary me-3">
							<i class="fas fa-plus"></i>Create Role</a>
					</div>
					<!--end::Card toolbar-->
				</div>
				<!--end::Card header-->
				
            </div>
            <div class="post d-flex flex-column-fluid" id="kt_post">
                <!--begin::Container-->
                <div id="kt_content_container" class="container-xxl">
                    <!--begin::Row-->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9 mb-10">
                        @foreach($roles as $role)
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Card-->
                                <div class="card card-flush h-md-100">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2>{{ ucfirst($role->name) }}</h2>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="card-body pt-1">
                                        <!--begin::Users-->
                                        <div class="fw-bold text-gray-600 mb-5">
                                            Total users with this role: {{ $role->users->count() }}
                                        </div>
                                        <!--end::Users-->

                                        <!--begin::Permissions-->
                                        <div class="d-flex flex-column text-gray-600">
                                            @if($role->permissions->count() > 0)
                                                @foreach($role->permissions as $permission)
                                                    <div class="d-flex align-items-center py-2">
                                                        <span class="bullet bg-primary me-3"></span>
                                                        {{ ucfirst($permission->name) }}
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-muted fst-italic">No permissions assigned</div>
                                            @endif
                                        </div>
                                        <!--end::Permissions-->
                                    </div>
                                    <!--end::Card body-->

                                    <!--begin::Card footer-->
                                    <div class="card-footer flex-wrap pt-0">
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-light btn-active-light-primary me-5">
                                            <i class="fas fa-pencil"></i>Edit Role</a>
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-ligh btn-danger my-1" onclick="return confirm('Delete this role?')"> <i class="fas fa-times"></i>Delete</button>
                                        </form>
                                    </div>
                                    <!--end::Card footer-->
                                </div>
                                <!--end::Card-->
                            </div>
                            <!--end::Col-->
                        @endforeach
                        <!--begin::Add new card-->
                        <div class="ol-md-4">
                            <!--begin::Card-->
                            <div class="card h-md-100">
                                <!--begin::Card body-->
                                <div class="card-body d-flex flex-center">
                                    <!--begin::Button-->
                                    <a href="{{route('roles.create')}}" class="btn btn-clear d-flex flex-column flex-center">
                                        <!--begin::Illustration-->
                                        <img src="{{asset('assets/media/illustrations/unitedpalms-1/4.png')}}" alt="" class="mw-100 mh-150px mb-7" />
                                        <!--end::Illustration-->
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-3 text-gray-600 text-hover-primary">Add New Role</div>
                                        <!--end::Label-->
                                    </a>
                                    <!--begin::Button-->
                                </div>
                                <!--begin::Card body-->
                            </div>
                            <!--begin::Card-->
                        </div>
                        <!--begin::Add new card-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Modals-->
                    <!--begin::Modal - Add role-->
                    <div class="modal fade" id="kt_modal_add_role" tabindex="-1" aria-hidden="true">
                        <!--begin::Modal dialog-->
                        <div class="modal-dialog modal-dialog-centered mw-750px">
                            <!--begin::Modal content-->
                            <div class="modal-content">
                                <!--begin::Modal header-->
                                <div class="modal-header">
                                    <!--begin::Modal title-->
                                    <h2 class="fw-bold">Add a Role</h2>
                                    <!--end::Modal title-->
                                    <!--begin::Close-->
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
                                        <i class="ki-outline ki-cross fs-1"></i>
                                    </div>
                                    <!--end::Close-->
                                </div>
                                <!--end::Modal header-->
                                <!--begin::Modal body-->
                                <div class="modal-body scroll-y mx-lg-5 my-7">
                                    <!--begin::Form-->
                                    <form id="kt_modal_add_role_form" class="form" action="#">
                                        <!--begin::Scroll-->
                                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_role_header" data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="fs-5 fw-bold form-label mb-2">
                                                    <span class="required">Role name</span>
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input class="form-control form-control-solid" placeholder="Enter a role name" name="role_name" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Permissions-->
                                            <div class="fv-row">
                                                <!--begin::Label-->
                                                <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                                                <!--end::Label-->
                                                <!--begin::Table wrapper-->
                                                <div class="table-responsive">
                                                    <!--begin::Table-->
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                                        <!--begin::Table body-->
                                                        <tbody class="text-gray-600 fw-semibold">
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <td class="text-gray-800">Administrator Access 
                                                                <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Allows a full access to the system">
                                                                    <i class="ki-outline ki-information fs-7"></i>
                                                                </span></td>
                                                                <td>
                                                                    <!--begin::Checkbox-->
                                                                    <label class="form-check form-check-custom form-check-solid me-9">
                                                                        <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" />
                                                                        <span class="form-check-label" for="kt_roles_select_all">Select all</span>
                                                                    </label>
                                                                    <!--end::Checkbox-->
                                                                </td>
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">User Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="user_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="user_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="user_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Content Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="content_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="content_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="content_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Financial Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="financial_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="financial_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="financial_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Reporting</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="reporting_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="reporting_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="reporting_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Payroll</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="payroll_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="payroll_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="payroll_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Disputes Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="disputes_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="disputes_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="disputes_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">API Controls</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="api_controls_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="api_controls_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="api_controls_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Database Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="database_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="database_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="database_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Repository Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Options-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="repository_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="repository_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="repository_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Options-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                        </tbody>
                                                        <!--end::Table body-->
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <!--end::Table wrapper-->
                                            </div>
                                            <!--end::Permissions-->
                                        </div>
                                        <!--end::Scroll-->
                                        <!--begin::Actions-->
                                        <div class="text-center pt-15">
                                            <button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">Discard</button>
                                            <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                                                <span class="indicator-label">Submit</span>
                                                <span class="indicator-progress">Please wait... 
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Modal body-->
                            </div>
                            <!--end::Modal content-->
                        </div>
                        <!--end::Modal dialog-->
                    </div>
                    <!--end::Modal - Add role-->
                    <!--begin::Modal - Update role-->
                    <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
                        <!--begin::Modal dialog-->
                        <div class="modal-dialog modal-dialog-centered mw-750px">
                            <!--begin::Modal content-->
                            <div class="modal-content">
                                <!--begin::Modal header-->
                                <div class="modal-header">
                                    <!--begin::Modal title-->
                                    <h2 class="fw-bold">Update Role</h2>
                                    <!--end::Modal title-->
                                    <!--begin::Close-->
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
                                        <i class="ki-outline ki-cross fs-1"></i>
                                    </div>
                                    <!--end::Close-->
                                </div>
                                <!--end::Modal header-->
                                <!--begin::Modal body-->
                                <div class="modal-body scroll-y mx-5 my-7">
                                    <!--begin::Form-->
                                    <form id="kt_modal_update_role_form" class="form" action="#">
                                        <!--begin::Scroll-->
                                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header" data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="fs-5 fw-bold form-label mb-2">
                                                    <span class="required">Role name</span>
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input class="form-control form-control-solid" placeholder="Enter a role name" name="role_name" value="Developer" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Permissions-->
                                            <div class="fv-row">
                                                <!--begin::Label-->
                                                <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                                                <!--end::Label-->
                                                <!--begin::Table wrapper-->
                                                <div class="table-responsive">
                                                    <!--begin::Table-->
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                                        <!--begin::Table body-->
                                                        <tbody class="text-gray-600 fw-semibold">
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <td class="text-gray-800">Administrator Access 
                                                                <span class="ms-1" data-bs-toggle="tooltip" title="Allows a full access to the system">
                                                                    <i class="ki-outline ki-information-5 text-gray-500 fs-6"></i>
                                                                </span></td>
                                                                <td>
                                                                    <!--begin::Checkbox-->
                                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                                        <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" />
                                                                        <span class="form-check-label" for="kt_roles_select_all">Select all</span>
                                                                    </label>
                                                                    <!--end::Checkbox-->
                                                                </td>
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">User Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="user_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="user_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="user_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Content Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="content_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="content_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="content_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Financial Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="financial_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="financial_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="financial_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Reporting</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="reporting_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="reporting_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="reporting_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Payroll</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="payroll_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="payroll_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="payroll_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Disputes Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="disputes_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="disputes_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="disputes_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">API Controls</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="api_controls_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="api_controls_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="api_controls_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Database Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="database_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="database_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="database_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                            <!--begin::Table row-->
                                                            <tr>
                                                                <!--begin::Label-->
                                                                <td class="text-gray-800">Repository Management</td>
                                                                <!--end::Label-->
                                                                <!--begin::Input group-->
                                                                <td>
                                                                    <!--begin::Wrapper-->
                                                                    <div class="d-flex">
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="repository_management_read" />
                                                                            <span class="form-check-label">Read</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                            <input class="form-check-input" type="checkbox" value="" name="repository_management_write" />
                                                                            <span class="form-check-label">Write</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                        <!--begin::Checkbox-->
                                                                        <label class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" value="" name="repository_management_create" />
                                                                            <span class="form-check-label">Create</span>
                                                                        </label>
                                                                        <!--end::Checkbox-->
                                                                    </div>
                                                                    <!--end::Wrapper-->
                                                                </td>
                                                                <!--end::Input group-->
                                                            </tr>
                                                            <!--end::Table row-->
                                                        </tbody>
                                                        <!--end::Table body-->
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <!--end::Table wrapper-->
                                            </div>
                                            <!--end::Permissions-->
                                        </div>
                                        <!--end::Scroll-->
                                        <!--begin::Actions-->
                                        <div class="text-center pt-15">
                                            <button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">Discard</button>
                                            <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                                                <span class="indicator-label">Submit</span>
                                                <span class="indicator-progress">Please wait... 
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Modal body-->
                            </div>
                            <!--end::Modal content-->
                        </div>
                        <!--end::Modal dialog-->
                    </div>
                    <!--end::Modal - Update role-->
                    <!--end::Modals-->
                </div>
                <!--end::Container-->
            </div>
        </div>
    </div>
</div>
@endsection