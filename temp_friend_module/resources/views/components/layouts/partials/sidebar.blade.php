<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <!--begin::Scroll wrapper-->
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" 
            data-kt-scroll="true"
            data-kt-scroll-activate="true" 
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" 
            data-kt-scroll-offset="5px" 
            data-kt-scroll-save-state="true">
            
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                
                <!-- Dashboard -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                <!-- Section: PLM (Product Life Cycle Management) -->
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-primary">PLM WORKSPACE</span>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('styles.*') ? ' active' : '' }}" href="{{ route('styles.index') }}">
                        <span class="menu-icon">
                            <i class="fa-solid fa-shirt fs-3 text-primary"></i>
                        </span>
                        <span class="menu-title fw-bold">Styles Workspace</span>
                    </a>
                </div>

                <!-- Section: Master Data -->
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">MASTER DATA</span>
                    </div>
                </div>

                <!-- Master Files Dropdown -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion{{ request()->routeIs('masters.colors.*') || request()->routeIs('masters.sizes.*') || request()->routeIs('masters.scales.*') || request()->routeIs('masters.design-positions.*') || request()->routeIs('masters.constructions.*') ? ' show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-setting-2 fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Master Files</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.colors.*') ? ' active' : '' }}" href="{{ route('masters.colors.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Colors Master</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.sizes.*') ? ' active' : '' }}" href="{{ route('masters.sizes.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Sizes Master</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.scales.*') ? ' active' : '' }}" href="{{ route('masters.scales.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Scales Master</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.design-positions.*') ? ' active' : '' }}" href="{{ route('masters.design-positions.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Design Positions</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.constructions.*') ? ' active' : '' }}" href="{{ route('masters.constructions.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Constructions Master</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.sample-types.*') ? ' active' : '' }}" href="{{ route('masters.sample-types.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Sample Types Master</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.seasons.*') ? ' active' : '' }}" href="{{ route('masters.seasons.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Seasons Master</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.tech-pack-types.*') ? ' active' : '' }}" href="{{ route('masters.tech-pack-types.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Tech Pack Types</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.artwork-types.*') ? ' active' : '' }}" href="{{ route('masters.artwork-types.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Artwork Types Master</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.pattern-file-types.*') ? ' active' : '' }}" href="{{ route('masters.pattern-file-types.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Pattern File Types</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('masters.file-types.*') ? ' active' : '' }}" href="{{ route('masters.file-types.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">File Types Master</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Clients -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('clients.*') ? ' active' : '' }}" href="{{ route('clients.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-people fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title">Clients</span>
                    </a>
                </div>

                <!-- Section: System -->
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">SYSTEM</span>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('activity-logs.*') ? ' active' : '' }}" href="{{ route('activity-logs.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-time fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Activity Logs</span>
                    </a>
                </div>

            </div>
            <!--end::Menu-->
        </div>
        <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
</div>
