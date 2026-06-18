@extends('layouts.panel')

@section('content')
 
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Customers" name="search_customers" id="search_customers">
                </div>
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
            @can('manage-admin')
                <!--begin::Filter menu-->
                <div class="m-0">
                    <!--begin::Menu toggle-->
                    <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                    <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor"></path>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->Filter</a>
                    <!--end::Menu toggle-->
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_624475d256e37">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bolder">Filter Options</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Menu separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Menu separator-->
                        <form action="/panel/customers/filter/branch" method="post">
                        @csrf
                        <!--begin::Form-->
                        <div class="px-7 py-5">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <!--begin::Label-->
                                <label class="form-label fw-bold">Branch:</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <div>
                                    <select name="branch" class="form-select form-select-solid select2-hidden-accessible" data-kt-select2="true" data-placeholder="Select branch" data-dropdown-parent="#kt_menu_624475d256e37" data-allow-clear="true" data-select2-id="select2-data-7-wpba" tabindex="-1" aria-hidden="true">
                                        <option data-select2-id="select2-data-9-d5ue"></option>
                                        @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>

                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            
                            
                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Form-->
                        </form>
                    </div>
                    <!--end::Menu 1-->
                </div>
                <!--end::Filter menu-->
            @endcan
                <!--begin::Secondary button-->
                <button type="button" class="btn btn-sm btn-primary text-dark me-3 " data-bs-toggle="modal" data-bs-target="#modal_customer"> <i class="fa fa-plus text-dark"></i> Add</button>
                <!-- <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_customers_export_modal">
                
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2" rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor"></rect>
                        <path d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z" fill="currentColor"></path>
                        <path d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z" fill="#C4C4C4"></path>
                    </svg>
                </span>Export</button> -->
                
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Container-->
    </div>
    
    <div class="d-flex flex-column-fluid mt-10">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Row-->
                <div class="row justify-content-center">
                    
                    <div class="col-lg-12">
                        <!--begin::Card-->
                        <div class="card">
                            
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Table-->
                                <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_customers_table">
                                            <!--begin::Table head-->
                                            <thead>
                                                <!--begin::Table row-->
                                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                    
                                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" aria-label="Customer Name: activate to sort column ascending" style="width: 171.9375px;">Customer Name</th>
                                                    
                                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" aria-label="Email: activate to sort column ascending" style="width: 225.21875px;">Contact Number</th>
                                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" aria-label="Company: activate to sort column ascending" style="width: 190.265625px;">Branch</th>
                                                    <th class="min-w-325px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" aria-label="Customer Name: activate to sort column ascending" style="width: 171.9375px;">Address</th>
                                                    <th class="min-w-105px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" aria-label="Customer Name: activate to sort column ascending" style="width: 171.9375px;">Balance</th>
                                                    <th class="max-w-50px" tabindex="0">Status</th>
                                                    <th class="text-end min-w-150px" rowspan="1" colspan="1" aria-label="Actions" style="width: 132.984375px;">Actions</th></tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fw-bold text-gray-600 customers-list"> 
                                                @foreach($customers as $customer)  
                                                <tr>
                                                    <td>
                                                        @if($page_name != 'Blacklist Customer')
                                                        <a href="/panel/customers/{{$customer->id}}" class="text-gray-800 text-hover-primary mb-1">{{$customer->last_name}}, {{$customer->first_name}}</a>
                                                        @else
                                                        <a href="" class="text-gray-800 text-hover-primary mb-1">{{$customer->last_name}}, {{$customer->first_name}}</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$customer->mobile_num}}
                                                    </td>
                                                    <td>{{$customer->branch_details->branch_name}}</td>
                                                    <td>
                                                        {{$customer->address}}, {{$customer->brgy}}, {{$customer->city_num}}, {{$customer->province}}
                                                    </td>
                                                    <td>
                                                    @if($customer->transaction_details->count() != 0)
                                                        <?php $balance = 0; ?>
                                                        @foreach($customer->transaction_details as $transaction)
                                                        <?php $balance += $transaction->balance; ?>
                                                        @endforeach
                                                        {{number_format($balance,2)}}
                                                    @else
                                                    0.00
                                                    @endif
                                                    </td>
                                                    <?php 
                                                        $status = '';
                                                        $classAdd = '';
                                                        if($customer->status === 'active'){
                                                            $classAdd = 'badge-light-success';
                                                        }
                                                        else {
                                                            $classAdd = 'badge-light-danger';
                                                        }
                                                    ?>
                                                    <td >
                                                        <span id="customer_status_{{$customer->id}}" class="badge {{$classAdd}}">{{$customer->status}}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        @if($page_name != 'Blacklist Customer')
                                                        <a href="javascript:;" id="customer_edit_{{$customer->id}}" class="btn btn-icon btn-active-light-info edit-customer"
                                                            data-customer_id="{{$customer->id}}"
                                                            data-customer_last_name="{{$customer->last_name}}"
                                                            data-customer_first_name="{{$customer->first_name}}"
                                                            data-customer_mobile_num="{{$customer->mobile_num}}"
                                                            data-customer_address="{{$customer->address}}"
                                                            data-customer_facebook="{{$customer->facebook}}"
                                                        >
                                                                <span class="svg-icon svg-icon-3">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path>
                                                                    <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                       
                                                        @if($customer->status == 'active')
                                                        <a href="javascript:;" id="blockcustomer{{$customer->id}}" class="btn btn-icon btn-active-light-warning block-customer"
                                                            data-customer_id="{{$customer->id}}"
                                                            data-customer_status="blocked">
                                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen050.svg-->
                                                            <span class="svg-icon svg-icon-muted svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
                                                            <rect x="9" y="13.0283" width="7.3536" height="1.2256" rx="0.6128" transform="rotate(-45 9 13.0283)" fill="currentColor"/>
                                                            <rect x="9.86664" y="7.93359" width="7.3536" height="1.2256" rx="0.6128" transform="rotate(45 9.86664 7.93359)" fill="currentColor"/>
                                                            </svg></span>
                                                            <!--end::Svg Icon-->
                                                        </a>
                                                        @elseif($customer->status == 'blocked')
                                                        <a href="javascript:;" id="blockcustomer{{$customer->id}}" class="btn btn-icon btn-active-light-info block-customer"
                                                            data-customer_id="{{$customer->id}}"
                                                            data-customer_status="active">
                                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen048.svg-->
                                                            <span class="svg-icon svg-icon-muted svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"/>
                                                            <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="currentColor"/>
                                                            </svg></span>
                                                            <!--end::Svg Icon-->
                                                        </a>
                                                        @else
                                                        @endif
                                                        <a href="/panel/customers/{{$customer->id}}" class="btn btn-icon btn-active-light-success">
                                                            <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen021.svg-->
                                                            <span class="svg-icon svg-icon-muted svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"/>
                                                            </svg></span>
                                                            <!--end::Svg Icon-->
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex align-items-center justify-content-center">
                                            <div class="dataTables_paginate paging_simple_numbers" id="kt_customers_table_paginate">
                                            {{$customers->appends(request()->input())->links('pagination::bootstrap-4')}} 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
            <!--end::Row-->
            
            <!--end::Dashboard-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!-- Modal Customer -->
<div class="modal fade" id="modal_customer" tabindex="-1" style="display: none;" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-850px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <!--begin::Modal title-->
                <h2 id="modal_title">Add Customer</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <!-- <form id="kt_modal_new_card_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#"> -->
                    <!--begin::Input group-->
                    @csrf
                    <div class="row mb-10">
                        <div id="add_customer_errors" class="d-none mb-3"></div>
                        <!--begin::Row-->
                        <div class="row fv-row fv-plugins-icon-container mb-5">
                            <!--begin::Col-->
                            <div class="col-6">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">First Name</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="First name is required" ></i>
                                </label>
                                <!--end::Label-->
                                <input type="text" id="first_name" class="form-control form-control-solid" placeholder="First Name of Customer" name="first_name">
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-6">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">Last Name</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Last name is required"></i>
                                </label>
                                <!--end::Label-->
                                <input type="text" id="last_name" class="form-control form-control-solid" placeholder="Last Name of Customer" name="last_name">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mt-3 mb-5">
                            <!-- <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Branch</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's province is required" ></i>
                                    </label>
                                    <select name="branch_id" id="branch_id" class="form-control form-control-lg form-control-solid" required>
                                    @php
                                        $user = Auth::user();
                                        $assignedBranch = $user->user_branch_assign->branch_id ?? null;
                                        $branches = $assignedBranch ? $branches->whereIn('id', [$assignedBranch]) : $branches;
                                    @endphp

                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $branch->id == $assignedBranch ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                        
                                    </select>
                                <div class="fv-plugins-message-container"></div></div>
                            </div> -->
                            <div class="col-xl-8">
                                <div class="form-group fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Address</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's address is required" ></i>
                                    </label>
                                    <input type="text" class="form-control form-control-lg form-control-solid" name="address" placeholder="Lot #/ Block #, Etc" id="address" required>
                                    
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Contact Number</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's contact number is required" ></i>
                                    </label>
                                    <input type="text" class="form-control form-control-lg form-control-solid" name="mobile_num" placeholder="0999-999-9999" id="mobile_num" required>
                                   
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                        </div>
                        <!--end::Row-->
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Province</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's province is required" ></i>
                                    </label>
                                    <select name="province" id="province" class="form-control form-control-lg form-control-solid">
                                        <option value="">-- Select Province --</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ e($province->prov_code) }}"
                                                {{ $province->prov_code === $default_province ? 'selected' : '' }}>
                                                {{ e($province->prov_desc) }}
                                            </option>
                                        @endforeach
                                    </select>
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container spinner-border text-success" role="status" id="loading_city_municipality">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="form-group fv-plugins-icon-container" id="municipality">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">City/Municipality</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's city/municipality is required" ></i>
                                    </label>
                                   <select name="city_municipality" id="city_municipality" class="form-control form-control-lg form-control-solid">
                                    <option value="">-- Select Municipality --</option>
                                    @foreach ($municipalities as $municipality)
                                        <option value="{{ e($municipality->citymun_code) }}"
                                            {{ $municipality->citymun_code === $default_citymun ? 'selected' : '' }}>
                                            {{ e($municipality->citymun_desc) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container spinner-border text-success" role="status" id="loading_barangay">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="form-group fv-plugins-icon-container" id="div_barangay">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Barangay</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's barangay is required" ></i>
                                    </label>
                                    <select name="barangay" id="barangay" class="form-control form-control-lg form-control-solid">
                                        <option value="">-- Select Barangay --</option>
                                        @foreach ($barangays as $barangay)
                                            <option value="{{ e($barangay->brgy_code) }}">
                                                {{ e($barangay->brgy_desc) }}
                                            </option>
                                        @endforeach
                                    </select>
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                        </div>
                       
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center pt-5">
                        <button type="reset" id="kt_modal_new_card_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                        <button type="submit" id="addcustomer" class="btn btn-primary">
                            <span class="indicator-label">Save</span>
                            <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                
                <!-- </form> -->
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!-- End Modal Customer -->
<!-- Update Customer Modal-->
<div class="modal fade" id="modal_edit_customer" tabindex="-1" style="display: none;" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-850px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <!--begin::Modal title-->
                <h2 id="modal_title">Edit Customer</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="update_customer_form" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <!--begin::Input group-->
                    @csrf
                    <div class="row mb-10">
                    <div id="edit_customer_errors" class="d-none mb-3"></div>
                        <!--begin::Row-->
                        <div class="row fv-row fv-plugins-icon-container">
                            <!--begin::Col-->
                            <div class="col-6">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">First Name</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="First name is required" ></i>
                                </label>
                                <!--end::Label-->
                                <input type="text" id="edit_first_name" class="form-control form-control-solid" placeholder="First Name of Customer" name="edit_first_name">
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-6">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">Last Name</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Last name is required"></i>
                                </label>
                                <!--end::Label-->
                                <input type="text" id="edit_last_name" class="form-control form-control-solid" placeholder="Last Name of Customer" name="edit_last_name">
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mt-3">
                            
                            <div class="col-xl-8">
                                <div class="form-group fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Address</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's address is required" ></i>
                                    </label>
                                    <input type="text" class="form-control form-control-lg form-control-solid" name="edit_address" id="edit_address" placeholder="Lot #/ Block #, Etc" required>
                                    <span class="form-text text-muted">Please enter your address.</span>
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Contact Number</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's contact number is required" ></i>
                                    </label>
                                    <input type="text" class="form-control form-control-lg form-control-solid" name="edit_mobile_num" placeholder="0999-999-9999" id="edit_mobile_num" required>
                                    <span class="form-text text-muted">Please enter contact number.</span>
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                        </div>
                        <!--end::Row-->
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container">
                                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required">Province</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's province is required" ></i>
                                    </label>
                                    <select name="edit_province" id="edit_province" class="form-control form-control-lg form-control-solid" required>
                                        <option value="0"></option>
                                        @foreach($provinces as $province)
                                            <option value="{{$province->prov_code}}">{{$province->prov_desc}}</option>
                                        @endforeach
                                    </select>
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container spinner-border text-success" role="status" id="edit_loading_city_municipality">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="form-group fv-plugins-icon-container" id="edit_municipality">
                                    
                                    <label class="required">City/Town</label> <br>
                                    <select name="edit_city_municipality" id="edit_city_municipality" class="form-control form-control-lg form-control-solid" required>
                                   
                                    </select>
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group fv-plugins-icon-container spinner-border text-success" role="status" id="edit_loading_barangay">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="form-group fv-plugins-icon-container" id="edit_div_barangay">
                                    <label class="required">Barangay</label> <br>
                                    <select name="edit_barangay" id="edit_barangay" class="form-control form-control-lg form-control-solid" required>
                                        
                                    </select>
                                <div class="fv-plugins-message-container"></div></div>
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <input type="hidden" id="edit_customer_id" name="edit_customer_id"required>
                        <button type="reset" id="kt_modal_new_card_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                        <button type="submit" id="updatecustomer" class="btn btn-primary">
                            <span class="indicator-label">Update</span>
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
<!-- End Update Customer Modal -->
<div class="modal fade" id="blockcustomerModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Block customer</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
            </div>
            <div class="modal-body">
                @csrf
                <div class="alert alert-danger" role="alert"> Are you sure you want block/unblock this customer?</div>
            </div>
            <div class="modal-footer">
            <input type="hidden" class="form-control" name="customer_block_id" id="customer_block_id">
            <input type="hidden" class="form-control" name="customer_block_status" id="customer_block_status">
                <button type="button" class="btn btn-light-warning btn-sm font-weight-bold" data-bs-dismiss="modal"> <i class=" fas fa-times"></i> Cancel</button>
                <button type="button" class="btn btn-success btn-sm font-weight-bold" id="blockcustomer"> <i class=" fas fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerUpdateModalSuccess" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleSuccess">Update customer</h5>
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-message" role="alert"> Customer successfully updated...</div>
            </div>
            <div class="modal-footer">
           
                <button type="button" class="btn btn-light-success btn-sm font-weight-bold closeblock" data-bs-dismiss="modal"> <i class=" fas fa-check"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#province").change(function () {
            var value=$(this).val();
            $.ajax({
                type : 'get',
                url : '{{route("search_town")}}',
                data:{'search':value},
                success:function(data){
                    $("#loading_city_municipality").css("display", "block");
                    setTimeout(function(){
                        $("#loading_city_municipality").css("display", "none");
                        $("#municipality").css("display", "block");
                        $('#city_municipality').find('option').remove().end();
                        $('#barangay').find('option').remove().end();
                        $('#city_municipality').append(data);
                        brgy();
                    },500);
                } 
            });
        });

        $("#city_municipality").change(function () {
            var value=$(this).val();
            $.ajax({
                type : 'get',
                url : '{{route("search_barangay")}}',
                data:{'search':value},
                success:function(data){
                    $("#loading_barangay").css("display", "block");
                    setTimeout(function(){
                        $("#loading_barangay").css("display", "none");
                        $("#div_barangay").css("display", "block");
                        $('#barangay').find('option').remove().end();
                        $('#barangay').append(data);
                    },500);
                } 
            });
        });
        function brgy (){
            //console.log('run me');
            var value=$('#city_municipality').val();
            $.ajax({
                type : 'get',
                url : '{{route("search_barangay")}}',
                data:{'search':value},
                success:function(data){
                    $("#loading_barangay").css("display", "block");
                    setTimeout(function(){
                        $("#loading_barangay").css("display", "none");
                        $("#div_barangay").css("display", "block");
                        $('#barangay').find('option').remove().end();
                        $('#barangay').append(data);
                    },500);
                } 
            });
      
        }
        // Edit Customer
        $("#edit_province").change(function () {
            var value=$(this).val();
            $.ajax({
                type : 'get',
                url : '{{route("search_town")}}',
                data:{'search':value},
                success:function(data){
                    $("#loading_city_municipality").css("display", "block");
                    setTimeout(function(){
                        $("#edit_loading_city_municipality").css("display", "none");
                        $("#edit_municipality").css("display", "block");
                        $('#edit_city_municipality').find('option').remove().end();
                        $('#edit_barangay').find('option').remove().end();
                        $('#edit_city_municipality').append(data);
                        edit_brgy();
                    },500);
                } 
            });
        });

        $("#edit_city_municipality").change(function () {
            var value=$(this).val();
            $.ajax({
                type : 'get',
                url : '{{route("search_barangay")}}',
                data:{'search':value},
                success:function(data){
                    $("#edit_loading_barangay").css("display", "block");
                    setTimeout(function(){
                        $("#edit_loading_barangay").css("display", "none");
                        $("#edit_div_barangay").css("display", "block");
                        $('#edit_barangay').find('option').remove().end();
                        $('#edit_barangay').append(data);
                    },500);
                } 
            });
        });
        function edit_brgy (){
            //console.log('run me');
            var value=$('#edit_city_municipality').val();
            $.ajax({
                type : 'get',
                url : '{{route("search_barangay")}}',
                data:{'search':value},
                success:function(data){
                    $("#edit_loading_barangay").css("display", "block");
                    setTimeout(function(){
                        $("#edit_loading_barangay").css("display", "none");
                        $("#edit_div_barangay").css("display", "block");
                        $('#edit_barangay').find('option').remove().end();
                        $('#edit_barangay').append(data);
                    },500);
                } 
            });
      
        }
    });
</script>
<script src="{{asset('assets/js/customers.js')}}"></script>
@endsection
