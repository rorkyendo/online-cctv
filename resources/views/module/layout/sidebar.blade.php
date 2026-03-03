<div id="kt_app_sidebar" class="app-sidebar flex-column"
     data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
     data-kt-drawer-width="225px" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ url('/panel/dashboard') }}">
            <img alt="Logo" src="{{ asset($data['identitas']->logo ?? 'assets/media/logos/default-logo.svg') }}"
                 class="h-50px app-sidebar-logo-default" />
            <img alt="Logo" src="{{ asset($data['identitas']->icon ?? 'assets/media/logos/default-small.svg') }}"
                 class="h-30px app-sidebar-logo-minimize" />
        </a>
        <!--begin::Sidebar toggle-->
        <div id="kt_app_sidebar_toggle"
             class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
             data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
             data-kt-toggle-name="app-sidebar-minimize">
            <span class="svg-icon svg-icon-2 rotate-180">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor"/>
                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor"/>
                </svg>
            </span>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->

    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper"
             class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
             data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
             data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
             data-kt-scroll-save-state="true">
            <div class="menu menu-column menu-rounded menu-sub-indention px-3"
                 id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">MAIN MENU</span>
                    </div>
                </div>
                @php
                    use App\Facades\GeneralModelFacade as GeneralModel;
                    $currentUrl   = request()->path();
                    $urlSegments  = explode('/', $currentUrl);
                    $parentModule = $urlSegments[1] ?? null;
                    $module       = $urlSegments[2] ?? null;
                @endphp
                @if(isset($data['parentModul']) && is_iterable($data['parentModul']))
                @foreach($data['parentModul'] as $parent)
                    @if(is_object($parent) && isset($parent->class) && \App\Helpers\AccessHelper::cekParentModulAkses($parent->class))
                        @if($parent->child_module === 'Y')
                            @php $activeClass = (strtoupper($parentModule) === strtoupper($parent->class)) ? 'here show' : ''; @endphp
                            <div data-kt-menu-trigger="click" class="menu-item {{ $activeClass }} menu-accordion">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <span class="svg-icon svg-icon-2">
                                            <i class="{!! $parent->icon !!}"></i>
                                        </span>
                                    </span>
                                    <span class="menu-title">{{ $parent->nama_parent_modul }}</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion">
                                    @php
                                        $modules = GeneralModel::getByMultiIdGeneral('cv_modul','all','class_parent_modul',$parent->class,'tampil_sidebar','Y');
                                    @endphp
                                    @foreach($modules as $moduleItem)
                                        @if(\App\Helpers\AccessHelper::cekModulAkses($moduleItem->controller_modul))
                                            @php $activeItemClass = (strtoupper($module) === strtoupper($moduleItem->controller_modul)) ? 'active' : ''; @endphp
                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeItemClass }}" href="{{ url($moduleItem->link_modul) }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ $moduleItem->nama_modul }}</span>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php $activeClass = (strtoupper($parentModule) === strtoupper($parent->class)) ? 'active' : ''; @endphp
                            <div class="menu-item">
                                <a class="menu-link {{ $activeClass }}" href="{{ url($parent->link) }}">
                                    <span class="menu-icon">
                                        <span class="svg-icon svg-icon-2">
                                            <i class="{!! $parent->icon !!}"></i>
                                        </span>
                                    </span>
                                    <span class="menu-title">{{ $parent->nama_parent_modul }}</span>
                                </a>
                            </div>
                        @endif
                    @endif
                @endforeach
                @endif
            </div>
        </div>
    </div>
    <!--end::sidebar menu-->

    <!--begin::Footer-->
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="{{ url('/panel/panduan') }}"
           class="btn btn-flex flex-center btn-custom btn-light-info overflow-hidden text-nowrap px-0 h-35px w-100 mb-2">
            <span class="btn-label fs-7">
                <i class="bi bi-book-half me-2"></i>Panduan Kamera
            </span>
        </a>
        <a href="{{ url('/panel/profile') }}"
           class="btn btn-flex flex-center btn-custom btn-secondary overflow-hidden text-nowrap px-0 h-40px w-100 mb-2">
            <span class="btn-label">
                <i class="bi bi-person-circle me-2"></i>
                {{ session('user')['nama_lengkap'] ?? 'Profile' }}
            </span>
        </a>
        <a href="{{ url('/logout') }}"
           class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100">
            <span class="btn-label">Logout</span>
            <span class="svg-icon btn-icon svg-icon-2 m-0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z" fill="currentColor"/>
                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"/>
                </svg>
            </span>
        </a>
    </div>
    <!--end::Footer-->
</div>
