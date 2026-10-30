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

                <!-- Section: PLM WORKSPACE -->
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

                <!-- Section: MASTER DATA -->
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">MASTER DATA</span>
                    </div>
                </div>

                <!-- Master Files Dropdown (PLM Masters) -->
                @php
                    $isMastersActive = request()->routeIs('masters.*');
                @endphp
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion{{ $isMastersActive ? ' show here' : '' }}">
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

                <!-- CertTrack Master Data Accordion -->
                @php
                    $isCertMasterActive = request()->is('master-data*');
                @endphp
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion{{ $isCertMasterActive ? ' show here' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-data fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">CertTrack Master Data</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('master-data.seasons.*') ? ' active' : '' }}" href="{{ route('master-data.seasons.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Season</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Section: OPERATIONS & COMPLIANCE (CertTrack) -->
                @if(!auth()->check() || auth()->user()->hasRole('super_admin') || auth()->user()->isAdmin() || !auth()->user()->hasAnyRole(['factory', 'lab']))
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">OPERATIONS & COMPLIANCE</span>
                    </div>
                </div>

                <!-- Users Accordion -->
                @php
                    $isUsersActive = request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('cpsc-tokens.*');
                @endphp
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion{{ $isUsersActive ? ' show here' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-profile-user fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Users Management</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('users.*') ? ' active' : '' }}" href="{{ route('users.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">User Account</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('roles.*') ? ' active' : '' }}" href="{{ route('roles.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">User Type / Roles</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('permissions.*') ? ' active' : '' }}" href="{{ route('permissions.grid') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Permissions Grid</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('cpsc-tokens.*') ? ' active' : '' }}" href="{{ route('cpsc-tokens.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">CPSC API Tokens</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Labs -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('labs.*') ? ' active' : '' }}" href="{{ route('labs.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-flask fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Labs</span>
                    </a>
                </div>

                <!-- Factories Accordion -->
                @php
                    $isFactoriesActive = request()->routeIs('factories.*') || request()->routeIs('certificates.*');
                @endphp
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion{{ $isFactoriesActive ? ' show here' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-shop fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title">Factories</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('factories.index') ? ' active' : '' }}" href="{{ route('factories.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">All Factories</span>
                            </a>
                        </div>
                        @if(request()->routeIs('certificates.*'))
                        <div class="menu-item">
                            <a class="menu-link active" href="#">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Certificate Details</span>
                            </a>
                        </div>
                        @elseif(request()->routeIs('factories.show'))
                        <div class="menu-item">
                            <a class="menu-link active" href="#">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Factory Details</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Standards -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('standards.*') ? ' active' : '' }}" href="{{ route('standards.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-document fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Standards</span>
                    </a>
                </div>

                <!-- Renewal Stages -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('standard-stages.*') ? ' active' : '' }}" href="{{ route('standard-stages.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-arrows-circle fs-2 text-success">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Renewal Stages</span>
                    </a>
                </div>

                <!-- CPSC e-Filing -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('stafford-certs.*') ? ' active' : '' }}" href="{{ route('stafford-certs.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-shield-tick fs-2 text-info">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">CPSC e-Filing</span>
                    </a>
                </div>

                <!-- Test Report Types -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('test-report-types.*') ? ' active' : '' }}" href="{{ route('test-report-types.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-tablet-text-down fs-2 text-info">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Test Report Types</span>
                    </a>
                </div>

                <!-- RFID Generation -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('rfid-generation.*') ? ' active' : '' }}" href="{{ route('rfid-generation.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-barcode fs-2 text-primary">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span>
                            </i>
                        </span>
                        <span class="menu-title">RFID Generation</span>
                    </a>
                </div>

                <!-- Purchase Orders -->
                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('purchase-orders.*') ? ' active' : '' }}" href="{{ route('purchase-orders.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-basket fs-2" style="color: #10b981;">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Purchase Orders</span>
                    </a>
                </div>

                <!-- Shipment Management Accordion -->
                @php
                    $isShipmentActive = request()->routeIs('shipment-management.*');
                @endphp
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion{{ $isShipmentActive ? ' show here' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-delivery-3 fs-2 text-warning">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">Shipment Management</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('shipment-management.index') ? ' active' : '' }}" href="{{ route('shipment-management.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">All Shipments</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('shipment-management.create') ? ' active' : '' }}" href="{{ route('shipment-management.create') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Create Shipment</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('shipment-management.scan') ? ' active' : '' }}" href="{{ route('shipment-management.scan') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Shipment Scan</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link{{ request()->routeIs('shipment-management.forwarder-login') ? ' active' : '' }}" href="{{ route('shipment-management.forwarder-login') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Forwarder Login</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Section: FACTORY PORTAL (For factory role) -->
                @if(auth()->check() && (auth()->user()->hasRole('factory') || auth()->user()->isFactory()))
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-info">FACTORY PORTAL</span>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('factory-portal.*') ? ' active' : '' }}" href="{{ route('factory-portal.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-cube-2 fs-2 text-primary">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">My Allocations</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('purchase-orders.*') ? ' active' : '' }}" href="{{ route('purchase-orders.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-basket fs-2" style="color: #10b981;">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Purchase Orders</span>
                    </a>
                </div>
                @endif

                <!-- Section: LABORATORY PORTAL (For lab role) -->
                @if(auth()->check() && (auth()->user()->hasRole('lab') || auth()->user()->isLab()))
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-warning">LABORATORY PORTAL</span>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('lab.pos.*') ? ' active' : '' }}" href="{{ route('lab.pos.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-notepad fs-2 text-primary">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">All Details</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('lab.tasks.*') ? ' active' : '' }}" href="{{ route('lab.tasks.completed') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-check-circle fs-2 text-success">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Submitted Reports</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link{{ request()->routeIs('test-report-types.*') ? ' active' : '' }}" href="{{ route('test-report-types.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-tablet-text-down fs-2 text-info">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Test Report Types</span>
                    </a>
                </div>
                @endif

                <!-- Section: SYSTEM -->
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
