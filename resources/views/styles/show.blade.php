<x-layouts.app title="Style {{ $style->style_no }} - PLM Workspace">
    <div class="d-flex flex-column flex-column-fluid">
        <!-- Top Toolbar / Breadcrumbs -->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-4">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted"><a href="{{ route('styles.index') }}" class="text-muted text-hover-primary">PLM</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted"><a href="{{ route('styles.index') }}" class="text-muted text-hover-primary">Styles</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">{{ $style->style_no }}</li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-gray-900 fw-bold">Overview</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('styles.index') }}" class="btn btn-sm btn-light border border-gray-300 text-gray-700 fw-bold px-4">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Styles List
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Workspace Content Container -->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">

                <!-- 1. TOP HEADER & WORKFLOW CONTROL BAR -->
                <div class="card card-flush shadow-sm mb-6 border">
                    <div class="card-body p-6">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">
                            <!-- Left: Style Title & Status -->
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-1">
                                    <h1 class="fw-black text-gray-900 display-6 my-0">STYLE {{ $style->style_no }}</h1>
                                    <span class="badge badge-light-primary text-primary fw-bold fs-8 px-3 py-2 text-uppercase">{{ $style->status ?: 'DEVELOPMENT' }}</span>
                                    <span id="header_plm_no_badge" class="badge badge-light-info text-info fw-bold fs-8 px-3 py-2 {{ empty($style->plm_no) ? 'd-none' : '' }}">PLM: {{ $style->plm_no }}</span>
                                </div>
                                <div class="fw-bold text-gray-600 fs-6">{{ $style->style_name }}</div>
                            </div>

                            <!-- Right: Revision Dropdown & Actions -->
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-2 bg-light p-2 rounded border">
                                    <span class="text-muted fs-8 fw-bold">Revision</span>
                                    <select class="form-select form-select-sm w-120px form-select-solid fw-bold text-gray-800 border-0 bg-transparent py-1 no-select2">
                                        <option value="REV 03" selected>REV 03</option>
                                        <option value="REV 02">REV 02</option>
                                        <option value="REV 01">REV 01</option>
                                    </select>
                                    <span class="badge badge-light-success text-success fw-bold fs-9">Current</span>
                                </div>

                                <a href="{{ Route::has('styles.create-order') ? route('styles.create-order', $style->id) : '#' }}" class="btn btn-outline btn-outline-primary btn-active-light-primary btn-sm fw-bold" @if(!Route::has('styles.create-order')) onclick="alert('Order creation feature will be available once the Orders module is enabled.'); return false;" @endif>
                                    <i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Convert to Order
                                </a>

                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-gear me-1"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-item fs-7 fw-semibold" href="{{ route('styles.edit', $style->id) }}"><i class="fa-solid fa-pen text-warning me-2"></i> Edit Style</a></li>
                                        <li>
                                            <form action="{{ route('styles.duplicate', $style->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item fs-7 fw-semibold"><i class="fa-solid fa-copy text-primary me-2"></i> Duplicate Style</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item fs-7 fw-semibold text-danger" href="#" id="btn_archive_style"><i class="fa-solid fa-box-archive text-danger me-2"></i> Archive Style</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. MODULAR TAB NAVIGATION BAR -->
                <div class="card card-flush shadow-sm mb-6 border">
                    <div class="card-header border-bottom px-6 py-0">
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold" id="style_tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link text-active-primary py-4 active" data-bs-toggle="tab" href="#tab_overview">Overview</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_tech_pack">Tech Pack</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_artwork">Artwork</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_sample_pattern">Sample Pattern</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_bom">BOM</a></li>
                            <!-- <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_measurements">Measurements</a></li> -->
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_colors">Colors</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_samples">Samples</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_costing">Costing</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_dimension">Dimension</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_files">Files</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_approvals">Approvals</a></li>
                            <li class="nav-item"><a class="nav-link text-active-primary py-4" data-bs-toggle="tab" href="#tab_trim_submissions">Trim Submissions</a></li>
                        </ul>
                    </div>
                </div>

                <!-- 3. TAB CONTENTS -->
                <div class="tab-content" id="style_tab_content">
                    <div class="tab-pane fade show active" id="tab_overview">@include('styles.tabs.overview')</div>
                    <div class="tab-pane fade" id="tab_tech_pack">@include('styles.tabs.tech_pack')</div>
                    <div class="tab-pane fade" id="tab_artwork">@include('styles.tabs.artwork')</div>
                    <div class="tab-pane fade" id="tab_sample_pattern">@include('styles.tabs.sample_pattern')</div>
                    <div class="tab-pane fade" id="tab_bom">@include('styles.tabs.bom')</div>
                    <!-- <div class="tab-pane fade" id="tab_measurements">@include('styles.tabs.measurements')</div> -->
                    <div class="tab-pane fade" id="tab_colors">@include('styles.tabs.colors')</div>
                    <div class="tab-pane fade" id="tab_samples">@include('styles.tabs.samples')</div>
                    <div class="tab-pane fade" id="tab_costing">@include('styles.tabs.costing')</div>
                    <div class="tab-pane fade" id="tab_dimension">@include('styles.tabs.dimension')</div>
                    <div class="tab-pane fade" id="tab_files">@include('styles.tabs.files')</div>
                    <div class="tab-pane fade" id="tab_approvals">@include('styles.tabs.approvals')</div>
                    <div class="tab-pane fade" id="tab_trim_submissions">@include('styles.tabs.trim_submissions')</div>
                </div>

                <!-- 4. BOTTOM ACTION BAR -->
                <div class="card card-flush shadow-sm mt-6 border">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-4 gap-3">
                        <a href="{{ route('styles.index') }}" class="btn btn-sm btn-light border border-gray-300 fw-bold text-gray-700">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Styles List
                        </a>

                        <div class="d-flex flex-wrap gap-3">
                            <form action="{{ route('styles.duplicate', $style->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light border fw-bold text-gray-800">
                                    <i class="fa-solid fa-copy me-1"></i> Duplicate Style
                                </button>
                            </form>

                            <button type="button" class="btn btn-sm btn-outline btn-outline-danger btn-active-light-danger fw-bold" id="btn_archive_style_bottom">
                                <i class="fa-solid fa-box-archive me-1"></i> Archive Style
                            </button>

                            <a href="{{ Route::has('styles.create-order') ? route('styles.create-order', $style->id) : '#' }}" class="btn btn-sm btn-outline btn-outline-primary btn-active-light-primary fw-bold" @if(!Route::has('styles.create-order')) onclick="alert('Order creation feature will be available once the Orders module is enabled.'); return false;" @endif>
                                <i class="fa-solid fa-cart-plus me-1"></i> Create Order
                            </a>

                            <a href="{{ route('styles.edit', $style->id) }}" class="btn btn-sm btn-primary px-6 fw-bold">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Update Style Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 5. WORKSPACE MODALS PARTIAL -->
                @include('styles.partials.modals')

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Restore active tab from URL hash if available
            var hash = window.location.hash;
            if (hash) {
                var tabTrigger = $('#style_tabs a[href="' + hash + '"]');
                if (tabTrigger.length) {
                    tabTrigger.tab('show');
                }
            }

            // Save active tab in hash on click
            $('#style_tabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });

            // Archive style confirmation
            $('#btn_archive_style, #btn_archive_style_bottom').click(function(e) {
                e.preventDefault();
                if (confirm("Are you sure you want to archive Style {{ $style->style_no }}?")) {
                    $.post("{{ route('styles.archive', $style->id) }}", { _token: "{{ csrf_token() }}" }, function(res) {
                        if (res.success) {
                            window.location.href = "{{ route('styles.index') }}";
                        }
                    });
                }
            });

            // Helper function for AJAX form submits
            function handleAjaxForm(formId, modalId) {
                $(formId).on('submit', function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var data = new FormData(this);

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            if (res.success) {
                                $(modalId).modal('hide');
                                if (modalId === '#addSampleModal') {
                                    window.location.hash = '#tab_samples';
                                }
                                if (modalId === '#addArtworkModal') {
                                    window.location.hash = '#tab_artwork';
                                }
                                if (modalId === '#uploadFileModal') {
                                    window.location.hash = '#tab_files';
                                }
                                if (modalId === '#addTrimSubmissionModal' || modalId === '#editTrimSubmissionModal') {
                                    window.location.hash = '#tab_trim_submissions';
                                }
                                if (modalId === '#addCartonDimensionModal' || modalId === '#uploadCartonDimensionModal') {
                                    window.location.hash = '#tab_dimension';
                                }
                                window.location.reload();
                            } else {
                                alert(res.message || 'An error occurred.');
                            }
                        },
                        error: function(xhr) {
                            var err = xhr.responseJSON ? xhr.responseJSON.message : 'Operation failed.';
                            alert(err);
                        }
                    });
                });
            }

            handleAjaxForm('#form_save_bom', '#addBomItemModal');
            handleAjaxForm('#form_save_measurement', '#addMeasurementModal');
            handleAjaxForm('#form_save_colorway', '#addColorwayModal');
            handleAjaxForm('#form_save_sample', '#addSampleModal');
            handleAjaxForm('#form_edit_tech_pack', '#editTechPackModal');
            handleAjaxForm('#form_save_artwork', '#addArtworkModal');
            handleAjaxForm('#form_edit_costing', '#editCostingModal');
            handleAjaxForm('#form_upload_file', '#uploadFileModal');
            handleAjaxForm('#form_save_approval', '#addApprovalModal');
            handleAjaxForm('#form_upload_tech_pack_document', '#uploadTechPackDocumentModal');
            handleAjaxForm('#form_save_trim_submission', '#addTrimSubmissionModal');
            handleAjaxForm('#form_update_trim_submission', '#editTrimSubmissionModal');
            handleAjaxForm('#form_save_dimension', '#addCartonDimensionModal');
            handleAjaxForm('#form_upload_dimension_csv', '#uploadCartonDimensionModal');

            // Delete Tech Pack Document by Type
            $(document).on('click', '.btn-delete-techpack-doc', function(e) {
                e.preventDefault();
                var docId = $(this).data('doc-id');
                var docName = $(this).data('doc-name') || 'this document';
                var url = '{{ route("styles.tech-pack-documents.destroy", ":id") }}'.replace(':id', docId);

                var executeDelete = function() {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: res.message || 'Document deleted successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(function() {
                                    window.location.reload();
                                });
                            } else {
                                window.location.reload();
                            }
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to delete document.';
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Error', msg, 'error');
                            } else {
                                alert(msg);
                            }
                        }
                    });
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete Document?',
                        text: 'Are you sure you want to delete "' + docName + '"?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            executeDelete();
                        }
                    });
                } else {
                    if (confirm('Are you sure you want to delete "' + docName + '"?')) {
                        executeDelete();
                    }
                }
            });

            // Direct Tech Pack PDF Upload / Replace
            $(document).on('submit', '.form-upload-techpack-pdf, #directTechPackPdfUploadForm', function(e) {
                e.preventDefault();
                var form = $(this);
                var btn = form.find('button[type="submit"]');
                var originalHtml = btn.html();
                var fileInput = form.find('input[type="file"]')[0];

                if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('No File Selected', 'Please select a PDF file to upload.', 'warning');
                    } else {
                        alert('Please select a PDF file to upload.');
                    }
                    return;
                }

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Uploading...');

                var formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PUT');
                formData.append('tech_pack_pdf', fileInput.files[0]);

                $.ajax({
                    url: '{{ route("styles.tech-pack.update", $style->id) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        btn.prop('disabled', false).html(originalHtml);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Uploaded!',
                                text: res.message || 'Tech Pack PDF uploaded successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            window.location.reload();
                        }
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(originalHtml);
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to upload PDF.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Upload Failed', msg, 'error');
                        } else {
                            alert(msg);
                        }
                    }
                });
            });

            // Toggle Replace PDF Form
            $(document).on('click', '#btnToggleReplacePdf', function() {
                $('#replacePdfFormWrapper').toggleClass('d-none');
            });
            $(document).on('click', '#btnCancelReplacePdf', function() {
                $('#replacePdfFormWrapper').addClass('d-none');
            });

            // Delete Tech Pack PDF
            $(document).on('click', '.btn-delete-techpack-pdf', function() {
                var styleId = $(this).data('style-id');
                var btn = $(this);

                var executeDelete = function() {
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Deleting...');
                    $.ajax({
                        url: '/styles/' + styleId + '/tech-pack-pdf',
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: res.message || 'Tech Pack PDF deleted.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(function() {
                                    window.location.reload();
                                });
                            } else {
                                window.location.reload();
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can me-1"></i> Delete');
                            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to delete PDF.';
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Error', msg, 'error');
                            } else {
                                alert(msg);
                            }
                        }
                    });
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete Tech Pack PDF?',
                        text: 'This will permanently remove the uploaded PDF file.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            executeDelete();
                        }
                    });
                } else {
                    if (confirm('Are you sure you want to delete the tech pack PDF?')) {
                        executeDelete();
                    }
                }
            });

            // 1. Edit BOM Item
            $(document).on('click', '.btn-edit-bom', function() {
                var item = $(this).data('item');
                $('#bom_modal_title').text('Edit BOM Component');
                $('#form_save_bom').attr('action', '/styles/bom/' + item.id);
                $('#bom_form_method').val('PUT');
                $('#bom_component_type').val(item.component_type);
                $('#bom_item_description').val(item.item_description);
                $('#bom_supplier').val(item.supplier);
                $('#bom_placement').val(item.placement);
                $('#bom_color').val(item.color);
                $('#bom_qty_per_size').val(item.qty_per_size);
                $('#bom_uom').val(item.uom);
                $('#bom_unit_cost').val(item.unit_cost);
                $('#addBomItemModal').modal('show');
            });

            // Delete BOM Item
            $(document).on('click', '.btn-delete-bom', function() {
                var id = $(this).data('id');
                if (confirm('Delete this BOM component?')) {
                    $.ajax({
                        url: '/styles/bom/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) { window.location.reload(); }
                    });
                }
            });

            // 2. Open / Edit Measurement Modal
            $(document).on('click', '[data-bs-target="#addMeasurementModal"]', function() {
                if ($(this).hasClass('btn-edit-measurement')) return;
                $('#measurement_modal_title').text('Add Measurement Specification');
                $('#form_save_measurement').attr('action', '{{ route("styles.measurements.store", $style->id) }}');
                $('#measurement_form_method').val('POST');
                $('#m_point').val('');
                $('#m_spec_value').val('0.0');
                $('#m_tol_minus').val('0.5');
                $('#m_tol_plus').val('0.5');
                $('#m_uom').val('Inch');
                var szList = ['xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', '4xl', '5xl', '6xl'];
                szList.forEach(function(sz) {
                    $('#m_' + sz).val('');
                });
            });

            $(document).on('click', '.btn-edit-measurement', function() {
                var m = $(this).data('measurement');
                $('#measurement_modal_title').text('Edit Measurement Spec');
                $('#form_save_measurement').attr('action', '/styles/measurements/' + m.id);
                $('#measurement_form_method').val('PUT');
                $('#m_point').val(m.measurement_point);
                $('#m_spec_value').val(m.spec_value);
                $('#m_tol_minus').val(m.tol_minus);
                $('#m_tol_plus').val(m.tol_plus);
                $('#m_uom').val(m.uom);
                
                var szList = ['xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', '4xl', '5xl', '6xl'];
                szList.forEach(function(sz) {
                    var val = m[sz];
                    $('#m_' + sz).val(val !== null && val !== undefined ? val : '');
                });
                
                $('#addMeasurementModal').modal('show');
            });

            // Delete Measurement
            $(document).on('click', '.btn-delete-measurement', function() {
                var id = $(this).data('id');
                if (confirm('Delete this measurement point?')) {
                    $.ajax({
                        url: '/styles/measurements/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) { window.location.reload(); }
                    });
                }
            });

            // 3. COLORWAY MANAGEMENT & PANTONE AUTO-LOOKUP
            function updateColorwayPreview() {
                var hex = $('#c_hex').val() || '#1e293b';
                var name = $('#c_color_name').val() || 'Color Name';
                var pantone = $('#c_pantone').val() || 'No Pantone Code';
                var labRef = $('#c_lab_ref').val() || '—';
                var status = $('#c_status').val() || 'Approved';

                // Normalize Hex format
                if (hex.charAt(0) !== '#') {
                    hex = '#' + hex;
                }

                $('#c_preview_box').css('background-color', hex);
                $('#c_preview_hex_tag').text(hex.toUpperCase());
                $('#c_preview_name').text(name);
                $('#c_preview_pantone').text(pantone);
                $('#c_preview_lab').text(labRef);

                var badgeClass = 'badge-light-success text-success';
                if (status.toLowerCase() === 'pending') badgeClass = 'badge-light-warning text-warning';
                if (status.toLowerCase() === 'rejected') badgeClass = 'badge-light-danger text-danger';

                $('#c_preview_status_badge').attr('class', 'badge ' + badgeClass + ' fs-9 fw-bold px-2 py-1').text(status);
            }

            // Sync Color Picker & Hex Text Field
            $(document).on('input change', '#c_hex_picker', function() {
                $('#c_hex').val($(this).val());
                updateColorwayPreview();
            });

            $(document).on('input change', '#c_hex', function() {
                var hex = $(this).val();
                if (/^#[0-9A-F]{6}$/i.test(hex) || /^#[0-9A-F]{3}$/i.test(hex)) {
                    $('#c_hex_picker').val(hex);
                }
                updateColorwayPreview();
            });

            $(document).on('input change', '#c_color_name, #c_lab_ref, #c_status', function() {
                updateColorwayPreview();
            });

            // Pantone Code Auto-Lookup via AJAX
            function fetchPantoneDetails(code) {
                if (!code || code.trim().length === 0) return;

                $('#pantone_status_msg').html('<i class="fa-solid fa-spinner fa-spin text-primary me-1"></i> Searching Pantone library...');

                $.ajax({
                    url: '{{ route("styles.pantone.lookup") }}',
                    type: 'GET',
                    data: { code: code },
                    success: function(res) {
                        if (res.success && res.data) {
                            var info = res.data;
                            $('#c_color_name').val(info.name);
                            $('#c_hex').val(info.hex);
                            $('#c_hex_picker').val(info.hex);
                            $('#c_pantone').val(info.code);

                            if (info.found) {
                                $('#pantone_status_msg').html(
                                    '<span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Library Match: ' + 
                                    info.name + ' <span class="font-monospace">(' + info.hex.toUpperCase() + ' • ' + info.rgb + ')</span></span>'
                                );
                            } else {
                                $('#pantone_status_msg').html(
                                    '<span class="text-primary fw-bold"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> Calculated Shade Match: ' + 
                                    info.name + ' <span class="font-monospace">(' + info.hex.toUpperCase() + ' • ' + info.rgb + ')</span></span>'
                                );
                            }
                            updateColorwayPreview();
                        }
                    },
                    error: function() {
                        $('#pantone_status_msg').html(
                            '<span class="text-muted"><i class="fa-solid fa-palette me-1"></i> Custom Pantone code entered. Manual color tuning enabled.</span>'
                        );
                    }
                });
            }

            $(document).on('click', '#btn_lookup_pantone', function() {
                fetchPantoneDetails($('#c_pantone').val());
            });

            $(document).on('change', '#c_pantone', function() {
                fetchPantoneDetails($(this).val());
            });

            // Open "Add Colorway" Modal Reset
            $(document).on('click', '#btn_open_add_colorway', function() {
                $('#colorway_modal_title').text('Add Colorway');
                $('#form_save_colorway').attr('action', '{{ route("styles.colorways.store", $style->id) }}');
                $('#colorway_form_method').val('POST');
                $('#c_color_name').val('');
                $('#c_pantone').val('');
                $('#c_hex').val('#0f4c81');
                $('#c_hex_picker').val('#0f4c81');
                $('#c_lab_ref').val('');
                $('#c_status').val('Approved');
                $('#pantone_status_msg').html('Type standard Pantone TCX/TPG or PMS numbers to auto-detect swatch hex & color name.');
                updateColorwayPreview();
            });

            // Edit Colorway Modal Populate
            $(document).on('click', '.btn-edit-colorway', function() {
                var c = $(this).data('colorway');
                $('#colorway_modal_title').text('Edit Colorway');
                $('#form_save_colorway').attr('action', '/styles/colorways/' + c.id);
                $('#colorway_form_method').val('PUT');
                $('#c_color_name').val(c.color_name);
                $('#c_pantone').val(c.pantone_code || '');
                $('#c_hex').val(c.swatch_color_hex || '#0f4c81');
                $('#c_hex_picker').val(c.swatch_color_hex || '#0f4c81');
                $('#c_lab_ref').val(c.lab_op_ref || '');
                $('#c_status').val(c.status || 'Approved');
                $('#pantone_status_msg').html('Editing existing colorway swatch.');
                updateColorwayPreview();
                $('#addColorwayModal').modal('show');
            });

            // Delete Colorway
            $(document).on('click', '.btn-delete-colorway', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this colorway swatch?')) {
                    $.ajax({
                        url: '/styles/colorways/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) { window.location.reload(); }
                    });
                }
            });

            // 4. Edit Sample
            $(document).on('click', '.btn-edit-sample', function() {
                var s = $(this).data('sample');
                $('#sample_modal_title').text('Edit Sample Record');
                $('#form_save_sample').attr('action', '/styles/samples/' + s.id);
                $('#sample_form_method').val('PUT');
                $('#s_sample_type_id').val(s.sample_type_id || '');
                $('#s_season').val(s.season || '');
                $('#s_name').val(s.sample_name);
                $('#s_code').val(s.sample_code);
                $('#s_tag_no').val(s.tag_no || '');
                $('#s_sent_date').val(s.sent_date ? s.sent_date.substring(0, 10) : '');
                $('#s_waybill_no').val(s.waybill_no || '');
                $('#s_status').val(s.status);
                $('#s_submitted_by').val(s.submitted_by);
                $('#s_approval_date').val(s.approval_date ? s.approval_date.substring(0, 10) : '');
                $('#s_remarks').val(s.remarks);
                $('#s_qc_report').val('');
                if (s.qc_report) {
                    $('#s_qc_report_name').text(s.qc_report);
                    $('#s_qc_report_view_btn').attr('href', "{{ asset('uploads/styles/samples/qc') }}/" + s.qc_report);
                    $('#s_qc_report_preview_box').removeClass('d-none');
                } else {
                    $('#s_qc_report_preview_box').addClass('d-none');
                }

                $('#s_cost_sheet').val('');
                if (s.cost_sheet) {
                    $('#s_cost_sheet_name').text(s.cost_sheet);
                    $('#s_cost_sheet_view_btn').attr('href', "{{ asset('uploads/styles/samples/cost') }}/" + s.cost_sheet);
                    $('#s_cost_sheet_preview_box').removeClass('d-none');
                } else {
                    $('#s_cost_sheet_preview_box').addClass('d-none');
                }

                $('#s_comment_sheet').val('');
                if (s.comment_sheet) {
                    $('#s_comment_sheet_name').text(s.comment_sheet);
                    $('#s_comment_sheet_view_btn').attr('href', "{{ asset('uploads/styles/samples/comments') }}/" + s.comment_sheet);
                    $('#s_comment_sheet_preview_box').removeClass('d-none');
                } else {
                    $('#s_comment_sheet_preview_box').addClass('d-none');
                }

                $('#addSampleModal').modal('show');
            });

            // View Sample Details Modal
            var currentViewedSampleId = null;
            function formatSampleDate(dateStr) {
                if (!dateStr) return '—';
                var clean = dateStr.substring(0, 10);
                var parts = clean.split('-');
                if (parts.length === 3) {
                    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    var mIndex = parseInt(parts[1], 10) - 1;
                    return parts[2] + ' ' + (months[mIndex] || parts[1]) + ' ' + parts[0];
                }
                return dateStr;
            }

            $(document).on('click', '.btn-view-sample', function() {
                var s = $(this).data('sample');
                var typeName = $(this).data('sample-type-name');
                var typeCode = $(this).data('sample-type-code');

                currentViewedSampleId = s.id;

                $('#v_sample_title').text(s.sample_name || 'Sample Details');
                $('#v_sample_subtitle').text((typeName ? typeName + ' • ' : '') + (s.sample_code || 'No code'));

                var st = (s.status || 'Pending').toUpperCase();
                var badgeClass = 'badge-light-primary text-primary';
                if (st === 'APPROVED') badgeClass = 'badge-light-success text-success';
                else if (st === 'REVISED') badgeClass = 'badge-light-warning text-warning';
                else if (st === 'REJECTED') badgeClass = 'badge-light-danger text-danger';

                $('#v_status_badge')
                    .removeClass()
                    .addClass('badge fs-8 fw-bold px-3 py-2 ' + badgeClass)
                    .text(s.status || 'Pending');

                $('#v_sample_name').text(s.sample_name || '—');
                $('#v_sample_type').text(typeName ? (typeName + (typeCode ? ' (' + typeCode + ')' : '')) : '—');
                $('#v_sample_code').text(s.sample_code || '—');
                $('#v_tag_no').text(s.tag_no || '—');
                $('#v_season').text(s.season || '—');

                $('#v_sent_date').text(formatSampleDate(s.sent_date));
                $('#v_approval_date').text(formatSampleDate(s.approval_date));
                $('#v_waybill_no').text(s.waybill_no || '—');
                $('#v_submitted_by').text(s.submitted_by || '—');

                // QC Report card
                if (s.qc_report) {
                    var qcUrl = "{{ asset('uploads/styles/samples/qc') }}/" + s.qc_report;
                    $('#v_qc_filename').text(s.qc_report);
                    $('#v_qc_view_btn').attr('href', qcUrl);
                    $('#v_qc_download_btn').attr('href', qcUrl);
                    $('#v_qc_actions').removeClass('d-none');
                    $('#v_qc_empty').addClass('d-none');
                } else {
                    $('#v_qc_filename').text('None');
                    $('#v_qc_actions').addClass('d-none');
                    $('#v_qc_empty').removeClass('d-none');
                }

                // Cost Sheet card
                if (s.cost_sheet) {
                    var costUrl = "{{ asset('uploads/styles/samples/cost') }}/" + s.cost_sheet;
                    $('#v_cost_filename').text(s.cost_sheet);
                    $('#v_cost_view_btn').attr('href', costUrl);
                    $('#v_cost_download_btn').attr('href', costUrl);
                    $('#v_cost_actions').removeClass('d-none');
                    $('#v_cost_empty').addClass('d-none');
                } else {
                    $('#v_cost_filename').text('None');
                    $('#v_cost_actions').addClass('d-none');
                    $('#v_cost_empty').removeClass('d-none');
                }

                // Comment Sheet card
                if (s.comment_sheet) {
                    var commentUrl = "{{ asset('uploads/styles/samples/comments') }}/" + s.comment_sheet;
                    $('#v_comment_filename').text(s.comment_sheet);
                    $('#v_comment_view_btn').attr('href', commentUrl);
                    $('#v_comment_download_btn').attr('href', commentUrl);
                    $('#v_comment_actions').removeClass('d-none');
                    $('#v_comment_empty').addClass('d-none');
                } else {
                    $('#v_comment_filename').text('None');
                    $('#v_comment_actions').addClass('d-none');
                    $('#v_comment_empty').removeClass('d-none');
                }

                $('#v_remarks').text(s.remarks || 'No remarks recorded.');
                $('#viewSampleDetailsModal').modal('show');
            });

            // Edit button from inside view details modal
            $('#v_btn_trigger_edit').on('click', function() {
                $('#viewSampleDetailsModal').modal('hide');
                if (currentViewedSampleId) {
                    $('.btn-edit-sample').filter(function() {
                        var data = $(this).data('sample');
                        return data && data.id === currentViewedSampleId;
                    }).trigger('click');
                }
            });

            // Auto-populate Sample Name & Code when Sample Type is selected in modal
            $('#s_sample_type_id').on('change', function() {
                var opt = $(this).find('option:selected');
                var name = opt.data('name');
                var code = opt.data('code');
                if (name && !$('#s_name').val()) {
                    $('#s_name').val(name);
                }
                if (code && !$('#s_code').val()) {
                    $('#s_code').val(code);
                }
            });

            // Auto-populate approval date to today when status changed to Approved in modal
            $('#s_status').on('change', function() {
                if ($(this).val() === 'Approved' && !$('#s_approval_date').val()) {
                    var today = new Date().toISOString().split('T')[0];
                    $('#s_approval_date').val(today);
                }
            });

            // Reset modal when clicking New Sample Record
            $(document).on('click', '[data-bs-target="#addSampleModal"]', function() {
                $('#sample_modal_title').text('Add Sample Record');
                $('#form_save_sample').attr('action', '{{ route("styles.samples.store", $style->id) }}');
                $('#sample_form_method').val('POST');
                $('#form_save_sample')[0].reset();
                $('#s_sample_type_id').val('');
                $('#s_season').val('');
                $('#s_tag_no').val('');
                $('#s_waybill_no').val('');
                $('#s_qc_report').val('');
                $('#s_qc_report_preview_box').addClass('d-none');
                $('#s_cost_sheet').val('');
                $('#s_cost_sheet_preview_box').addClass('d-none');
                $('#s_comment_sheet').val('');
                $('#s_comment_sheet_preview_box').addClass('d-none');
            });

            // Quick Change Sample Season directly from samples table list
            $(document).on('change', '.select-change-sample-season', function() {
                var select = $(this);
                var sampleId = select.data('sample-id');
                var season = select.val();
                var originalVal = select.data('current-val') || '';

                select.prop('disabled', true);
                $.ajax({
                    url: '/styles/samples/' + sampleId + '/change-season',
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        season: season || null
                    },
                    success: function(res) {
                        select.prop('disabled', false);
                        select.data('current-val', season);

                        var row = select.closest('tr');
                        var editBtn = row.find('.btn-edit-sample');
                        if (editBtn.length) {
                            var sObj = editBtn.data('sample');
                            if (sObj) {
                                sObj.season = season;
                                editBtn.data('sample', sObj);
                            }
                        }
                        var viewBtn = row.find('.btn-view-sample');
                        if (viewBtn.length) {
                            var svObj = viewBtn.data('sample');
                            if (svObj) {
                                svObj.season = season;
                                viewBtn.data('sample', svObj);
                            }
                        }

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Season Updated!',
                                text: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        select.prop('disabled', false);
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to update sample season.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', msg, 'error');
                        } else {
                            alert(msg);
                        }
                        select.val(originalVal);
                    }
                });
            });

            // Quick Change Sample Type directly from samples table list
            $(document).on('change', '.select-change-sample-type', function() {
                var select = $(this);
                var sampleId = select.data('sample-id');
                var sampleTypeId = select.val();
                var originalVal = select.data('current-val') || '';

                select.prop('disabled', true);
                $.ajax({
                    url: '/styles/samples/' + sampleId + '/change-type',
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        sample_type_id: sampleTypeId || null
                    },
                    success: function(res) {
                        select.prop('disabled', false);
                        select.data('current-val', sampleTypeId);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        select.prop('disabled', false);
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to change sample type.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', msg, 'error');
                        } else {
                            alert(msg);
                        }
                        select.val(originalVal);
                    }
                });
            });

            // Quick Change Sample Status directly from samples table list
            $(document).on('change', '.select-change-sample-status', function() {
                var select = $(this);
                var sampleId = select.data('sample-id');
                var status = select.val();
                var originalVal = select.data('current-val') || '';

                select.prop('disabled', true);
                $.ajax({
                    url: '/styles/samples/' + sampleId + '/change-status',
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(res) {
                        select.data('current-val', status);

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated!',
                                text: res.message || 'Sample status updated.',
                                timer: 800,
                                showConfirmButton: false
                            });
                        }

                        // Refresh the page so the table updates with the new status, approval date, and timeline
                        setTimeout(function() {
                            window.location.hash = '#tab_samples';
                            window.location.reload();
                        }, 850);
                    },
                    error: function(xhr) {
                        select.prop('disabled', false);
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to update sample status.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', msg, 'error');
                        } else {
                            alert(msg);
                        }
                        select.val(originalVal);
                    }
                });
            });

            // Delete Sample
            $(document).on('click', '.btn-delete-sample', function() {
                var id = $(this).data('id');
                if (confirm('Delete this sample record?')) {
                    $.ajax({
                        url: '/styles/samples/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) { window.location.reload(); }
                    });
                }
            });

            // 5. Add Artwork Modal
            $('[data-bs-target="#addArtworkModal"]').on('click', function() {
                $('#artwork_modal_title').text('Add Artwork Record');
                $('#form_save_artwork').attr('action', "{{ route('styles.artworks.store', $style->id) }}");
                $('#artwork_form_method').val('POST');
                $('#form_save_artwork')[0].reset();
                $('#art_artwork_type_id').val('');
                $('#art_type').val('');
                $('#art_file').val('');
                $('#art_file_preview_box').addClass('d-none');
            });

            // Sync hidden artwork_type_id on select change
            $('#art_type').on('change', function() {
                var selectedId = $(this).find('option:selected').data('id') || '';
                $('#art_artwork_type_id').val(selectedId);
            });

            // Edit Artwork
            $(document).on('click', '.btn-edit-artwork', function() {
                var art = $(this).data('artwork');
                $('#artwork_modal_title').text('Edit Artwork Record');
                $('#form_save_artwork').attr('action', '/styles/artworks/' + art.id);
                $('#artwork_form_method').val('PUT');
                $('#art_name').val(art.artwork_name);
                $('#art_code').val(art.artwork_code);
                $('#art_type').val(art.type);
                $('#art_artwork_type_id').val(art.artwork_type_id || '');
                $('#art_placement').val(art.placement);
                $('#art_dimensions').val(art.dimensions);
                $('#art_file').val('');

                if (art.artwork_file) {
                    $('#art_file_preview_box').removeClass('d-none');
                    $('#art_current_file_name').text(art.artwork_file);
                    $('#art_file_download_btn').attr('href', '/styles/artworks/' + art.id + '/download');
                } else {
                    $('#art_file_preview_box').addClass('d-none');
                }

                $('#addArtworkModal').modal('show');
            });

            // Delete Artwork
            $(document).on('click', '.btn-delete-artwork', function() {
                var id = $(this).data('id');
                if (confirm('Delete this artwork?')) {
                    $.ajax({
                        url: '/styles/artworks/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) { 
                            window.location.hash = '#tab_artwork';
                            window.location.reload(); 
                        }
                    });
                }
            });

            // 6. Delete File
            $(document).on('click', '.btn-delete-file', function() {
                var id = $(this).data('id');
                if (confirm('Delete this file?')) {
                    $.ajax({
                        url: '/styles/files/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) { 
                            window.location.hash = '#tab_files';
                            window.location.reload(); 
                        }
                    });
                }
            });

            // 7. Edit Approval Gate
            $(document).on('click', '.btn-edit-approval', function() {
                var app = $(this).data('approval');
                $('#approval_modal_title').text('Edit Approval Sign-off');
                $('#form_save_approval').attr('action', '/styles/approvals/' + app.id);
                $('#approval_form_method').val('PUT');
                $('#app_gate_name').val(app.gate_name);
                $('#app_status').val(app.status);
                $('#app_approved_by').val(app.approved_by);
                $('#app_remarks').val(app.remarks);
                $('#addApprovalModal').modal('show');
            });

            // Delete Approval Gate
            $(document).on('click', '.btn-delete-approval', function() {
                var id = $(this).data('id');
                if (confirm('Delete this approval gate?')) {
                    $.ajax({
                        url: '/styles/approvals/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) { window.location.reload(); }
                    });
                }
            });

            // 7.1 Edit Trim Submission
            $(document).on('click', '.btn-edit-trim-submission', function() {
                var trim = $(this).data('trim');
                var updateUrl = $(this).data('update-url');
                $('#form_update_trim_submission').attr('action', updateUrl);
                $('#edit_trim_type').val(trim.trim_type);
                $('#edit_trim_item_name').val(trim.item_name);
                
                if (trim.supplier) {
                    if ($('#edit_trim_supplier').find("option[value='" + trim.supplier.replace(/'/g, "\\'") + "']").length === 0) {
                        $('#edit_trim_supplier').append(new Option(trim.supplier, trim.supplier, true, true));
                    }
                    $('#edit_trim_supplier').val(trim.supplier).trigger('change');
                } else {
                    $('#edit_trim_supplier').val('').trigger('change');
                }

                $('#edit_trim_placement').val(trim.placement || '');
                $('#edit_trim_color').val(trim.color || '');
                $('#edit_trim_submission_stage').val(trim.submission_stage || '1st Submission');
                $('#edit_trim_sent_date').val(trim.sent_date ? trim.sent_date.substring(0, 10) : '');
                $('#edit_trim_status').val(trim.status || 'PENDING');
                $('#edit_trim_approved_by').val(trim.approved_by || '');
                $('#edit_trim_approval_date').val(trim.approval_date ? trim.approval_date.substring(0, 10) : '');
                $('#edit_trim_remarks').val(trim.remarks || '');
                if (trim.attachment) {
                    $('#edit_trim_current_attachment').html('Current attachment: <a href="/uploads/styles/trims/' + trim.attachment + '" target="_blank" class="fw-bold text-primary">' + trim.attachment + '</a>');
                } else {
                    $('#edit_trim_current_attachment').html('');
                }
                $('#editTrimSubmissionModal').modal('show');
            });

            $('#addTrimSubmissionModal').on('show.bs.modal', function() {
                $('#add_trim_supplier').val('').trigger('change');
            });

            // Delete Trim Submission
            $(document).on('click', '.btn-delete-trim-submission', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name') || 'this trim submission';
                var url = $(this).data('delete-url');

                var doDelete = function() {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            window.location.hash = '#tab_trim_submissions';
                            window.location.reload();
                        },
                        error: function(xhr) {
                            alert(xhr.responseJSON?.message || 'Failed to delete trim submission.');
                        }
                    });
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete Trim Submission?',
                        text: 'Are you sure you want to delete "' + name + '"?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            doDelete();
                        }
                    });
                } else {
                    if (confirm('Are you sure you want to delete "' + name + '"?')) {
                        doDelete();
                    }
                }
            });

            // ==========================================
            // 8. SAMPLE PATTERN TAB EVENT HANDLERS
            // ==========================================

            // Open Upload / Replace Pattern Modal
            $(document).on('click', '.btn-open-upload-pattern', function() {
                var sampleId = $(this).data('sample-id');
                var sampleName = $(this).data('sample-name') || 'Sample Record';
                var patternTypeId = $(this).data('pattern-type-id') || '';

                $('#modal_pattern_sample_name').text(sampleName);
                $('#modal_pattern_tag_badge').text('Sample #' + sampleId);
                $('#modal_pattern_file_type_id').val(patternTypeId);
                $('#modal_pattern_file_input').val('');
                $('#modal_pattern_notes').val('');
                $('#form_upload_sample_pattern').attr('action', '/styles/samples/' + sampleId + '/pattern/upload');

                $('#uploadSamplePatternModal').modal('show');
            });

            // Submit Upload Sample Pattern Form
            $('#form_upload_sample_pattern').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var formData = new FormData(this);
                var submitBtn = $('#btn_submit_sample_pattern');

                submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Uploading...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        submitBtn.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Pattern');
                        if (res.success) {
                            $('#uploadSamplePatternModal').modal('hide');
                            window.location.hash = '#tab_sample_pattern';
                            window.location.reload();
                        } else {
                            alert(res.message || 'Error uploading pattern file.');
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Pattern');
                        var msg = xhr.responseJSON ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Failed to upload pattern file.';
                        alert(msg);
                    }
                });
            });

            // Quick Change Pattern File Type directly from table
            $(document).on('change', '.select-change-pattern-type', function() {
                var select = $(this);
                var sampleId = select.data('sample-id');
                var patternTypeId = select.val();
                var originalVal = select.data('current-val') || '';

                select.prop('disabled', true);
                $.ajax({
                    url: '/styles/samples/' + sampleId + '/change-pattern-type',
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        pattern_file_type_id: patternTypeId || null
                    },
                    success: function(res) {
                        select.prop('disabled', false);
                        select.data('current-val', patternTypeId);

                        var row = select.closest('tr');
                        row.find('.btn-open-upload-pattern').data('pattern-type-id', patternTypeId);

                        select.addClass('border-success bg-light-success');
                        setTimeout(function() {
                            select.removeClass('border-success bg-light-success');
                        }, 1200);
                    },
                    error: function(xhr) {
                        select.prop('disabled', false);
                        select.val(originalVal);
                        var msg = xhr.responseJSON ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Failed to update pattern file type.';
                        alert(msg);
                    }
                });
            });

            // Delete Sample Pattern File
            $(document).on('click', '.btn-delete-sample-pattern', function() {
                var sampleId = $(this).data('sample-id');
                if (confirm('Are you sure you want to delete this pattern file? This will permanently remove the pattern file from storage.')) {
                    $.ajax({
                        url: '/styles/samples/' + sampleId + '/pattern',
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            if (res.success) {
                                window.location.hash = '#tab_sample_pattern';
                                window.location.reload();
                            } else {
                                alert(res.message || 'Failed to delete pattern file.');
                            }
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error deleting pattern file.';
                            alert(msg);
                        }
                    });
                }
            });
        });

        // Scoped behavior for the Add / Edit Colorway reference-type selector
        const modal = document.getElementById('addColorwayModal');

        if (modal) {
            const pantoneRadio = document.getElementById('c_type_pantone');
            const customRadio = document.getElementById('c_type_custom');
            const pantoneField = document.getElementById('c_pantone');
            const pantoneSection = document.getElementById('c_pantone_section');
            const customSection = document.getElementById('c_custom_section');
            const pantoneBadge = document.getElementById('c_preview_pantone_badge');
            const customBadge = document.getElementById('c_preview_custom_badge');
            const pantoneHelp = document.getElementById('c_pantone_help');
            const customHelp = document.getElementById('c_custom_help');

            let previousPantone = '';

            function syncReferenceType() {
                const isCustom = customRadio.checked;

                if (isCustom) {
                    if (!pantoneField.disabled) previousPantone = pantoneField.value;

                    pantoneField.value = '';
                    pantoneField.disabled = true; // Do not submit a Pantone reference for a custom color.
                } else {
                    pantoneField.disabled = false;

                    if (!pantoneField.value && previousPantone) pantoneField.value = previousPantone;
                }

                pantoneSection.classList.toggle('d-none', isCustom);
                customSection.classList.toggle('d-none', !isCustom);
                pantoneBadge.classList.toggle('d-none', isCustom);
                customBadge.classList.toggle('d-none', !isCustom);
                pantoneHelp.classList.toggle('d-none', isCustom);
                customHelp.classList.toggle('d-none', !isCustom);
                customHelp.classList.toggle('d-flex', isCustom);
            }

            pantoneRadio.addEventListener('change', syncReferenceType);
            customRadio.addEventListener('change', syncReferenceType);

            // Each new opening defaults to Pantone, while preserving the existing inputs and preview logic.
            modal.addEventListener('hidden.bs.modal', function () {
                pantoneRadio.checked = true;
                previousPantone = '';
                syncReferenceType();
            });

            syncReferenceType();
        }

        
        // CARTON DIMENSIONS module
        $(document).ready(function() {
            let dimTable = null;

            // Live calculation of CBM and Carton Size
            function calculateCartonDimensions() {
                const l = parseFloat($('#dim_carton_length').val()) || 0;
                const w = parseFloat($('#dim_carton_width').val()) || 0;
                const h = parseFloat($('#dim_carton_height').val()) || 0;

                // CBM = (L * W * H) / 1,000,000
                const cbm = (l * w * h) / 1000000;
                $('#dim_preview_cbm').text(cbm.toFixed(3));

                // Carton Size in inches = (L/2.54) + 2 * ((W/2.54) + (H/2.54))
                const l_in = l / 2.54;
                const w_in = w / 2.54;
                const h_in = h / 2.54;
                const cartonSize = l_in + (2 * (w_in + h_in));
                $('#dim_preview_carton_size').text(cartonSize.toFixed(2));
            }

            $(document).on('input', '.dim-calc-trigger', function() {
                calculateCartonDimensions();
            });

            // Initialize DataTables
            if ($('#table_carton_dimensions tbody tr.dimension-row').length > 0) {
                if (!$.fn.DataTable.isDataTable('#table_carton_dimensions')) {
                    dimTable = $('#table_carton_dimensions').DataTable({
                        pageLength: 10,
                        lengthMenu: [10, 25, 50, 100],
                        order: [[0, 'asc']],
                        responsive: true,
                        dom: "<'row mb-2'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end align-items-center'f>>" +
                             "<'row'<'col-12'tr>>" +
                             "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
                        language: {
                            emptyTable: '<div class="text-center py-2 text-white fw-semibold fs-7" style="background-color: #f59e0b;">No matching records found</div>',
                            zeroRecords: '<div class="text-center py-2 text-white fw-semibold fs-7" style="background-color: #f59e0b;">No matching records found</div>'
                        }
                    });

                    // Add Custom Filter to DataTables
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        if (settings.nTable.id !== 'table_carton_dimensions') return true;
                        const selectedType = $('input[name="dimension_type_filter"]:checked').val() || 'solid';
                        if (selectedType === 'all') return true;

                        const rowNode = dimTable.row(dataIndex).node();
                        const packType = $(rowNode).data('pack-type');
                        return packType === selectedType;
                    });
                }
            }

            // Dimension Filter logic
            function filterDimensions(selectedType) {
                const exportBase = '{{ route("styles.dimensions.export", $style->id) }}';
                $('#btn_dimension_export_csv').attr('href', exportBase + '?type=' + selectedType);

                if (dimTable) {
                    dimTable.draw();
                } else {
                    let visibleCount = 0;
                    $('#carton_dimensions_tbody tr.dimension-row').each(function() {
                        const packType = $(this).data('pack-type');
                        if (selectedType === 'all' || packType === selectedType) {
                            $(this).show();
                            visibleCount++;
                            $(this).find('.row-index').text(visibleCount);
                        } else {
                            $(this).hide();
                        }
                    });

                    if (visibleCount === 0 && $('#carton_dimensions_tbody tr.dimension-row').length > 0) {
                        $('#filter_no_records_banner').removeClass('d-none');
                    } else {
                        $('#filter_no_records_banner').addClass('d-none');
                    }
                }
            }

            $('.dimension-type-filter').on('change', function() {
                filterDimensions($(this).val());
            });

            // Initial filter run (defaults to 'solid')
            const initialType = $('input[name="dimension_type_filter"]:checked').val() || 'solid';
            filterDimensions(initialType);

            // Open Add Dimension Modal
            $('#btn_open_add_dimension').on('click', function() {
                $('#form_save_dimension').attr('action', '{{ route("styles.dimensions.store", $style->id) }}');
                $('#dimension_form_method').val('POST');
                $('#dimension_modal_title').html('<i class="fa-solid fa-box text-primary me-2"></i> Add Carton Dimension');
                $('#form_save_dimension')[0].reset();
                $('#dim_ratio_pack_carton').val('0');
                $('#dim_pcs_per_carton').val('24');
                $('#dim_carton_length').val('50');
                $('#dim_carton_width').val('38');
                $('#dim_carton_height').val('20');
                $('#dim_item_weight').val('0.250');
                $('#dim_carton_weight').val('0.80');
                calculateCartonDimensions();
            });

            // Open Edit Dimension Modal
            $(document).on('click', '.btn-edit-dimension', function() {
                const item = $(this).data('dimension');
                const updateUrl = '{{ route("styles.dimensions.update", ":id") }}'.replace(':id', item.idtbl_carton_dimension);

                $('#form_save_dimension').attr('action', updateUrl);
                $('#dimension_form_method').val('PUT');
                $('#dimension_modal_title').html('<i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Carton Dimension');

                $('#dim_size_desc').val(item.tbl_style_size_desc);
                $('#dim_ratio_pack_carton').val(item.ratio_pack_carton !== undefined ? item.ratio_pack_carton : 0);
                $('#dim_pcs_per_carton').val(item.pcs_per_carton);
                $('#dim_carton_length').val(item.carton_length);
                $('#dim_carton_width').val(item.carton_width);
                $('#dim_carton_height').val(item.carton_height);
                $('#dim_item_weight').val(item.item_weight);
                $('#dim_carton_weight').val(item.carton_weight);

                calculateCartonDimensions();
                $('#addCartonDimensionModal').modal('show');
            });

            // Delete Dimension
            $(document).on('click', '.btn-delete-dimension', function() {
                const id = $(this).data('id');
                const deleteUrl = '{{ route("styles.dimensions.destroy", ":id") }}'.replace(':id', id);

                const executeDelete = function() {
                    $.ajax({
                        url: deleteUrl,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: res.message || 'Carton dimension removed.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(function() {
                                    window.location.hash = '#tab_dimension';
                                    window.location.reload();
                                });
                            } else {
                                alert(res.message || 'Deleted successfully.');
                                window.location.hash = '#tab_dimension';
                                window.location.reload();
                            }
                        },
                        error: function(xhr) {
                            alert(xhr.responseJSON ? xhr.responseJSON.message : 'Delete failed.');
                        }
                    });
                };

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete Carton Dimension?',
                        text: 'Are you sure you want to remove this carton dimension?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            executeDelete();
                        }
                    });
                } else {
                    if (confirm('Are you sure you want to delete this carton dimension?')) {
                        executeDelete();
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
