<!--begin::Aside-->
<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="/panel/dashboard">
            
            <span class="fs-2 text-white logo">[ BASE ] </span>
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
                 <div data-kt-menu-trigger="click" class="menu-item  menu-accordion {{ ( request()->segment(2) == 'dashboard') ? 'here' : '' }}">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="/panel/dashboard">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-home-2 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Dashboard | {{ Auth::user()->primary_role_name()}}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
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