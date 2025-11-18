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
                <h1 class="d-flex align-items-center text-gray-900 fw-bold my-1 fs-3">Multipurpose</h1>
                <!--end::Title-->
                <!--begin::Separator-->
                <span class="h-20px border-gray-200 border-start mx-4"></span>
                <!--end::Separator-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="index.html" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">Dashboards</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-900">Default</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center py-1">
                <!--begin::Wrapper-->
                <div class="me-4">
                    <!--begin::Menu-->
                    <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                    <i class="ki-outline ki-filter fs-5 text-gray-500 me-1"></i>Filter</a>
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_68c106db43e7d">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Menu separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Menu separator-->
                        <!--begin::Form-->
                        <div class="px-7 py-5">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <!--begin::Label-->
                                <label class="form-label fw-semibold">Status:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div>
                                    <select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_menu_68c106db43e7d" data-allow-clear="true">
                                        <option></option>
                                        <option value="1">Approved</option>
                                        <option value="2">Pending</option>
                                        <option value="2">In Process</option>
                                        <option value="2">Rejected</option>
                                    </select>
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <!--begin::Label-->
                                <label class="form-label fw-semibold">Member Type:</label>
                                <!--end::Label-->
                                <!--begin::Options-->
                                <div class="d-flex">
                                    <!--begin::Options-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                        <span class="form-check-label">Author</span>
                                    </label>
                                    <!--end::Options-->
                                    <!--begin::Options-->
                                    <label class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="2" checked="checked" />
                                        <span class="form-check-label">Customer</span>
                                    </label>
                                    <!--end::Options-->
                                </div>
                                <!--end::Options-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <!--begin::Label-->
                                <label class="form-label fw-semibold">Notifications:</label>
                                <!--end::Label-->
                                <!--begin::Switch-->
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked" />
                                    <label class="form-check-label">Enabled</label>
                                </div>
                                <!--end::Switch-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                                <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Form-->
                    </div>
                    <!--end::Menu 1-->
                    <!--end::Menu-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Button-->
                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app" id="kt_toolbar_primary_button">Create</a>
                <!--end::Button-->
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Row-->
            <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
                <!--begin::Col-->
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                    <!--begin::Card widget 20-->
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10" style="background-color: #F1416C;background-image:url('assets/media/patterns/vector-1.png')">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Amount-->
                                <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">69</span>
                                <!--end::Amount-->
                                <!--begin::Subtitle-->
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Active Projects</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body d-flex align-items-end pt-0">
                            <!--begin::Progress-->
                            <div class="d-flex align-items-center flex-column mt-3 w-100">
                                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                                    <span>43 Pending</span>
                                    <span>72%</span>
                                </div>
                                <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                                    <div class="bg-white rounded h-8px" role="progressbar" style="width: 72%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <!--end::Progress-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card widget 20-->
                    <!--begin::Card widget 7-->
                    <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Amount-->
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">357</span>
                                <!--end::Amount-->
                                <!--begin::Subtitle-->
                                <span class="text-gray-500 pt-1 fw-semibold fs-6">Professionals</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body d-flex flex-column justify-content-end pe-0">
                            <!--begin::Title-->
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Today’s Heroes</span>
                            <!--end::Title-->
                            <!--begin::Users group-->
                            <div class="symbol-group symbol-hover flex-nowrap">
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
                                    <span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                                </div>
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michael Eberon">
                                    <img alt="Pic" src="assets/media/avatars/300-11.jpg" />
                                </div>
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Susan Redwood">
                                    <span class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
                                </div>
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Melody Macy">
                                    <img alt="Pic" src="assets/media/avatars/300-2.jpg" />
                                </div>
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Perry Matthew">
                                    <span class="symbol-label bg-danger text-inverse-danger fw-bold">P</span>
                                </div>
                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Barry Walter">
                                    <img alt="Pic" src="assets/media/avatars/300-12.jpg" />
                                </div>
                                <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
                                    <span class="symbol-label bg-dark text-gray-300 fs-8 fw-bold">+42</span>
                                </a>
                            </div>
                            <!--end::Users group-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card widget 7-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                    <!--begin::Card widget 17-->
                    <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <div class="card-title d-flex flex-column">
                                <!--begin::Info-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Currency-->
                                    <span class="fs-4 fw-semibold text-gray-500 me-1 align-self-start">$</span>
                                    <!--end::Currency-->
                                    <!--begin::Amount-->
                                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">69,700</span>
                                    <!--end::Amount-->
                                    <!--begin::Badge-->
                                    <span class="badge badge-light-success fs-base">
                                    <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>2.2%</span>
                                    <!--end::Badge-->
                                </div>
                                <!--end::Info-->
                                <!--begin::Subtitle-->
                                <span class="text-gray-500 pt-1 fw-semibold fs-6">Projects Earnings in April</span>
                                <!--end::Subtitle-->
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                            <!--begin::Chart-->
                            <div class="d-flex flex-center me-5 pt-2">
                                <div id="kt_card_widget_17_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div>
                            </div>
                            <!--end::Chart-->
                            <!--begin::Labels-->
                            <div class="d-flex flex-column content-justify-center flex-row-fluid">
                                <!--begin::Label-->
                                <div class="d-flex fw-semibold align-items-center">
                                    <!--begin::Bullet-->
                                    <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                                    <!--end::Bullet-->
                                    <!--begin::Label-->
                                    <div class="text-gray-500 flex-grow-1 me-4">Leaf CRM</div>
                                    <!--end::Label-->
                                    <!--begin::Stats-->
                                    <div class="fw-bolder text-gray-700 text-xxl-end">$7,660</div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Label-->
                                <!--begin::Label-->
                                <div class="d-flex fw-semibold align-items-center my-3">
                                    <!--begin::Bullet-->
                                    <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                                    <!--end::Bullet-->
                                    <!--begin::Label-->
                                    <div class="text-gray-500 flex-grow-1 me-4">Mivy App</div>
                                    <!--end::Label-->
                                    <!--begin::Stats-->
                                    <div class="fw-bolder text-gray-700 text-xxl-end">$2,820</div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Label-->
                                <!--begin::Label-->
                                <div class="d-flex fw-semibold align-items-center">
                                    <!--begin::Bullet-->
                                    <div class="bullet w-8px h-3px rounded-2 me-3" style="background-color: #E4E6EF"></div>
                                    <!--end::Bullet-->
                                    <!--begin::Label-->
                                    <div class="text-gray-500 flex-grow-1 me-4">Others</div>
                                    <!--end::Label-->
                                    <!--begin::Stats-->
                                    <div class="fw-bolder text-gray-700 text-xxl-end">$45,257</div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Labels-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card widget 17-->
                    <!--begin::List widget 26-->
                    <div class="card card-flush h-lg-50">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <h3 class="card-title text-gray-800 fw-bold">External Links</h3>
                            <!--end::Title-->
                            <!--begin::Toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Menu-->
                                <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                                    <i class="ki-outline ki-dots-square fs-1 text-gray-500 me-n1"></i>
                                </button>
                                <!--begin::Menu 2-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">Quick Actions</div>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator mb-3 opacity-75"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">New Ticket</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">New Customer</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
                                        <!--begin::Menu item-->
                                        <a href="#" class="menu-link px-3">
                                            <span class="menu-title">New Group</span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <!--end::Menu item-->
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Admin Group</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Staff Group</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3">Member Group</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu sub-->
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">New Contact</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu separator-->
                                    <div class="separator mt-3 opacity-75"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content px-3 py-3">
                                            <a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu 2-->
                                <!--end::Menu-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-5">
                            <!--begin::Item-->
                            <div class="d-flex flex-stack">
                                <!--begin::Section-->
                                <a href="#" class="text-primary fw-semibold fs-6 me-2">Avg. Client Rating</a>
                                <!--end::Section-->
                                <!--begin::Action-->
                                <button type="button" class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                    <i class="ki-outline ki-exit-right-corner fs-2"></i>
                                </button>
                                <!--end::Action-->
                            </div>
                            <!--end::Item-->
                            <!--begin::Separator-->
                            <div class="separator separator-dashed my-3"></div>
                            <!--end::Separator-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack">
                                <!--begin::Section-->
                                <a href="#" class="text-primary fw-semibold fs-6 me-2">Instagram Followers</a>
                                <!--end::Section-->
                                <!--begin::Action-->
                                <button type="button" class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                    <i class="ki-outline ki-exit-right-corner fs-2"></i>
                                </button>
                                <!--end::Action-->
                            </div>
                            <!--end::Item-->
                            <!--begin::Separator-->
                            <div class="separator separator-dashed my-3"></div>
                            <!--end::Separator-->
                            <!--begin::Item-->
                            <div class="d-flex flex-stack">
                                <!--begin::Section-->
                                <a href="#" class="text-primary fw-semibold fs-6 me-2">Google Ads CPC</a>
                                <!--end::Section-->
                                <!--begin::Action-->
                                <button type="button" class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                    <i class="ki-outline ki-exit-right-corner fs-2"></i>
                                </button>
                                <!--end::Action-->
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::LIst widget 26-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xxl-6">
                    <!--begin::Engage widget 10-->
                    <div class="card card-flush h-md-100">
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0" style="background-position: 100% 50%; background-image:url('assets/media/stock/900x600/42.png')">
                            <!--begin::Wrapper-->
                            <div class="mb-10">
                                <!--begin::Title-->
                                <div class="fs-2hx fw-bold text-gray-800 text-center mb-13">
                                <span class="me-2">Try our all new Enviroment with 
                                <br />
                                <span class="position-relative d-inline-block text-danger">
                                    <a href="pages/user-profile/overview.html" class="text-danger opacity-75-hover">Pro Plan</a>
                                    <!--begin::Separator-->
                                    <span class="position-absolute opacity-15 bottom-0 start-0 border-4 border-danger border-bottom w-100"></span>
                                    <!--end::Separator-->
                                </span></span>for Free</div>
                                <!--end::Title-->
                                <!--begin::Action-->
                                <div class="text-center">
                                    <a href='#' class="btn btn-sm btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Upgrade Now</a>
                                </div>
                                <!--begin::Action-->
                            </div>
                            <!--begin::Wrapper-->
                            <!--begin::Illustration-->
                            <img class="mx-auto h-150px h-lg-200px theme-light-show" src="assets/media/illustrations/misc/upgrade.svg" alt="" />
                            <img class="mx-auto h-150px h-lg-200px theme-dark-show" src="assets/media/illustrations/misc/upgrade-dark.svg" alt="" />
                            <!--end::Illustration-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Engage widget 10-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
           
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
@endsection
@section('jslinks')
<script src="{{asset('')}}"></script>
@endsection