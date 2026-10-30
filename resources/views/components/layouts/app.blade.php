@props(['title' => ''])
<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <title>{{ $title ?: (View::hasSection('header') ? View::yieldContent('header') . ' - STAF PLM' : config('app.name', 'STAF PLM')) }}</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <link rel="shortcut icon" href="{{ asset('images/Opmalogo.png') }}" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="preload" href="{{ asset('plugins/global/fonts/keenicons/keenicons-duotone.woff') }}" as="font" type="font/woff" crossorigin="anonymous">
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle (Metronic)-->
    <link href="{{ asset('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Vendor Stylesheets (Merged from both projects)-->
    <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Bootstrap Icons & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Original DataTables & Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    
    <!-- Specific App CSS -->
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"/>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .bubble-card {
            border-radius: 1rem;
            transition: all 0.2s ease-in-out;
        }
    </style>

    {{-- Page-specific stylesheets pushed by child views --}}
    @stack('styles')
</head>
<!--end::Head-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">

    <!--begin::Theme mode setup-->
    <script>
        var defaultThemeMode = "light"; 
        var themeMode; 
        if (document.documentElement) { 
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) { 
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); 
            } else { 
                if (localStorage.getItem("data-bs-theme") !== null) { 
                    themeMode = localStorage.getItem("data-bs-theme"); 
                } else { 
                    themeMode = defaultThemeMode; 
                } 
            } 
            if (themeMode === "system") { 
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; 
            } 
            document.documentElement.setAttribute("data-bs-theme", themeMode); 
        }
    </script>
    <!--end::Theme mode setup-->

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
                <x-layouts.partials.navbar />
            </div>
            <!--end::Header-->

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
                        <a href="{{ url('/') }}" class="d-flex align-items-center gap-3 text-decoration-none">
                            <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width:36px; height:36px;">
                                <i class="fa-solid fa-shirt fs-5"></i>
                            </div>
                            <span class="fw-bold fs-4 text-white app-sidebar-logo-default tracking-wide">
                                STAF PLM
                            </span>
                        </a>
                        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
                            <i class="ki-duotone ki-black-left-line fs-3 rotate-180"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <x-layouts.partials.sidebar />
                </div>
                <!--end::Sidebar-->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid py-4">
                                {{ $slot ?? '' }}
                                @yield('content')
                            </div>
                        </div>
                    </div>

                    <!--begin::Footer-->
                    <x-layouts.partials.footer />
                    <!--end::Footer-->
                </div>
                <!--end::Main-->
            </div>
        </div>
    </div>

    <!-- Generic Confirm Modal (for CertTrack operations) -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bolder text-dark">Confirm Action</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;" id="confirmModalIconContainer">
                        <i class="bi bi-exclamation-triangle-fill fs-3" id="confirmModalIcon"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2" id="confirmModalMessage">Are you sure?</h6>
                    <p class="text-muted small mb-0" id="confirmModalSubmessage">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-center pt-0 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <form id="confirmForm" method="POST" class="d-inline m-0">
                        @csrf
                        <input type="hidden" name="_method" id="confirmMethod" value="POST">
                        <div id="confirmHiddenInputs"></div>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold" id="confirmSubmitBtn">Yes, Proceed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--begin::Javascript-->
    <!-- 1. jQuery (First) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- 2. Metronic Core -->
    <script src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/scripts.bundle.js') }}"></script>

    <!-- 3. DataTables & Export Tools -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- 4. App Logic Scripts -->
    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/production-received-notes.js') }}"></script>
    <script src="{{ asset('js/colors.js') }}"></script>
    <script src="{{ asset('js/sizes.js') }}"></script>
    <script src="{{ asset('js/design-positions.js') }}"></script>
    <script src="{{ asset('js/constructions.js') }}"></script>
    <script src="{{ asset('js/sample-types.js') }}"></script>
    <script src="{{ asset('js/seasons.js') }}"></script>
    <script src="{{ asset('js/tech-pack-types.js') }}"></script>
    <script src="{{ asset('js/artwork-types.js') }}"></script>
    <script src="{{ asset('js/pattern-file-types.js') }}"></script>
    <script src="{{ asset('js/file-types.js') }}"></script>

    <!-- 5. Inline Initialization Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            // Confirm Modal Handler
            var confirmModal = document.getElementById('confirmModal');
            if (confirmModal) {
                confirmModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var action = button.getAttribute('data-action');
                    var message = button.getAttribute('data-message') || 'Are you sure?';
                    var submessage = button.getAttribute('data-submessage') || 'This action cannot be undone.';
                    var method = button.getAttribute('data-method') || 'DELETE';
                    var btnText = button.getAttribute('data-btn-text') || 'Yes, Delete';
                    var btnClass = button.getAttribute('data-btn-class') || 'btn-danger';

                    var form = confirmModal.querySelector('#confirmForm');
                    form.action = action;
                    form.querySelector('#confirmMethod').value = method;

                    confirmModal.querySelector('#confirmModalMessage').textContent = message;
                    confirmModal.querySelector('#confirmModalSubmessage').textContent = submessage;

                    var submitBtn = confirmModal.querySelector('#confirmSubmitBtn');
                    submitBtn.textContent = btnText;
                    submitBtn.className = 'btn rounded-pill px-4 fw-bold ' + btnClass;

                    var hiddenInputsContainer = confirmModal.querySelector('#confirmHiddenInputs');
                    hiddenInputsContainer.innerHTML = '';
                    var inputsData = button.getAttribute('data-inputs');
                    if (inputsData) {
                        try {
                            var inputs = JSON.parse(inputsData);
                            for (var key in inputs) {
                                var input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = key;
                                input.value = inputs[key];
                                hiddenInputsContainer.appendChild(input);
                            }
                        } catch (e) {
                            console.error('Invalid JSON for data-inputs', e);
                        }
                    }
                });
            }
        });

        $(document).ready(function() {
            // Global Searchable Select2 Init
            if (typeof window.initSearchableSelects === 'function') {
                window.initSearchableSelects();
            }

            // AJAX Prefilter for Base URL
            const baseUrl = $('meta[name="base-url"]').attr('content');
            if (baseUrl) {
                $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
                    if (options.url.startsWith('/')) {
                        options.url = baseUrl + options.url;
                    }
                });
            }
        });
    </script>

    @stack('scripts')
    @yield('scripts')
    <!--end::Javascript-->
</body>
</html>
