<!-- 1. BOM ITEM MODAL (Add & Edit) -->
<div class="modal fade" id="addBomItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="form_save_bom" action="{{ route('styles.bom.store', $style->id) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="bom_form_method" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="bom_modal_title">Add BOM Component</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Component Type</label>
                            <select name="component_type" id="bom_component_type" class="form-select form-select-sm" required>
                                <option value="Fabric">Fabric</option>
                                <option value="Rib">Rib</option>
                                <option value="Drawcord">Drawcord</option>
                                <option value="Trims">Trims</option>
                                <option value="Label">Label</option>
                                <option value="Packaging">Packaging</option>
                                <option value="Thread">Thread</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Item Description</label>
                            <input type="text" name="item_description" id="bom_item_description" class="form-control form-control-sm" placeholder="e.g. FBR-2456 - 330 GSM Fleece" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Supplier</label>
                            <input type="text" name="supplier" id="bom_supplier" class="form-control form-control-sm" placeholder="Supplier Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Placement</label>
                            <input type="text" name="placement" id="bom_placement" class="form-control form-control-sm" placeholder="e.g. Body, Hood, Cuff">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Color</label>
                            <input type="text" name="color" id="bom_color" class="form-control form-control-sm" placeholder="e.g. Navy">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold required">Qty / Size</label>
                            <input type="number" step="0.0001" name="qty_per_size" id="bom_qty_per_size" class="form-control form-control-sm" value="1.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold required">UOM</label>
                            <input type="text" name="uom" id="bom_uom" class="form-control form-control-sm" value="Mtr" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Unit Cost ($)</label>
                            <input type="number" step="0.0001" name="unit_cost" id="bom_unit_cost" class="form-control form-control-sm" value="0.00" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Save Component</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. MEASUREMENT MODAL (Add & Edit) -->
<div class="modal fade" id="addMeasurementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="form_save_measurement" action="{{ route('styles.measurements.store', $style->id) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="measurement_form_method" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="measurement_modal_title">Add Measurement Specification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold required">Measurement Point</label>
                            <input type="text" name="measurement_point" id="m_point" class="form-control form-control-sm" placeholder="e.g. Chest - 1 inch below armhole" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold required">Spec Value</label>
                            <input type="number" step="0.01" name="spec_value" id="m_spec_value" class="form-control form-control-sm" value="0.0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tol (-)</label>
                            <input type="number" step="0.01" name="tol_minus" id="m_tol_minus" class="form-control form-control-sm" value="0.5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tol (+)</label>
                            <input type="number" step="0.01" name="tol_plus" id="m_tol_plus" class="form-control form-control-sm" value="0.5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">UOM</label>
                            <input type="text" name="uom" id="m_uom" class="form-control form-control-sm" value="Inch">
                        </div>
                        <div class="col-12 border-top pt-2 mt-2 fw-bold text-gray-700 fs-8">Size Breakdown (XS - 6XL)</div>
                        @php
                            $modalSizes = [
                                'xs' => 'XS', 's' => 'S', 'm' => 'M', 'l' => 'L', 'xl' => 'XL', 
                                'xxl' => 'XXL', '3xl' => '3XL', '4xl' => '4XL', '5xl' => '5XL', '6xl' => '6XL'
                            ];
                        @endphp
                        @foreach($modalSizes as $k => $lbl)
                            <div class="col-6 col-sm-4 col-md-2 mb-2">
                                <label class="form-label fs-9 fw-bold mb-1">{{ $lbl }}</label>
                                <input type="number" step="0.01" name="{{ $k }}" id="m_{{ $k }}" class="form-control form-control-sm" placeholder="0.0">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Save Spec</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. COLORWAY MODAL (Add & Edit) -->
<div class="modal fade" id="addColorwayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <form id="form_save_colorway" action="{{ route('styles.colorways.store', $style->id) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="colorway_form_method" value="POST">
                
                <div class="modal-header bg-light py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="symbol symbol-30px bg-primary text-white d-flex align-items-center justify-content-center rounded-circle">
                            <i class="fa-solid fa-palette fs-6"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-gray-800" id="colorway_modal_title">Add Colorway</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 fs-7">
                    <div class="row g-4">
                        <!-- Colorway type: default to the existing Pantone workflow -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-800 mb-2">Color Reference Type</label>
                            <div class="d-flex flex-wrap gap-4 p-3 bg-light rounded border">
                                <div class="form-check form-check-custom form-check-sm">
                                    <input class="form-check-input" type="radio" name="color_reference_type" id="c_type_pantone" value="pantone" checked>
                                    <label class="form-check-label fw-semibold" for="c_type_pantone">Pantone Color</label>
                                </div>
                                <div class="form-check form-check-custom form-check-sm">
                                    <input class="form-check-input" type="radio" name="color_reference_type" id="c_type_custom" value="non_pantone">
                                    <label class="form-check-label fw-semibold" for="c_type_custom">Non-Pantone Color</label>
                                </div>
                            </div>
                        </div>

                        <!-- Left Column: Pantone & Color Inputs -->
                        <div class="col-lg-7 border-end-lg pe-lg-4">
                            <!-- Pantone Code Field (Primary Input) -->
                            <div id="c_pantone_section" class="mb-3">
                                <label class="form-label fw-bold text-gray-800 d-flex justify-content-between align-items-center">
                                    <span><i class="fa-solid fa-swatchbook me-1 text-primary"></i> Pantone Color Code</span>
                                    <span class="badge badge-light-primary fs-9 fw-normal">Auto-Fetch Color & Swatch</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-hashtag text-gray-500"></i></span>
                                    <input type="text" name="pantone_code" id="c_pantone" list="pantone_preset_list" autocomplete="off" class="form-control form-control-sm border-start-0 text-uppercase fw-semibold" placeholder="e.g. PANTONE 19-4052 TCX or 286 C">
                                    <button type="button" class="btn btn-sm btn-primary fw-bold" id="btn_lookup_pantone">
                                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Auto-Fill
                                    </button>
                                </div>
                                <datalist id="pantone_preset_list">
                                    @foreach(\App\Services\PantoneService::getAll() as $p)
                                        <option value="{{ $p['code'] }}">{{ $p['name'] }} ({{ $p['hex'] }})</option>
                                    @endforeach
                                </datalist>
                                <div id="pantone_status_msg" class="form-text fs-9 mt-1">
                                    Type standard Pantone TCX/TPG or PMS numbers to auto-detect swatch hex & color name.
                                </div>
                            </div>

                            <!-- Alternative workflow: choose a color manually, without a Pantone number -->
                            <div id="c_custom_section" class="mb-3 d-none">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fa-solid fa-droplet text-primary"></i>
                                    <span class="fw-bold text-gray-800">Custom / Supplier Color</span>
                                    <span class="badge badge-light-info fs-9 fw-normal">Manual Color Selection</span>
                                </div>
                                <div class="p-3 bg-light rounded border border-dashed fs-8 text-gray-700">
                                    Enter your own color name and choose a swatch using the color picker or HEX field below.
                                    You can use the Lab Dip Ref to record your supplier's matching reference.
                                    No Pantone code is required.
                                </div>
                            </div>

                            <hr class="my-3 text-gray-200">

                            <!-- Color Name -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-gray-800 required">Color Name</label>
                                <input type="text" name="color_name" id="c_color_name" class="form-control form-control-sm" placeholder="e.g. Classic Blue" required>
                            </div>

                            <!-- Swatch Color Hex with Dual Sync Picker -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-gray-800">Color Swatch Fine-Tuning (HEX / RGB)</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <input type="color" id="c_hex_picker" class="form-control form-control-color w-40px h-35px p-1 rounded cursor-pointer" value="#0f4c81" title="Choose color visually">
                                    </div>
                                    <div class="col">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-muted">HEX</span>
                                            <input type="text" name="swatch_color_hex" id="c_hex" class="form-control form-control-sm font-monospace text-uppercase" placeholder="#0F4C81" value="#0f4c81">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-gray-800">Lab Dip Ref</label>
                                    <input type="text" name="lab_op_ref" id="c_lab_ref" class="form-control form-control-sm" placeholder="e.g. LD-001 / Batch A">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-gray-800">Status</label>
                                    <select name="status" id="c_status" class="form-select form-select-sm">
                                        <option value="Approved">Approved</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Live Swatch Preview & Info -->
                        <div class="col-lg-5 d-flex flex-column justify-content-between">
                            <div>
                                <label class="form-label fw-bold text-gray-800 mb-2">
                                    <i class="fa-solid fa-eye text-success me-1"></i> Live Swatch Card Preview
                                </label>
                                
                                <div class="card border shadow-xs overflow-hidden rounded-3">
                                    <!-- Dynamic Swatch Preview Box -->
                                    <div id="c_preview_box" class="h-140px d-flex flex-column align-items-center justify-content-center p-3 text-white fw-bold shadow-inner position-relative transition-all" style="background-color: #0f4c81;">
                                        <i class="fa-solid fa-shirt fs-1 opacity-75 mb-1" id="c_preview_icon"></i>
                                        <span id="c_preview_hex_tag" class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75 fs-9 font-monospace">#0F4C81</span>
                                    </div>
                                    <div class="card-body p-3 bg-white">
                                        <div id="c_preview_name" class="fw-bold text-gray-900 fs-6 mb-1">Classic Blue</div>
                                        <div class="d-flex align-items-center gap-1 mb-2">
                                            <span class="badge bg-light text-dark border fs-9 fw-semibold" id="c_preview_pantone_badge">
                                                <i class="fa-solid fa-palette text-primary me-1"></i> <span id="c_preview_pantone">PANTONE 19-4052 TCX</span>
                                            </span>
                                            <span id="c_preview_custom_badge" class="badge bg-light text-dark border fs-9 fw-semibold d-none">
                                                <i class="fa-solid fa-droplet text-primary me-1"></i> Custom / Non-Pantone
                                            </span>
                                        </div>
                                        <div class="text-muted fs-8 mb-2" id="c_preview_lab_wrap">
                                            Ref: <span id="c_preview_lab">LD-001</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                            <span class="text-muted fs-9">Status:</span>
                                            <span id="c_preview_status_badge" class="badge badge-light-success fs-9 fw-bold px-2 py-1">Approved</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="c_pantone_help" class="alert alert-dismissible bg-light-primary border border-primary border-dashed d-flex align-items-center p-2 mt-3 mb-0 rounded">
                                <i class="fa-solid fa-circle-info fs-6 text-primary me-2 ms-1"></i>
                                <div class="fs-9 text-gray-700">
                                    Pantone codes standardise fabric dye matching across suppliers & factories.
                                </div>
                            </div>
                            <div id="c_custom_help" class="alert bg-light-info border border-info border-dashed d-none align-items-center p-2 mt-3 mb-0 rounded">
                                <i class="fa-solid fa-circle-info fs-6 text-info me-2 ms-1"></i>
                                <div class="fs-9 text-gray-700">Custom colors use the selected HEX swatch and optional Lab Dip Ref for supplier matching.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-light border fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-4">
                        <i class="fa-solid fa-check me-1"></i> Save Colorway Swatch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. SAMPLE MODAL (Add & Edit) -->
<div class="modal fade" id="addSampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="form_save_sample" action="{{ route('styles.samples.store', $style->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="sample_form_method" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="sample_modal_title">Add Sample Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sample Type <span class="text-muted fw-normal fs-9">(Master)</span></label>
                            <select name="sample_type_id" id="s_sample_type_id" class="form-select form-select-sm">
                                <option value="">-- Select Sample Type --</option>
                                @if(isset($sampleTypes))
                                    @foreach($sampleTypes as $st)
                                        <option value="{{ $st->id }}" data-code="{{ $st->code }}" data-name="{{ $st->name }}">{{ $st->name }}{{ $st->code ? ' ('.$st->code.')' : '' }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Season <span class="text-muted fw-normal fs-9">(Master)</span></label>
                            <select name="season" id="s_season" class="form-select form-select-sm">
                                <option value="">-- Select Season --</option>
                                @if(isset($seasons))
                                    @foreach($seasons as $seasonOpt)
                                        <option value="{{ $seasonOpt }}">{{ $seasonOpt }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold required">Sample Name</label>
                            <input type="text" name="sample_name" id="s_name" class="form-control form-control-sm" placeholder="e.g. Fit Sample 1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sent Date</label>
                            <input type="date" name="sent_date" id="s_sent_date" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Waybill No</label>
                            <input type="text" name="waybill_no" id="s_waybill_no" class="form-control form-control-sm" placeholder="e.g. DHL-9876543210">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold required">Status</label>
                            <select name="status" id="s_status" class="form-select form-select-sm" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Revised">Revised</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Submitted By</label>
                            <input type="text" name="submitted_by" id="s_submitted_by" class="form-control form-control-sm" placeholder="Name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Approval Date</label>
                            <input type="date" name="approval_date" id="s_approval_date" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Sample Code</label>
                            <input type="text" name="sample_code" id="s_code" class="form-control form-control-sm" placeholder="e.g. FTS-001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tag No</label>
                            <input type="text" name="tag_no" id="s_tag_no" class="form-control form-control-sm" placeholder="e.g. TAG-1029">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Remarks & Technical Comments</label>
                            <textarea name="remarks" id="s_remarks" class="form-control form-control-sm" rows="2" placeholder="Notes, comments or instructions"></textarea>
                        </div>

                        <div class="col-12">
                            <div class="separator separator-dashed my-1"></div>
                            <span class="fs-8 fw-bold text-uppercase text-muted d-block mb-1">Sample Attachments & Documents (Max 20MB each)</span>
                        </div>

                        <!-- 1. QC Report -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold fs-8"><i class="fa-solid fa-file-pdf text-danger me-1"></i> QC Report</label>
                            <input type="file" name="qc_report" id="s_qc_report" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                            <div id="s_qc_report_preview_box" class="d-none mt-2 p-2 bg-light rounded border d-flex align-items-center justify-content-between">
                                <span class="fs-9 fw-semibold text-gray-800 text-truncate me-1" id="s_qc_report_name"></span>
                                <a href="#" target="_blank" id="s_qc_report_view_btn" class="btn btn-xs btn-light-danger fw-bold text-nowrap p-1">
                                    <i class="fa-solid fa-eye fs-9"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 2. Cost Sheet -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold fs-8"><i class="fa-solid fa-file-invoice-dollar text-success me-1"></i> Cost Sheet</label>
                            <input type="file" name="cost_sheet" id="s_cost_sheet" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.jpg,.jpeg,.png">
                            <div id="s_cost_sheet_preview_box" class="d-none mt-2 p-2 bg-light rounded border d-flex align-items-center justify-content-between">
                                <span class="fs-9 fw-semibold text-gray-800 text-truncate me-1" id="s_cost_sheet_name"></span>
                                <a href="#" target="_blank" id="s_cost_sheet_view_btn" class="btn btn-xs btn-light-success fw-bold text-nowrap p-1">
                                    <i class="fa-solid fa-eye fs-9"></i>
                                </a>
                            </div>
                        </div>

                        <!-- 3. Comment Sheet -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold fs-8"><i class="fa-solid fa-file-lines text-primary me-1"></i> Comment Sheet</label>
                            <input type="file" name="comment_sheet" id="s_comment_sheet" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.jpg,.jpeg,.png">
                            <div id="s_comment_sheet_preview_box" class="d-none mt-2 p-2 bg-light rounded border d-flex align-items-center justify-content-between">
                                <span class="fs-9 fw-semibold text-gray-800 text-truncate me-1" id="s_comment_sheet_name"></span>
                                <a href="#" target="_blank" id="s_comment_sheet_view_btn" class="btn btn-xs btn-light-primary fw-bold text-nowrap p-1">
                                    <i class="fa-solid fa-eye fs-9"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Save Sample</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4b. VIEW SAMPLE DETAILS MODAL --}}
<div class="modal fade" id="viewSampleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px bg-light-primary p-2 rounded">
                        <i class="fa-solid fa-vial fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-gray-900 my-0" id="v_sample_title">Sample Details</h5>
                        <span class="text-muted fs-8" id="v_sample_subtitle">Detailed sample tracking record</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span id="v_status_badge" class="badge fs-8 fw-bold px-3 py-2"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body fs-7 p-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border h-100">
                            <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Stage & Identification</span>
                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <span class="text-gray-600 fs-8 d-block">Sample Name:</span>
                                    <span class="fw-bold text-gray-900 fs-6" id="v_sample_name">—</span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <span class="text-gray-600 fs-8 d-block">Sample Type:</span>
                                        <span class="badge badge-light-primary fw-bold fs-8" id="v_sample_type">—</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-gray-600 fs-8 d-block">Season:</span>
                                        <span class="badge badge-light-dark fw-bold fs-8" id="v_season">—</span>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <span class="text-gray-600 fs-8 d-block">Sample Code:</span>
                                        <span class="fw-semibold text-gray-800 font-monospace" id="v_sample_code">—</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-gray-600 fs-8 d-block">Tag No:</span>
                                        <span class="badge badge-light-secondary text-gray-800 font-monospace fs-8" id="v_tag_no">—</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border h-100">
                            <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Shipment & Approval Timeline</span>
                            <div class="d-flex flex-column gap-2">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <span class="text-gray-600 fs-8 d-block">Sent Date:</span>
                                        <span class="fw-bold text-gray-800" id="v_sent_date">—</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-gray-600 fs-8 d-block">Approval Date:</span>
                                        <span class="fw-bold text-gray-800" id="v_approval_date">—</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-gray-600 fs-8 d-block">Waybill / Tracking No:</span>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge badge-light-secondary text-gray-800 font-monospace fs-8" id="v_waybill_no">—</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-gray-600 fs-8 d-block">Submitted By:</span>
                                    <span class="fw-semibold text-gray-800" id="v_submitted_by">—</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Section (QC, Cost Sheet, Comment Sheet) -->
                    <div class="col-12">
                        <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-2">Sample Documents & Attachments</span>
                        <div class="row g-3">
                            <!-- QC Report Card -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border h-100 d-flex flex-column justify-content-between" id="v_qc_card">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fa-solid fa-file-pdf fs-3 text-danger"></i>
                                            <div>
                                                <span class="fw-bold text-gray-900 fs-8 d-block">QC Report</span>
                                                <span class="text-muted fs-9 text-truncate d-block" id="v_qc_filename" style="max-width: 170px;">None</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="v_qc_actions" class="d-none d-flex gap-1 mt-2">
                                        <a href="#" target="_blank" id="v_qc_view_btn" class="btn btn-xs btn-light-danger fw-bold flex-grow-1 text-center">
                                            <i class="fa-solid fa-eye fs-9 me-1"></i> Open
                                        </a>
                                        <a href="#" download id="v_qc_download_btn" class="btn btn-xs btn-light border p-1" title="Download">
                                            <i class="fa-solid fa-download fs-9"></i>
                                        </a>
                                    </div>
                                    <div id="v_qc_empty" class="text-muted fs-9 fst-italic">Not uploaded</div>
                                </div>
                            </div>

                            <!-- Cost Sheet Card -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border h-100 d-flex flex-column justify-content-between" id="v_cost_card">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fa-solid fa-file-invoice-dollar fs-3 text-success"></i>
                                            <div>
                                                <span class="fw-bold text-gray-900 fs-8 d-block">Cost Sheet</span>
                                                <span class="text-muted fs-9 text-truncate d-block" id="v_cost_filename" style="max-width: 170px;">None</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="v_cost_actions" class="d-none d-flex gap-1 mt-2">
                                        <a href="#" target="_blank" id="v_cost_view_btn" class="btn btn-xs btn-light-success fw-bold flex-grow-1 text-center">
                                            <i class="fa-solid fa-eye fs-9 me-1"></i> Open
                                        </a>
                                        <a href="#" download id="v_cost_download_btn" class="btn btn-xs btn-light border p-1" title="Download">
                                            <i class="fa-solid fa-download fs-9"></i>
                                        </a>
                                    </div>
                                    <div id="v_cost_empty" class="text-muted fs-9 fst-italic">Not uploaded</div>
                                </div>
                            </div>

                            <!-- Comment Sheet Card -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border h-100 d-flex flex-column justify-content-between" id="v_comment_card">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fa-solid fa-file-lines fs-3 text-primary"></i>
                                            <div>
                                                <span class="fw-bold text-gray-900 fs-8 d-block">Comment Sheet</span>
                                                <span class="text-muted fs-9 text-truncate d-block" id="v_comment_filename" style="max-width: 170px;">None</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="v_comment_actions" class="d-none d-flex gap-1 mt-2">
                                        <a href="#" target="_blank" id="v_comment_view_btn" class="btn btn-xs btn-light-primary fw-bold flex-grow-1 text-center">
                                            <i class="fa-solid fa-eye fs-9 me-1"></i> Open
                                        </a>
                                        <a href="#" download id="v_comment_download_btn" class="btn btn-xs btn-light border p-1" title="Download">
                                            <i class="fa-solid fa-download fs-9"></i>
                                        </a>
                                    </div>
                                    <div id="v_comment_empty" class="text-muted fs-9 fst-italic">Not uploaded</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks Section -->
                    <div class="col-12">
                        <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Remarks & Technical Comments</span>
                        <div class="p-3 bg-light rounded border text-gray-800 fs-7" id="v_remarks" style="min-height: 60px;">
                            No remarks recorded.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-3">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-warning fw-bold px-4" id="v_btn_trigger_edit">
                    <i class="fa-solid fa-pen fs-8 me-1"></i> Edit This Sample
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 5. EDIT TECH PACK MODAL -->
<div class="modal fade" id="editTechPackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="form_edit_tech_pack" action="{{ route('styles.tech-pack.update', $style->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Tech Pack Specifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fitting</label>
                            <input type="text" name="fitting" class="form-control form-control-sm" value="{{ $style->techPack?->fitting }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stitching</label>
                            <input type="text" name="stitching" class="form-control form-control-sm" value="{{ $style->techPack?->stitching }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Label & Branding</label>
                            <input type="text" name="label_branding" class="form-control form-control-sm" value="{{ $style->techPack?->label_branding }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Care Instructions</label>
                            <input type="text" name="care_instructions" class="form-control form-control-sm" value="{{ $style->techPack?->care_instructions }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Packaging Notes</label>
                            <input type="text" name="packaging_notes" class="form-control form-control-sm" value="{{ $style->techPack?->packaging_notes }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Drawcord Notes</label>
                            <input type="text" name="drawcord_notes" class="form-control form-control-sm" value="{{ $style->techPack?->drawcord_notes }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Seam Notes</label>
                            <input type="text" name="seam_notes" class="form-control form-control-sm" value="{{ $style->techPack?->seam_notes }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Thread Notes</label>
                            <input type="text" name="thread_notes" class="form-control form-control-sm" value="{{ $style->techPack?->thread_notes }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Remarks</label>
                            <textarea name="remarks" class="form-control form-control-sm" rows="2">{{ $style->techPack?->remarks }}</textarea>
                        </div>

                        {{-- Tech Pack PDF Upload --}}
                        <div class="col-12 border-top pt-3 mt-1">
                            <label class="form-label fw-bold">
                                <i class="fa-solid fa-file-pdf text-danger me-1"></i> Tech Pack PDF (All Details Report)
                            </label>

                            @if($style->techPack?->pdf_path)
                                <div id="existing_techpack_pdf" class="d-flex align-items-center gap-3 p-3 bg-light rounded border mb-2">
                                    <i class="fa-solid fa-file-pdf text-danger fs-2"></i>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-gray-800 fs-7">{{ $style->techPack->pdf_path }}</div>
                                        <div class="text-muted fs-9">Currently uploaded tech pack PDF</div>
                                    </div>
                                    <a href="{{ asset('uploads/styles/techpacks/' . $style->techPack->pdf_path) }}" target="_blank" class="btn btn-sm btn-light-primary border px-2 py-1" title="View PDF">
                                        <i class="fa-solid fa-eye fs-8"></i>
                                    </a>
                                    <a href="{{ asset('uploads/styles/techpacks/' . $style->techPack->pdf_path) }}" download class="btn btn-sm btn-light-info border px-2 py-1" title="Download PDF">
                                        <i class="fa-solid fa-download fs-8"></i>
                                    </a>
                                </div>
                            @endif

                            <input type="file" name="tech_pack_pdf" id="tech_pack_pdf_input" class="form-control form-control-sm form-control-solid border" accept=".pdf">
                            <div class="form-text fs-9 text-muted mt-1">
                                <i class="fa-solid fa-circle-info text-info me-1"></i>
                                Upload PDF up to 20MB. {{ $style->techPack?->pdf_path ? 'Uploading a new file will replace the existing one.' : '' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Update Tech Pack</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 6. ARTWORK MODAL (Add & Edit) -->
<div class="modal fade" id="addArtworkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form_save_artwork" action="{{ route('styles.artworks.store', $style->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="artwork_form_method" value="POST">
                <input type="hidden" name="artwork_type_id" id="art_artwork_type_id" value="">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="artwork_modal_title">Add Artwork Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold required">Artwork Name</label>
                            <input type="text" name="artwork_name" id="art_name" class="form-control form-control-sm" placeholder="e.g. Front Screen Print Logo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Artwork Code</label>
                            <input type="text" name="artwork_code" id="art_code" class="form-control form-control-sm" placeholder="AWFC-01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Artwork Type</label>
                            <select name="type" id="art_type" class="form-select form-select-sm" required>
                                <option value="">Select Artwork Type</option>
                                @foreach($artworkTypes ?? [] as $at)
                                    <option value="{{ $at->name }}" data-id="{{ $at->id }}" data-code="{{ $at->code }}">{{ $at->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Placement</label>
                            <input type="text" name="placement" id="art_placement" class="form-control form-control-sm" placeholder="e.g. Center Chest">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dimensions</label>
                            <input type="text" name="dimensions" id="art_dimensions" class="form-control form-control-sm" placeholder="e.g. 10 inch W x 4 inch H">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Artwork File</label>
                            <input type="file" name="artwork_file" id="art_file" class="form-control form-control-sm" accept=".ai,.psd,.pdf,.svg,.png,.jpg,.jpeg,.eps,.zip">
                            <div class="form-text fs-9 text-muted">Upload vector or graphics artwork (AI, PSD, PDF, SVG, EPS, PNG, JPG, ZIP). Max 100MB.</div>
                            <div id="art_file_preview_box" class="d-none mt-2 p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2 text-truncate">
                                    <i class="fa-solid fa-file-arrow-down text-primary fs-5"></i>
                                    <div class="text-truncate">
                                        <div class="fw-semibold text-gray-800 fs-8 text-truncate" id="art_current_file_name"></div>
                                        <span class="text-muted fs-9">Current attached artwork file</span>
                                    </div>
                                </div>
                                <a href="#" id="art_file_download_btn" target="_blank" class="btn btn-xs btn-outline btn-outline-primary py-1 px-3 fs-9 flex-shrink-0">
                                    <i class="fa-solid fa-download me-1"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Save Artwork</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 7. EDIT COSTING SETTINGS MODAL -->
<div class="modal fade" id="editCostingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form_edit_costing" action="{{ route('styles.costing.update', $style->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Costing Margins</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Currency</label>
                            <select name="currency" class="form-select form-select-sm" required>
                                <option value="USD" {{ ($style->costing?->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="LKR" {{ ($style->costing?->currency) == 'LKR' ? 'selected' : '' }}>LKR (Rs)</option>
                                <option value="EUR" {{ ($style->costing?->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Overhead %</label>
                            <input type="number" step="0.01" name="overhead_percent" class="form-control form-control-sm" value="{{ $style->costing?->overhead_percent ?? 10.00 }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Profit %</label>
                            <input type="number" step="0.01" name="profit_percent" class="form-control form-control-sm" value="{{ $style->costing?->profit_percent ?? 15.00 }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Target Price (Optional)</label>
                            <input type="number" step="0.01" name="target_price" class="form-control form-control-sm" value="{{ $style->costing?->target_price }}" placeholder="Auto-calculated if empty">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Update Costing</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 8. UPLOAD FILE MODAL -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="form_upload_file" action="{{ route('styles.files.upload', $style->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Upload Style Document
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7 p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">File Type <span class="text-danger">*</span></label>
                            <select name="file_type_id" id="modal_upload_file_type_id" class="form-select form-input-custom" required>
                                <option value="">-- Select File Type --</option>
                                @foreach($fileTypes ?? [] as $ft)
                                    <option value="{{ $ft->id }}" data-desc="{{ $ft->description }}" data-code="{{ $ft->code }}">
                                        {{ $ft->name }}{{ $ft->code ? ' (' . $ft->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            
                            {{-- Dynamic Description Box --}}
                            <div id="modal_file_type_desc_container" class="alert alert-light-primary border border-primary-subtle p-3 mt-2 rounded d-none">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fa-solid fa-circle-info text-primary mt-1"></i>
                                    <div>
                                        <span class="fw-bold text-gray-800 fs-8 d-block mb-1">File Type Description:</span>
                                        <span class="text-gray-700 fs-9" id="modal_file_type_desc_text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Select File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control form-input-custom" required>
                            <div class="form-text fs-9 text-muted">Supported formats: PDF, Word, Excel, Images, Zip, CAD (Max: 50MB).</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Notes / Document Description</label>
                            <textarea name="description" class="form-control form-input-custom" rows="2" placeholder="e.g. Final approved revision, signed by buyer representative..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-sm btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 9. APPROVAL GATE MODAL (Add & Edit) -->
<div class="modal fade" id="addApprovalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form_save_approval" action="{{ route('styles.approvals.store', $style->id) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="approval_form_method" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="approval_modal_title">Add Approval Sign-off Gate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold required">Gate Name</label>
                            <input type="text" name="gate_name" id="app_gate_name" class="form-control form-control-sm" placeholder="e.g. PP Sample Sign-off" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold required">Status</label>
                            <select name="status" id="app_status" class="form-select form-select-sm" required>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Approved By</label>
                            <input type="text" name="approved_by" id="app_approved_by" class="form-control form-control-sm" placeholder="Sign-off person name">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Sign-off Remarks</label>
                            <textarea name="remarks" id="app_remarks" class="form-control form-control-sm" rows="2" placeholder="Approval notes"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold">Save Gate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 10. QUICK UPLOAD STYLE IMAGE MODAL -->
<div class="modal fade" id="quickUploadImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Upload Style Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickUploadImageForm" action="{{ route('styles.images.upload', $style->id) }}" method="POST" enctype="multipart/form-data">
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

<!-- 11. LIGHTBOX / IMAGE VIEWER MODAL -->
@include('styles.partials.image-viewer-modal')

{{-- 12. UPLOAD TECH PACK DOCUMENT BY TYPE MODAL --}}
<div class="modal fade" id="uploadTechPackDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="form_upload_tech_pack_document" action="{{ route('styles.tech-pack-documents.upload', $style->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-gray-900">
                        <i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i> Upload Tech Pack Document
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7 p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Tech Pack Type <span class="text-danger">*</span></label>
                            <select name="tech_pack_type_id" id="modal_tech_pack_type_id" class="form-select form-input-custom" required>
                                <option value="">Select Tech Pack Type</option>
                                @foreach($techPackTypes ?? [] as $tpt)
                                    <option value="{{ $tpt->id }}">{{ $tpt->name }} {{ $tpt->code ? '('.$tpt->code.')' : '' }}</option>
                                @endforeach
                            </select>
                            <div class="form-text fs-9 text-muted">Select the type or category of this tech pack document.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Document Title / Name</label>
                            <input type="text" name="document_name" class="form-control form-input-custom" placeholder="e.g. Master Tech Pack Rev 2 (optional)">
                            <div class="form-text fs-9 text-muted">Leave blank to use the uploaded file's original name.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Select File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control form-input-custom" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg,.svg,.zip">
                            <div class="form-text fs-9 text-muted">Supported formats: PDF, Word, Excel, Images, Zip (Max: 30MB).</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Notes / Version Remarks</label>
                            <textarea name="notes" class="form-control form-input-custom" rows="2" placeholder="e.g. Approved by buyer, updated measurements for grading..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-sm btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btn_submit_tech_pack_doc" class="btn btn-sm btn-primary px-4 fw-bold">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 13. UPLOAD SAMPLE PATTERN MODAL -->
<div class="modal fade" id="uploadSamplePatternModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="form_upload_sample_pattern" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-shapes text-primary me-2"></i> Upload Sample Pattern File
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fs-7 p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 bg-light rounded border d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fs-9 d-block">Target Sample Record</span>
                                    <div class="fw-bold text-gray-900 fs-7" id="modal_pattern_sample_name">Sample</div>
                                </div>
                                <span class="badge badge-light-primary fw-bold" id="modal_pattern_tag_badge">Stage</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Pattern File Type</label>
                            <select name="pattern_file_type_id" id="modal_pattern_file_type_id" class="form-select form-input-custom">
                                <option value="">Select Pattern File Type</option>
                                @foreach($patternFileTypes ?? [] as $pft)
                                    <option value="{{ $pft->id }}">{{ $pft->name }} {{ $pft->code ? '('.$pft->code.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Pattern File <span class="text-danger">*</span></label>
                            <input type="file" name="pattern_file" id="modal_pattern_file_input" class="form-control form-input-custom" required accept=".dxf,.zip,.rar,.pdf,.ai,.pds,.mdl,.vet,.zprj,.bw,.tmp,.txt,.cad">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-gray-700 small">Pattern Notes / Remarks</label>
                            <textarea name="pattern_notes" id="modal_pattern_notes" class="form-control form-input-custom" rows="2" placeholder="e.g. Master base size M with graded nesting, seam allowance included..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-sm btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btn_submit_sample_pattern" class="btn btn-sm btn-primary px-4 fw-bold">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Pattern
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


