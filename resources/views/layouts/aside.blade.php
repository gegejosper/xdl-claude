<!--begin::Aside-->
<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{ Auth::user()->dashboard_route() }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <span style="
                font-family: 'Inter', sans-serif;
                font-weight: 900;
                font-size: 1.25rem;
                letter-spacing: -0.5px;
                line-height: 1;
            ">
                <span style="color:#C8C8C8;">XAN</span><span style="color:#CC0000;">DOC</span><span style="color:#C8C8C8;">LUY</span>
            </span>
        </a>
        <!--end::Logo-->
        <!--begin::Aside toggler-->
        <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle me-n2" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
            <i class="ki-outline ki-double-left fs-1 rotate-180"></i>
        </div>
        <!--end::Aside toggler-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                
                <!--begin:Menu item-->
                @php
                    $dashboard_url    = Auth::user()->dashboard_route();
                    $is_dashboard_active = request()->is('panel/admin') || request()->is('panel/staff/dashboard') || request()->is('panel/cashier') || request()->is('panel/dashboard');
                @endphp
                <div class="menu-item {{ $is_dashboard_active ? 'here' : '' }}">
                    <a class="menu-link {{ $is_dashboard_active ? 'active' : '' }}" href="{{ $dashboard_url }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-home-2 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>
                {{-- Transactions --}}
                <div class="menu-item">
                    <a href="{{ route('transactions.index') }}" class="menu-link {{ (request()->segment(2) == 'transactions') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-receipt-square fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Job Orders</span>
                    </a>
                </div>
                <!--end:Menu item-->
                {{-- Customers --}}
                <div class="menu-item">
                    <a href="/panel/customers" class="menu-link {{ (request()->segment(2) == 'customers') ? 'active' : '' }}">
                        <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor"/>
                        <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
                        <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor"/>
                        <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
                        </svg>
                        </span>
                        <span class="menu-title">Customers</span>
                    </a>
                </div>

                {{-- Purchases --}}
                <div class="menu-item">
                    <a href="{{ route('expenses.purchases') }}" class="menu-link {{ (request()->segment(2) == 'expenses' && request()->segment(3) == 'purchases') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-parcel fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">Purchases</span>
                    </a>
                </div>

                {{-- Expenses --}}
                <div class="menu-item">
                    <a href="{{ route('expenses.index') }}" class="menu-link {{ (request()->segment(2) == 'expenses' && request()->segment(3) != 'purchases') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">Expenses</span>
                    </a>
                </div>
                <!--end:Menu item-->

                {{-- Daily Sales --}}
                <div class="menu-item">
                    <a href="{{ route('daily-sales.index') }}" class="menu-link {{ (request()->segment(2) == 'daily-sales') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-chart-simple fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Daily Sales</span>
                    </a>
                </div>

                {{-- Reports --}}
                <div class="menu-item">
                    <a href="{{ route('reports.outstanding-balances') }}" class="menu-link {{ (request()->segment(2) == 'reports') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-document fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Outstanding Balances</span>
                    </a>
                </div>
                @can('manage-users')
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item  menu-accordion {{ (request()->segment(2) == 'users' || request()->segment(2) == 'roles' || request()->segment(2) == 'permissions') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-user-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                </i>
                        </span>
                        <span class="menu-title">User Management</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item {{ (request()->segment(2) == 'users') ? 'here show' : '' }} menu-accordion mb-1">
                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-bullet">
                                    <i class="ki-duotone ki-user">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Users</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ (request()->segment(2) == 'users' && request()->segment(3) == null ) ? 'active' : '' }}" href="/panel/users">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Users List</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ (request()->segment(2) == 'users' && request()->segment(3) == 'create') ? 'active' : '' }}" href="{{route('users.create')}}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Create</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                            </div>
                            <!--end:Menu sub-->
                        </div>
                        <!--end:Menu item-->
                        @can('manage-users-related')
                        <!--begin:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item {{ (request()->segment(2) == 'roles') ? 'here show' : '' }} menu-accordion mb-1">
                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-bullet">
                                    <i class="ki-duotone ki-book">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Roles</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ (request()->segment(2) == 'roles' && request()->segment(3) == null ) ? 'active' : '' }} " href="/panel/roles">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Roles List</span>
                                    </a>
                                    <!--end:Menu link-->
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ (request()->segment(2) == 'roles' && request()->segment(3) == 'create' ) ? 'active' : '' }} " href="{{route('roles.create')}}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Create</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                                
                            </div>
                            <!--end:Menu sub-->
                        </div>
                        <!--end:Menu item-->
                         <!--begin:Menu item-->
                        <div data-kt-menu-trigger="click" class="menu-item {{ (request()->segment(2) == 'permissions') ? 'here show' : '' }} menu-accordion mb-1 ">
                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-bullet">
                                    <i class="ki-duotone ki-book-open">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Permissions</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ (request()->segment(2) == 'permissions' && request()->segment(3) == null ) ? 'active' : '' }} " href="/panel/permissions">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Permissions List</span>
                                    </a>
                                    <!--end:Menu link-->
                                    <!--begin:Menu link-->
                                    <a class="menu-link {{ (request()->segment(2) == 'permissions' && request()->segment(3) == 'create' ) ? 'active' : '' }} " href="{{route('permissions.create')}}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Create</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                                
                            </div>
                            <!--end:Menu sub-->
                        </div>
                        <!--end:Menu item-->
                        @endcan
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                @endcan
                @can('manage-settings')
                

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ (request()->segment(2) == 'logs' || request()->segment(2) == 'binding_devices') ? 'here show' : '' }}">

                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-solid ki-gear fs-2"></i>
                        </span>
                        <span class="menu-title">Settings</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        {{-- Branches --}}
                        <div class="menu-item">
                            <a href="{{ route('branches.index') }}" class="menu-link {{ (request()->segment(2) == 'branches') ? 'active' : '' }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-home fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Branches</span>
                            </a>
                        </div>
                        {{-- Item Prices --}}
                        <div class="menu-item">
                            <a href="{{ route('item_prices.index') }}" class="menu-link {{ (request()->segment(2) == 'item-prices') ? 'active' : '' }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-price-tag fs-2">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Item Prices</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ ( request()->segment(2) == 'binding_devices' ) ? 'active' : '' }} " href="{{route('binding_devices.index')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Binding Devices</span>
                            </a>
                            <!--end:Menu link-->
                            <!--begin:Menu link-->
                            <a class="menu-link {{ (request()->segment(2) == 'logs' ) ? 'active' : '' }} " href="{{route('logs.index')}}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Logs</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->

                    </div>
                    <!--end:Menu sub-->
                </div>
                @endcan
                <!--end:Menu item-->

               
            </div>
            <!--end::Menu-->
        </div>
    </div>
    <!--end::Aside menu-->
    <!--begin::Footer-->
    <div class="aside-footer flex-column-auto pb-7 px-5" id="kt_aside_footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="#"
                    onclick="event.preventDefault();
                    this.closest('form').submit();" class="btn btn-custom btn-primary w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click">
                <span class="btn-label">LOGOUT</span>
                <i class="ki-outline ki-document btn-icon fs-2"></i>
            </a>
        </form>
    </div>
    <!--end::Footer-->
</div>
<!--end::Aside-->