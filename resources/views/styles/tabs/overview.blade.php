<div class="row g-6">
    <!-- LEFT COLUMN: Style Information -->
    <div class="col-xl-7 col-lg-6">
        <!-- Card: Style Information -->
        <div class="card card-flush shadow-sm mb-0 border">
            <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                    <i class="fa-solid fa-circle-info text-primary me-2"></i> Style Information
                </h3>
                <a href="{{ route('styles.edit', $style->id) }}" class="btn btn-sm btn-link text-primary p-0 fw-bold fs-8">
                    <i class="fa-solid fa-pen fs-8 me-1"></i> Edit
                </a>
            </div>
            <div class="card-body pt-2 fs-7 text-gray-700">
                <div class="row gy-3">
                    <div class="col-md-6"><span class="text-muted d-block fs-8">Style No.</span> <span class="fw-bold text-gray-900 fs-6">{{ $style->style_no }}</span></div>
                    <div class="col-md-6"><span class="text-muted d-block fs-8">Style Name</span> <span class="fw-bold text-gray-900">{{ $style->style_name }}</span></div>

                    <div class="col-md-6">
                        <span class="text-muted d-block fs-8 mb-1">Season</span>
                        <div class="d-flex align-items-center gap-2">
                            <select id="overview_season_select" class="form-select form-select-sm form-select-solid border py-1 fs-8 text-gray-900 fw-semibold" style="max-width: 230px;">
                                <option value="">Select Season</option>
                                @foreach($seasons ?? [] as $s)
                                    <option value="{{ $s }}" {{ (old('season', $style->season) == $s) ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            <span id="season_save_spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status"></span>
                            <span id="season_save_badge" class="badge badge-light-success fs-9 d-none"><i class="fa-solid fa-check text-success me-1"></i>Saved</span>
                        </div>
                    </div>

                    <div class="col-md-6"><span class="text-muted d-block fs-8">Program</span> <span class="fw-bold text-gray-900">{{ $style->program ?: 'N/A' }}</span></div>
                    <div class="col-md-6"><span class="text-muted d-block fs-8">Category</span> <span class="fw-bold text-gray-900">{{ $style->category ?: 'N/A' }}</span></div>

                    <div class="col-md-6"><span class="text-muted d-block fs-8">Gender</span> <span class="fw-bold text-gray-900">{{ $style->gender ?: 'N/A' }}</span></div>
                    <div class="col-md-6"><span class="text-muted d-block fs-8">Country of Origin</span> <span class="fw-bold text-gray-900">{{ $style->country ?: 'N/A' }}</span></div>

                    <div class="col-md-6"><span class="text-muted d-block fs-8">HTS Code (US)</span> <span class="fw-bold text-gray-900">{{ $style->hts_code ?: 'N/A' }}</span></div>
                    <div class="col-md-6"><span class="text-muted d-block fs-8">HTS Code (Canada)</span> <span class="fw-bold text-gray-900">{{ $style->hts_code_ca ?: 'N/A' }}</span></div>
                    <div class="col-md-6"><span class="text-muted d-block fs-8">Size Range</span> <span class="fw-bold text-gray-900">{{ $style->size_range ?: 'N/A' }}</span></div>
                    <div class="col-md-6"><span class="text-muted d-block fs-8">Current Revision</span> <span class="badge badge-light-success fw-bold fs-8">{{ $style->revision_no ?: 'REV 01' }}</span></div>

                    <div class="col-md-6">
                        <span class="text-muted d-block fs-8 mb-1">Construction</span>
                        <div class="d-flex align-items-center gap-2">
                            <select id="overview_construction_select" class="form-select form-select-sm form-select-solid border py-1 fs-8 text-gray-900 fw-semibold" style="max-width: 230px;">
                                <option value="">Select Construction</option>
                                @foreach($constructions as $cons)
                                    <option value="{{ $cons }}" {{ (old('construction', $style->construction) == $cons) ? 'selected' : '' }}>{{ $cons }}</option>
                                @endforeach
                            </select>
                            <span id="construction_save_spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status"></span>
                            <span id="construction_save_badge" class="badge badge-light-success fs-9 d-none"><i class="fa-solid fa-check text-success me-1"></i>Saved</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block fs-8 mb-1">Treatment / Finish</span>
                        <div class="d-flex align-items-center gap-2">
                            <input type="text" id="overview_treatment_input" class="form-control form-control-sm form-control-solid border py-1 fs-8 text-gray-900 fw-semibold" style="max-width: 230px;" placeholder="e.g. Mineral Wash" value="{{ $style->treatment }}">
                            <button type="button" id="btn_save_treatment" class="btn btn-sm btn-light-primary px-3 py-1 fs-8">Save</button>
                            <span id="treatment_save_spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status"></span>
                            <span id="treatment_save_badge" class="badge badge-light-success fs-9 d-none"><i class="fa-solid fa-check text-success me-1"></i>Saved</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block fs-8">Fabric Content & Weight</span>
                        <span class="fw-bold text-gray-900">{{ $style->content ?: 'N/A' }} {{ $style->gsm ? '('.$style->gsm.' GSM)' : '' }}</span>
                    </div>

                    <div class="col-12 border-top pt-3 mt-2">
                        <span class="text-muted d-block fs-8">Description</span>
                        <div class="fw-semibold text-gray-800 mt-1">
                            {{ $style->description ?: 'No description provided.' }}
                        </div>
                    </div>

                    @php
                        $styleSizes = is_array($style->sizes) ? $style->sizes : ($style->sizes ? (json_decode($style->sizes, true) ?: []) : []);
                    @endphp
                    <div class="col-12 mt-2">
                        <span class="text-muted d-block fs-8 mb-1">Available Sizes</span>
                        @if(!empty($styleSizes))
                            <span class="d-inline-flex gap-1 flex-wrap">
                                @foreach($styleSizes as $sz)
                                    <span class="badge badge-light-primary fw-bold fs-9">{{ $sz }}</span>
                                @endforeach
                            </span>
                        @else
                            <span class="text-muted fs-8 fst-italic">None selected (<a href="{{ route('styles.edit', $style->id) }}" class="text-primary fw-semibold">Click Edit to select sizes</a>)</span>
                        @endif
                    </div>

                    @if(!empty($style->tags))
                    <div class="col-12 mt-2">
                        <span class="text-muted d-block fs-8 mb-1">Tags</span> 
                        <span class="d-inline-flex gap-1 flex-wrap">
                            @foreach((array)$style->tags as $tag)
                                <span class="badge badge-light-secondary text-gray-700 fs-9">{{ $tag }}</span>
                            @endforeach
                        </span>
                    </div>
                    @endif

                    <!-- Special Instructions Word-like Editor Box -->
                    <div class="col-12 border-top pt-4 mt-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                            <div>
                                <label class="form-label fw-bold text-gray-800 fs-7 mb-0 text-uppercase d-flex align-items-center">
                                    <i class="fa-solid fa-file-signature text-primary me-2"></i> Special Instructions
                                </label>
                                <span class="text-muted fs-8">Word-like rich formatting (fonts, size, bold, italic, underline, text & highlight colors)</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span id="instructions_save_spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status"></span>
                                <span id="instructions_save_badge" class="badge badge-light-success fs-9 d-none">
                                    <i class="fa-solid fa-check text-success me-1"></i>Saved
                                </span>
                                <button type="button" id="btn_save_special_instructions" class="btn btn-sm btn-primary fw-bold px-3 py-1 fs-8 shadow-xs">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Save Instructions
                                </button>
                            </div>
                        </div>
                        <div class="border rounded bg-white overflow-hidden shadow-xs">
                            <textarea id="overview_special_instructions" name="special_instructions" class="form-control" rows="8">{!! old('special_instructions', $style->special_instructions) !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Style Images Preview -->
    <div class="col-xl-5 col-lg-6">
        @php
            $frontImages     = $style->images->where('image_type', 'Front');
            $backImages      = $style->images->where('image_type', 'Back');
            $detailsImages   = $style->images->where('image_type', 'Details');
            $colorwaysImages = $style->images->where('image_type', 'Colorways');
            $overviewImageTabs = [
                'All'       => ['key' => 'all', 'items' => $style->images],
                'Front'     => ['key' => 'front', 'items' => $frontImages],
                'Back'      => ['key' => 'back', 'items' => $backImages],
                'Details'   => ['key' => 'details', 'items' => $detailsImages],
                'Colorways' => ['key' => 'colorways', 'items' => $colorwaysImages],
            ];
        @endphp

        <!-- Card: Style Images Gallery -->
        <div class="card card-flush shadow-sm mb-6 border">
            <div class="card-header pt-4 pb-2 border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                    <i class="fa-solid fa-images text-info me-2"></i> Style Images
                </h3>
                <button type="button" class="btn btn-sm btn-light-primary fw-bold fs-8" data-bs-toggle="modal" data-bs-target="#quickUploadImageModal">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Image
                </button>
            </div>

            <div class="card-body pt-0">
                <!-- Category Filter Subtabs -->
                <ul class="nav nav-tabs nav-line-tabs border-bottom mb-3 fs-8 fw-bold" role="tablist">
                    @foreach($overviewImageTabs as $tabName => $tabInfo)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active py-2 border-bottom border-primary border-2 text-primary' : 'py-2 text-gray-600' }}" data-bs-toggle="tab" href="#overview_tab_{{ $tabInfo['key'] }}">
                                {{ $tabName }}
                                <span class="badge badge-sm badge-circle {{ $tabInfo['items']->count() > 0 ? 'badge-light-primary text-primary' : 'badge-light-secondary text-muted' }} ms-1">
                                    {{ $tabInfo['items']->count() }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($overviewImageTabs as $tabName => $tabInfo)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="overview_tab_{{ $tabInfo['key'] }}">
                            @if($tabInfo['items']->count() > 0)
                                <div class="row g-2">
                                    @foreach($tabInfo['items'] as $img)
                                        <div class="col-6 col-sm-4 image-card-wrapper" id="image_card_{{ $img->id }}">
                                            <div class="position-relative border rounded overflow-hidden shadow-xs bg-light style-image-card" style="cursor: zoom-in;" title="Click to view large">
                                                <img src="{{ asset('uploads/styles/' . $img->image_path) }}" 
                                                     alt="{{ $img->image_type }}" 
                                                     class="w-100 h-115px object-fit-cover btn-enlarge-image" 
                                                     data-src="{{ asset('uploads/styles/' . $img->image_path) }}" 
                                                     data-type="{{ $img->image_type }}"
                                                     onerror="this.onerror=null; this.src='{{ asset('media/svg/files/blank-image.svg') }}';">
                                                <div class="image-zoom-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 transition-opacity pointer-events-none">
                                                    <span class="badge bg-dark bg-opacity-75 text-white fs-9 py-1 px-1.5 rounded-pill shadow-sm">
                                                        <i class="fa-solid fa-magnifying-glass-plus text-primary"></i>
                                                    </span>
                                                </div>
                                                <span class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white fs-9 py-0.5 text-center fw-semibold pointer-events-none">
                                                    {{ $img->image_type }}
                                                </span>
                                                <button type="button" class="btn btn-icon btn-circle w-20px h-20px bg-white shadow position-absolute top-0 end-0 m-1 btn-delete-overview-img" data-id="{{ $img->id }}" data-category="{{ $tabInfo['key'] }}" title="Delete {{ $img->image_type }} Image" style="z-index: 50;">
                                                    <i class="fa-solid fa-xmark fs-9 text-danger pointer-events-none"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-6 text-center text-muted bg-light rounded border border-dashed">
                                    <i class="fa-solid fa-shirt fs-2x text-gray-400 mb-1 d-block"></i>
                                    <span class="fs-8">No {{ $tabName === 'All' ? '' : $tabName }} images uploaded yet.</span>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-link text-primary fw-semibold fs-8 p-0" data-bs-toggle="modal" data-bs-target="#quickUploadImageModal" data-default-type="{{ $tabName === 'All' ? 'Front' : $tabName }}">
                                            <i class="fa-solid fa-plus me-1"></i> Upload {{ $tabName === 'All' ? 'Image' : $tabName }}
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{--  QUICK UPLOAD IMAGE MODAL  --}}
<div class="modal fade" id="quickUploadImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Upload Style Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickUploadImageForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold fs-8">View Category*</label>
                        <select name="image_type" id="modal_image_type" class="form-select form-select-sm form-select-solid border" required>
                            <option value="Front">Front</option>
                            <option value="Back">Back</option>
                            <option value="Details">Details</option>
                            <option value="Colorways">Colorways</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold fs-8">Select Image*</label>
                        <input type="file" name="image" id="modal_image_file" class="form-control form-control-sm form-control-solid border" accept="image/*" required>
                        <div class="form-text fs-9 text-muted">PNG, JPG, WEBP up to 10MB</div>
                    </div>

                    <div id="modal_image_preview" class="d-none text-center p-2 bg-light rounded border border-dashed mb-2">
                        <img id="modal_preview_img" src="" class="img-fluid rounded" style="max-height: 160px;">
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btn_submit_quick_image" class="btn btn-sm btn-primary px-4 fw-bold">
                        <i class="fa-solid fa-upload me-1"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize TinyMCE Word-like Editor for Special Instructions
        function initSpecialInstructionsEditor() {
            if (typeof tinymce === 'undefined') return;

            if (tinymce.get('overview_special_instructions')) {
                tinymce.get('overview_special_instructions').remove();
            }

            const isDarkMode = document.documentElement.getAttribute("data-bs-theme-mode") === "dark" || 
                               document.documentElement.getAttribute("data-bs-theme") === "dark";

            tinymce.init({
                selector: '#overview_special_instructions',
                menubar: false,
                height: 250,
                branding: false,
                elementpath: false,
                skin_url: isDarkMode 
                    ? "{{ asset('plugins/custom/tinymce/skins/ui/oxide-dark') }}" 
                    : "{{ asset('plugins/custom/tinymce/skins/ui/oxide') }}",
                content_css: isDarkMode 
                    ? "{{ asset('plugins/custom/tinymce/skins/content/dark/content.css') }}" 
                    : "{{ asset('plugins/custom/tinymce/skins/content/default/content.css') }}",
                toolbar: [
                    'undo redo | fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat'
                ],
                font_formats: 'Inter=Inter,sans-serif; Arial=arial,helvetica,sans-serif; Calibri=calibri,candara,segoe,segoe ui,optima,arial,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva',
                fontsize_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 24pt 36pt',
                content_style: 'body { font-family: Inter, Arial, sans-serif; font-size: 13px; color: ' + (isDarkMode ? '#cdcdde' : '#181C32') + '; line-height: 1.5; padding: 10px; }',
                setup: function (editor) {
                    editor.on('change keyup', function () {
                        editor.save();
                    });
                }
            });
        }

        initSpecialInstructionsEditor();

        // Save Special Instructions AJAX handler
        $('#btn_save_special_instructions').on('click', function (e) {
            e.preventDefault();
            const btn = $(this);
            const spinner = $('#instructions_save_spinner');
            const badge = $('#instructions_save_badge');

            let content = '';
            if (typeof tinymce !== 'undefined' && tinymce.get('overview_special_instructions')) {
                content = tinymce.get('overview_special_instructions').getContent();
            } else {
                content = $('#overview_special_instructions').val();
            }

            btn.prop('disabled', true);
            spinner.removeClass('d-none');
            badge.addClass('d-none');

            $.ajax({
                url: "{{ route('styles.update-special-instructions', $style->id) }}",
                type: 'PATCH',
                data: {
                    _token: "{{ csrf_token() }}",
                    special_instructions: content
                },
                dataType: 'json',
                success: function (res) {
                    btn.prop('disabled', false);
                    spinner.addClass('d-none');
                    badge.removeClass('d-none');
                    setTimeout(() => { badge.addClass('d-none'); }, 3000);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            text: res.message || 'Special instructions saved successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function (xhr) {
                    btn.prop('disabled', false);
                    spinner.addClass('d-none');
                    const msg = xhr.responseJSON?.message || 'Failed to save special instructions.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', msg, 'error');
                    } else {
                        alert(msg);
                    }
                }
            });
        });

        $('#overview_season_select').on('change', function () {
            const val = $(this).val();
            const spinner = $('#season_save_spinner');
            const badge = $('#season_save_badge');

            spinner.removeClass('d-none');
            badge.addClass('d-none');

            $.ajax({
                url: "{{ route('styles.update-season', $style->id) }}",
                type: 'PATCH',
                data: {
                    _token: "{{ csrf_token() }}",
                    season: val
                },
                dataType: 'json',
                success: function (res) {
                    spinner.addClass('d-none');
                    badge.removeClass('d-none');
                    setTimeout(() => { badge.addClass('d-none'); }, 2500);
                },
                error: function (xhr) {
                    spinner.addClass('d-none');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update season.', 'error');
                    } else {
                        alert(xhr.responseJSON?.message || 'Failed to update season.');
                    }
                }
            });
        });

        $('#overview_construction_select').on('change', function () {
            const val = $(this).val();
            const spinner = $('#construction_save_spinner');
            const badge = $('#construction_save_badge');

            spinner.removeClass('d-none');
            badge.addClass('d-none');

            $.ajax({
                url: "{{ route('styles.update-construction', $style->id) }}",
                type: 'PATCH',
                data: {
                    _token: "{{ csrf_token() }}",
                    construction: val
                },
                dataType: 'json',
                success: function (res) {
                    spinner.addClass('d-none');
                    badge.removeClass('d-none');
                    setTimeout(() => { badge.addClass('d-none'); }, 2500);
                },
                error: function (xhr) {
                    spinner.addClass('d-none');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update construction.', 'error');
                    } else {
                        alert(xhr.responseJSON?.message || 'Failed to update construction.');
                    }
                }
            });
        });

        function saveTreatment() {
            const val = $('#overview_treatment_input').val();
            const spinner = $('#treatment_save_spinner');
            const badge = $('#treatment_save_badge');

            spinner.removeClass('d-none');
            badge.addClass('d-none');

            $.ajax({
                url: "{{ route('styles.update-treatment', $style->id) }}",
                type: 'PATCH',
                data: {
                    _token: "{{ csrf_token() }}",
                    treatment: val
                },
                dataType: 'json',
                success: function (res) {
                    spinner.addClass('d-none');
                    badge.removeClass('d-none');
                    setTimeout(() => { badge.addClass('d-none'); }, 2500);
                },
                error: function (xhr) {
                    spinner.addClass('d-none');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update treatment.', 'error');
                    } else {
                        alert(xhr.responseJSON?.message || 'Failed to update treatment.');
                    }
                }
            });
        }

        $('#btn_save_treatment').on('click', saveTreatment);
        $('#overview_treatment_input').on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                saveTreatment();
            }
        });

        // Image Quick Upload Modal Pre-selection
        $('#quickUploadImageModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const defaultType = button.data('default-type');
            if (defaultType) {
                $('#modal_image_type').val(defaultType);
            }
        });

        // Image Preview on file select
        $('#modal_image_file').on('change', function () {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#modal_preview_img').attr('src', e.target.result);
                    $('#modal_image_preview').removeClass('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                $('#modal_preview_img').attr('src', '');
                $('#modal_image_preview').addClass('d-none');
            }
        });

        // Submit Quick Upload Form
        $('#quickUploadImageForm').on('submit', function (e) {
            e.preventDefault();
            const btn = $('#btn_submit_quick_image');
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Uploading...');

            const formData = new FormData(this);

            $.ajax({
                url: "{{ route('styles.images.upload', $style->id) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    btn.prop('disabled', false).html(originalHtml);
                    $('#quickUploadImageModal').modal('hide');
                    $('#quickUploadImageForm')[0].reset();
                    $('#modal_image_preview').addClass('d-none');
                    $('#modal_preview_img').attr('src', '');

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Uploaded!',
                            text: res.message || 'Image uploaded successfully.',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        window.location.reload();
                    }
                },
                error: function (xhr) {
                    btn.prop('disabled', false).html(originalHtml);
                    const msg = xhr.responseJSON?.message || 'Failed to upload image.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Upload Failed', msg, 'error');
                    } else {
                        alert(msg);
                    }
                }
            });
        });

        // Delete Image from Overview Gallery with Notification Toast
        $(document).on('click', '.btn-delete-overview-img', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const btn = $(this);
            const imageId = btn.data('id');
            const catKey = btn.data('category');

            const executeDelete = () => {
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-xs text-danger"></span>');
                $.ajax({
                    url: '/styles/images/' + imageId,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (res) {
                        // Show visual notification toast instead of console
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message || 'Image deleted successfully.',
                                toast: true,
                                position: 'top-end',
                                timer: 2000,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });
                        } else {
                            alert(res.message || 'Image deleted successfully.');
                        }

                        // Remove card immediately without needing reload
                        $(`.image-card-wrapper#image_card_${imageId}`).fadeOut(250, function () {
                            $(this).remove();
                        });
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html('<i class="fa-solid fa-xmark fs-9 text-danger pointer-events-none"></i>');
                        const msg = xhr.responseJSON?.message || 'Failed to delete image.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Delete Failed', msg, 'error');
                        } else {
                            alert(msg);
                        }
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Image?',
                    text: 'Are you sure you want to permanently delete this style image?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        executeDelete();
                    }
                });
            } else {
                if (confirm('Are you sure you want to delete this style image?')) {
                    executeDelete();
                }
            }
        });

        // Overview Lightbox Image Viewer Logic
        let overviewGalleryImages = [];
        let overviewCurrentIdx = 0;

        function collectOverviewImages() {
            overviewGalleryImages = [];
            const activeTab = document.querySelector('#overview_tab_all, .tab-pane[id^="overview_tab_"].active');
            let targets = [];
            if (activeTab) {
                targets = activeTab.querySelectorAll('.btn-enlarge-image');
            }
            if (!targets || targets.length === 0) {
                targets = document.querySelectorAll('.tab-pane[id^="overview_tab_"] .btn-enlarge-image');
            }

            targets.forEach((img) => {
                overviewGalleryImages.push({
                    src: img.getAttribute('data-src') || img.src,
                    type: img.getAttribute('data-type') || 'Style'
                });
            });
        }

        function showOverviewImageAtIndex(idx) {
            if (!overviewGalleryImages.length) return;
            if (idx < 0) idx = overviewGalleryImages.length - 1;
            if (idx >= overviewGalleryImages.length) idx = 0;
            overviewCurrentIdx = idx;

            const item = overviewGalleryImages[overviewCurrentIdx];
            const viewerImg = document.getElementById('viewerImage');
            const badge = document.getElementById('viewerCategoryBadge');
            const counter = document.getElementById('viewerImageCounter');
            const downloadBtn = document.getElementById('viewerDownloadBtn');

            if (viewerImg) {
                viewerImg.src = item.src;
                viewerImg.style.opacity = '1';
            }
            if (badge) badge.textContent = (item.type || 'Style') + ' View';
            if (counter) counter.textContent = `Image ${overviewCurrentIdx + 1} of ${overviewGalleryImages.length}`;
            if (downloadBtn) {
                downloadBtn.href = item.src;
                downloadBtn.setAttribute('download', (item.type || 'style') + '_image.png');
            }

            const prevBtn = document.getElementById('viewerPrevBtn');
            const nextBtn = document.getElementById('viewerNextBtn');
            if (overviewGalleryImages.length <= 1) {
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
            } else {
                if (prevBtn) prevBtn.style.display = 'flex';
                if (nextBtn) nextBtn.style.display = 'flex';
            }
        }

        $(document).on('click', '.style-image-card, .btn-enlarge-image', function (e) {
            if ($(e.target).closest('.btn-delete-overview-img').length) {
                return;
            }

            collectOverviewImages();
            let clickedImg = $(this).is('.btn-enlarge-image') ? $(this) : $(this).find('.btn-enlarge-image');
            const clickedSrc = clickedImg.attr('data-src') || clickedImg.attr('src');

            const foundIdx = overviewGalleryImages.findIndex(i => i.src === clickedSrc);
            overviewCurrentIdx = foundIdx >= 0 ? foundIdx : 0;

            showOverviewImageAtIndex(overviewCurrentIdx);

            const viewerModalEl = document.getElementById('imageViewerModal');
            if (viewerModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(viewerModalEl).show();
            } else {
                $('#imageViewerModal').modal('show');
            }
        });

        $(document).on('click', '#viewerPrevBtn', function (e) {
            e.stopPropagation();
            showOverviewImageAtIndex(overviewCurrentIdx - 1);
        });

        $(document).on('click', '#viewerNextBtn', function (e) {
            e.stopPropagation();
            showOverviewImageAtIndex(overviewCurrentIdx + 1);
        });
    });
</script>
@endpush

<style>
    .style-image-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .style-image-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
    }
    .style-image-card:hover .image-zoom-overlay {
        opacity: 1 !important;
    }
    .style-image-card .btn-enlarge-image {
        transition: transform 0.25s ease;
    }
    .style-image-card:hover .btn-enlarge-image {
        transform: scale(1.05);
    }
</style>
