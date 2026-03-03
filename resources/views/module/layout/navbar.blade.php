<div id="kt_app_header" class="app-header">
    <div class="app-container container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Sidebar mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <span class="svg-icon svg-icon-1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor"/>
                        <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor"/>
                    </svg>
                </span>
            </div>
        </div>
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{ url('/panel/dashboard') }}" class="d-lg-none">
                <img alt="Logo" src="{{ asset($data['identitas']->logo ?? 'assets/media/logos/default-logo.svg') }}" class="h-30px" />
            </a>
        </div>
        <!--begin::Header wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <div id="kt_app_header_menu" class="app-header-menu app-header-mobile-drawer align-items-stretch"
                 data-kt-drawer="true" data-kt-drawer-name="app-header-menu"
                 data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                 data-kt-drawer-width="225px" data-kt-drawer-direction="end"
                 data-kt-drawer-toggle="#kt_app_header_menu_toggle"></div>
            <!--begin::Navbar-->
            <div class="app-navbar flex-shrink-0">
                <!--begin::User menu-->
                <div class="app-navbar-item ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                    <div class="cursor-pointer symbol symbol-35px symbol-md-40px"
                         data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                         data-kt-menu-placement="bottom-end">
                        <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="user" />
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                         data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="user" />
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        {{ session('user')['nama_lengkap'] ?? '-' }}
                                        <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">
                                            {{ session('user')['hak_akses'] ?? '-' }}
                                        </span>
                                    </div>
                                    <span class="fw-semibold text-muted fs-7">
                                        {{ session('user')['email'] ?? session('user')['username'] ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ url('/panel/profile') }}" class="menu-link px-5">
                                <i class="bi bi-person-circle me-2"></i> My Profile
                            </a>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ url('/logout') }}" class="menu-link px-5">
                                <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::User menu-->
            </div>
            <!--end::Navbar-->
        </div>
        <!--end::Header wrapper-->
    </div>
</div>
