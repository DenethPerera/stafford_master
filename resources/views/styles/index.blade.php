<x-layouts.app title="PLM Style Master">
    @push('styles')
    <style>
        /* High-Contrast Enterprise Header Typography */
        #styles_table thead th {
            color: #181C32 !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background-color: #F8F9FA !important;
            border-bottom: 2px solid #E4E6EF !important;
            padding-top: 0.85rem !important;
            padding-bottom: 0.85rem !important;
            vertical-align: middle !important;
        }

        /* Table Body Cell Alignment & Vertical Spacing */
        #styles_table tbody td {
            vertical-align: middle !important;
            padding-top: 0.85rem !important;
            padding-bottom: 0.85rem !important;
            color: #3F4254;
            font-size: 0.825rem;
        }

        /* Hover & Row Polish */
        #styles_table tbody tr {
            transition: background-color 0.15s ease-in-out;
        }
        #styles_table tbody tr:hover {
            background-color: #F9FAFB !important;
        }

        /* Progress Bar Proportions */
        .style-progress-bar {
            height: 6px;
            border-radius: 9999px;
        }
    </style>
    @endpush

    <div class="d-flex flex-column flex-column-fluid">
        <!-- Toolbar -->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Style Master
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">PLM</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-gray-900">Styles</li>
                    </ul>
                </div>

                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    @can('create-designs')
                    <a href="{{ route('styles.create') }}" class="btn btn-sm btn-primary fw-bold">
                        <i class="fa-solid fa-plus fs-7 me-1"></i> New Style Workspace
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Content -->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                
                <!-- Filter Card -->
                <div class="card mb-5 shadow-sm">
                    <div class="card-body py-4">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label fs-7 fw-bold text-gray-700">Client / Customer</label>
                                <select id="filter_client_id" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="All Clients">
                                    <option value="">All Clients</option>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-7 fw-bold text-gray-700">Season</label>
                                <select id="filter_season" class="form-select form-select-sm form-select-solid">
                                    <option value="">All Seasons</option>
                                    @foreach($seasons as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-7 fw-bold text-gray-700">Status</label>
                                <select id="filter_status" class="form-select form-select-sm form-select-solid">
                                    <option value="">All Statuses</option>
                                    <option value="DEVELOPMENT">DEVELOPMENT</option>
                                    <option value="FIT 2">FIT 2</option>
                                    <option value="APPROVED">APPROVED</option>
                                    <option value="ARCHIVED">ARCHIVED</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2 pt-6">
                                <button type="button" id="btn_filter" class="btn btn-sm btn-light-primary w-100 fw-bold">
                                    <i class="fa-solid fa-filter me-1"></i> Filter
                                </button>
                                <button type="button" id="btn_reset" class="btn btn-sm btn-light w-100 fw-bold">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="card card-flush shadow-sm">
                    <div class="card-body pt-4">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-7 gy-3 gs-4 w-100" id="styles_table">
                                <thead>
                                    <tr class="text-start bg-light text-gray-900 fw-bolder fs-8 text-uppercase border-bottom">
                                        <th class="ps-3 w-60px text-center">Image</th>
                                        <th class="w-120px">Style No</th>
                                        <th class="min-w-200px">Style Name / Description</th>
                                        <th class="w-140px">Customer</th>
                                        <th class="w-100px">Season</th>
                                        <th class="w-120px">Category</th>
                                        <th class="w-90px text-center">Revision</th>
                                        <th class="w-130px">Progress</th>
                                        <th class="w-120px text-center">Status</th>
                                        <th class="text-end pe-4 min-w-160px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-700"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#styles_table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('styles.index') }}",
                    data: function(d) {
                        d.client_id = $('#filter_client_id').val();
                        d.season_filter = $('#filter_season').val();
                        d.status_filter = $('#filter_status').val();
                    }
                },
                columns: [
                    { data: 'image_thumb', name: 'image_thumb', orderable: false, searchable: false, className: 'ps-3 text-center align-middle' },
                    { data: 'style_no', name: 'style_no', className: 'align-middle' },
                    { data: 'style_name', name: 'style_name', className: 'align-middle text-wrap min-w-200px' },
                    { data: 'client_name', name: 'client_name', className: 'align-middle text-truncate' },
                    { data: 'season', name: 'season', className: 'align-middle' },
                    { data: 'category', name: 'category', className: 'align-middle text-truncate' },
                    { data: 'revision_no', name: 'revision_no', className: 'align-middle text-center' },
                    { data: 'formatted_progress', name: 'overall_progress', className: 'align-middle' },
                    { data: 'status_badge', name: 'status', className: 'align-middle text-center' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'align-middle text-end pe-4' }
                ],
                order: [[1, 'desc']],
                drawCallback: function () {
                    // Re-initialize Metronic dropdown menu instances on newly drawn table rows
                    if (typeof KTMenu !== 'undefined') {
                        KTMenu.createInstances();
                        if (typeof KTMenu.init === 'function') {
                            KTMenu.init();
                        }
                    }

                    // Re-initialize Bootstrap tooltips if present
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        document.querySelectorAll('#styles_table [data-bs-toggle="tooltip"]').forEach(function (el) {
                            new bootstrap.Tooltip(el);
                        });
                    }
                },
                language: {
                    emptyTable: "No style records found in PLM database."
                }
            });

            // Event Delegation: Filter and Reset
            $('#btn_filter').click(function() { table.draw(); });
            $('#btn_reset').click(function() {
                $('#filter_client_id').val('').trigger('change');
                $('#filter_season').val('');
                $('#filter_status').val('');
                table.draw();
            });

            // Event Delegation: Direct Navigation for Action Buttons & Menu Links
            $(document).on('click', '#styles_table .btn-workspace, #styles_table .btn-style-action', function(e) {
                let targetUrl = $(this).attr('href');
                if (targetUrl && targetUrl !== '#' && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    e.preventDefault();
                    window.location.href = targetUrl;
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
