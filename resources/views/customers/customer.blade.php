@extends('layouts.panel')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-100px symbol-circle mb-7 text-center">
                              
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Name-->
                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{$customer->last_name}}, {{$customer->first_name}}</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="fs-5 fw-bold text-muted mb-6">{{$customer->status}}</div>
                                <!--end::Position-->
                              
                            </div>
                            <!--end::Summary-->
                            
                            <div class="separator separator-dashed my-3"></div>
                            <!--begin::Details content-->
                            <div id="kt_customer_view_details" class="collapse show">
                                <div class="py-5 fs-6">
                                    <!--begin::Badge-->
                                    <div class="badge badge-light-info d-inline">{{ucfirst($customer->status)}} Account</div>
                                    <!--begin::Badge-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">Account ID</div>
                                    <div class="text-gray-600">ID-45453423</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">Email</div>
                                    <div class="text-gray-600">
                                        <a href="mailto:{{$customer->email}}" class="text-gray-600 text-hover-primary">{{$customer->email}}</a>
                                    </div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">Billing Address</div>
                                    <div class="text-gray-600">{{$customer->address}}, {{$customer->brgy ? $customer->brgy.',' : ''}} 
                                    <br>{{$customer->city_num ? $customer->city_num.',' : ''}} 
                                    <br>{{$customer->province ? $customer->province.',' : ''}}</div>
                                    <!--begin::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">Contact #</div>
                                    <div class="text-gray-600">{{$customer->mobile_num}}</div>
                                    <!--begin::Details item-->
                                  
                                    <!--begin::Details item-->
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                   
                </div>
                <!--end::Sidebar-->
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-15">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_customer_view_transactions_tab">Transaction History</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_customer_view_payments_tab">Payment History</a>
                        </li>
                        <!--end:::Tab item-->
                       
                        <!--begin:::Tab item-->
                        <li class="nav-item ms-auto">
                            <!--begin::Action menu-->
                            <a href="#" class="btn btn-primary ps-7 text-black" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">Actions
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                            <span class="svg-icon svg-icon-2 text-black me-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon--></a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold py-4 w-250px fs-6" data-kt-menu="true">
                                
                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">Account</div>
                                </div>
                                <!--end::Menu item-->
                            
                                <!--begin::Menu item-->
                                <div class="menu-item px-5 my-1">
                                    <a href="javascript:;" class="menu-link px-5 edit-customer"
                                        data-customer_id="{{$customer->id}}"
                                        data-customer_last_name="{{$customer->last_name}}"
                                        data-customer_first_name="{{$customer->first_name}}"
                                        data-customer_mobile_num="{{$customer->mobile_num}}"
                                        data-customer_address="{{$customer->address}}"
                                    >Update Account</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="javascript:;" class="menu-link text-danger px-5 block-customer" 
                                        data-customer_id="{{$customer->id}}"
                                        data-customer_status="blocked"
                                    >Block Account</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                            <!--end::Menu-->
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin:::Tab content-->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="kt_customer_view_transactions_tab" role="tabpanel">
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2>Transaction History</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0 pb-5">
                                    <div id="kt_table_customers_payment_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <div class="table-responsive">
                                            <table class="table" id="transactionsTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Time</th>
                                                        <th scope="col">Transaction #</th>
                                                        <th scope="col">Reciept #</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Balance</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $balance = 0; 
                                                        $count = 0;
                                                    ?>
                                                    @foreach($transactions as $transaction)
                                                    <tr class="row{{$transaction->id}}">
                                                    <?php 
                                                    if($transaction->payment_status != 'paid' && $transaction->payment_status != "canceled"){
                                                        $balance += $transaction->balance; 
                                                    }
                                                       
                                                    ?>  
                                                        <td>{{$transaction->id}}</td>
                                                        <td>{{$transaction->created_at->format('M-d-Y')}}  </td>   
                                                        <td>{{$transaction->created_at->format('h:i A')}}  </td>   
                                                        <td>
                                                        {{$transaction->transaction_number}}
                                                        </td>
                                                        <td>
                                                        {{$transaction->or_number}}
                                                        </td>
                                                        <td>{{number_format($transaction->total_amount,2)}}</td>
                                                        
                                                        <td>{{number_format($transaction->balance,2)}}</td>
                                                        <td>
                                                            {{$transaction->payment_status}}
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                $name = $transaction->customer_details->last_name.', '.$transaction->customer_details->first_name;
                                                            ?>
                                                            @if($transaction->payment_status != 'paid' && $transaction->payment_status != "canceled")
                                                            <a href="javascript:;" class="btn btn-success btn-icon btn-sm btn-bg-light pay-transaction"
                                                                data-transaction_id = "{{$transaction->id}}"
                                                                data-customer_name = "{{$name}}"
                                                                data-customer_id = "{{$transaction->customer_id}}"
                                                                data-balance = "{{$transaction->balance}}"
															>
															<span class="svg-icon svg-icon-success svg-icon-2">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<path opacity="0.3" d="M20 18H4C3.4 18 3 17.6 3 17V7C3 6.4 3.4 6 4 6H20C20.6 6 21 6.4 21 7V17C21 17.6 20.6 18 20 18ZM12 8C10.3 8 9 9.8 9 12C9 14.2 10.3 16 12 16C13.7 16 15 14.2 15 12C15 9.8 13.7 8 12 8Z" fill="currentColor"/>
																<path d="M18 6H20C20.6 6 21 6.4 21 7V9C19.3 9 18 7.7 18 6ZM6 6H4C3.4 6 3 6.4 3 7V9C4.7 9 6 7.7 6 6ZM21 17V15C19.3 15 18 16.3 18 18H20C20.6 18 21 17.6 21 17ZM3 15V17C3 17.6 3.4 18 4 18H6C6 16.3 4.7 15 3 15Z" fill="currentColor"/>
																</svg>
															</span>
															</a>
                                                            @endif
                                                            <a href="/panel/cashier/transactions/{{$transaction->id}}"  class="btn btn-info btn-icon btn-sm btn-bg-light"
                                                            >
                                                            <span class="svg-icon svg-icon-warning svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"/>
                                                            </svg></span>
                                                            </a>
                                                           
                                                           
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                        $count += 1; 
                                                    ?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <h3 class="text-danger">Balance: {{number_format($balance,2)}}</h3>
                                        </div>
                                        <!--end::Table-->
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="tab-pane fade" id="kt_customer_view_payments_tab" role="tabpanel">
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2>Payment History</h2>
                                    </div>
                                    
                                </div>
                                <div class="card-body pt-0 pb-5">
                                    <div id="kt_table_customers_payment_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <div class="table-responsive">
                                            <table class="table" id="transactionsTable">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Time</th>
                                                        <th scope="col">Transaction #</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Change</th>
                                                        <th scope="col">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $count = 1; 
                                                    ?>
                                                    @foreach($transaction_payment as $payment)
                                                    <tr class="row{{$transaction->id}}">
                                        
                                                        <td>{{$payment->created_at->format('M-d-Y')}}  </td>   
                                                        <td>{{$payment->created_at->format('h:i A')}}  </td>   
                                                        <td>
                                                        {{$payment->transaction_details->transaction_number}}
                                                        </td>
                                                        <td>{{number_format($payment->amount_paid,2)}}</td>
                                                        
                                                        <td>{{number_format($payment->change,2)}}</td>
                                                        <td>
                                                            {{$payment->status}}
                                                        </td>
                                                        
                                                    </tr>
                                                    <?php 
                                                        $count += 1; 
                                                    ?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Layout-->
            <!--begin::Modals-->
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
                                <div id="errors" hidden></div>
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
                                        
                                        <div class="col-xl-12">
                                            <div class="form-group fv-plugins-icon-container">
                                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                                    <span class="required">Address</span>
                                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="" data-bs-original-title="Customer's address is required" ></i>
                                                </label>
                                                <input type="text" class="form-control form-control-lg form-control-solid" name="edit_address" id="edit_address" placeholder="Lot #/ Block #, Etc" required>
                                                <span class="form-text text-muted">Please enter your address.</span>
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
                                                        <option value="{{$province->provCode}}">{{$province->provDesc}}</option>
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
                                    <div class="row">
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
                                        <div class="col-xl-4">
                                            <div class="form-group fv-plugins-icon-container">
                                                <label>Facebook</label>
                                                <input type="text" class="form-control form-control-lg form-control-solid" id="edit_facebook" name="edit_facebook" placeholder="http://facebook.com/skinaura" required>
                                                <span class="form-text text-muted">Please enter facebook link</span>
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
                    
                            <button type="button" class="btn btn-light-success btn-sm font-weight-bold closeblock" data-dismiss="modal"> <i class=" fas fa-check"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Modals-->
            
        </div>
        <!--end::Container-->
    </div>
</div>
<div class="modal bg-white fade" tabindex="-1" id="paymentModal">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Payment</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
					<span class="svg-icon svg-icon-danger svg-icon-2hx"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<path opacity="0.3" d="M12 10.6L14.8 7.8C15.2 7.4 15.8 7.4 16.2 7.8C16.6 8.2 16.6 8.80002 16.2 9.20002L13.4 12L12 10.6ZM10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.3 16.5 8.5 16.5C8.7 16.5 8.99999 16.4 9.19999 16.2L12 13.4L10.6 12Z" fill="currentColor"/>
					<path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM13.4 12L16.2 9.20001C16.6 8.80001 16.6 8.19999 16.2 7.79999C15.8 7.39999 15.2 7.39999 14.8 7.79999L12 10.6L9.2 7.79999C8.8 7.39999 8.2 7.39999 7.8 7.79999C7.4 8.19999 7.4 8.80001 7.8 9.20001L10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.3 16.5 8.5 16.5C8.7 16.5 9 16.4 9.2 16.2L12 13.4L14.8 16.2C15 16.4 15.3 16.5 15.5 16.5C15.7 16.5 16 16.4 16.2 16.2C16.6 15.8 16.6 15.2 16.2 14.8L13.4 12Z" fill="currentColor"/>
					</svg></span>
                </div>
            </div>

            <div class="modal-body">
				<div class="row mb-10">
					<!--begin::Col-->
					<div class="col-xl-12">
						<div class="card card-flush py-4">
							<!--begin::Card body-->
							<div class="card-body pt-0">
								<div class="row justify-content-center">	
									<div class="col-lg-6 bg-light-primary p-10 align-self-center">
										<div class="row">
											<div class="col-lg-12">
												<form id="submit_payment">
													@csrf
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12">
															<div class=" border-gray-300 py-5 card-rounded bg-lighten">
																<label for="">Name</label>
																<input type="text"  name="customer_name_payment" id="customer_name_payment" style="text-align: center;" class="form-control text-dark input-text-large fs-2hx" readonly disabled> 
															</div>
														</div>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<div class=" border-gray-300 py-5 card-rounded bg-lighten">
																<label for="">Balance</label>
																<input type="number"  name="transcaction_balance" id="transcaction_balance" style="text-align: right;" class="form-control text-dark input-text-large fs-2hx" readonly disabled> 
															</div>
														</div>
														<div class="col-lg-6 col-md-6 col-sm-12">
															<div class=" border-gray-300 py-5 card-rounded bg-lighten">
																<label for="">Amount Paid</label>
																<input type="number"  name="transaction_amount_paid" id="transaction_amount_paid" style="text-align: right;" class="form-control text-dark fs-2hx validate-input" value="0" required min="0"> 
																
															</div>
														</div>
													</div>
													<div id="transaction_payment_errors">
														
													</div>
													<div class=" border-gray-300 px-9 card-rounded bg-lighten">
														<input type="hidden" class="validate-input" name="transaction_customer_id" id="transaction_customer_id"> 
														<input type="hidden" class="validate-input" name="transaction_id" id="transaction_id"> 
														<button type="submit" class="btn btn-success" id="process_payment" style="float:right; margin-top:5px;" disabled>Process</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									
								</div>
								
							</div>
							<!--end::Card header-->
						</div>
					</div>
					<!--end::Col-->
				</div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmPaymentModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Submit this transaction payment?</h5>
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
                    <div class="alert alert-danger" role="alert"> Are you sure you want to submit this transaction payment?</div>
                </div>
				<div id="payment_errors">
														
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-warning btn-sm font-weight-bold" data-bs-dismiss="modal"> <i class=" fas fa-times"></i> Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold" id="confirm_payment"> 
						<span class="indicator-label">
                            <i class=" fas fa-check"></i> Submit
                        </span>
                        <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
					</button>
                </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  // print an image
  function ImagetoPrint(source){
    return "<html><head><script>function step1(){\n" +
           "setTimeout('step2()', 10);}\n" +
           "function step2(){window.print();window.close()}\n" +
           "</scri"+"pt></head><body onload='step1()'>\n" +
           "<img src='" + source + "'/></body></html>";  
  }

  function PrintImage(source){
     Pagelink = "about:blank";
     var pwa = window.open(Pagelink, "_new");
     pwa.document.open();
     pwa.document.write(ImagetoPrint(source));
     pwa.document.close();
  }
</script>
<script src="{{asset('js/app.js')}}"></script>  
<script src="{{asset('assets/js/customers.js')}}?v={{ filemtime(public_path('assets/js/customers.js')) }}"></script>
<script src="{{asset('assets/js/customer-payment.js')}}?v={{ filemtime(public_path('assets/js/customer-payment.js')) }}"></script>
@endsection
