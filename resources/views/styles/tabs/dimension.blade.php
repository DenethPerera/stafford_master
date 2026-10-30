<div class="card card-flush shadow-sm border">
    <div class="card-body p-5">
       
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <!-- Left: Dimension Filter & Export -->
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="fw-bold text-gray-800 fs-7">Product Dimensions:</span>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check form-check-custom form-check-solid form-check-sm mb-0">
                        <input class="form-check-input dimension-type-filter" type="radio" name="dimension_type_filter" id="dim_filter_solid" value="solid" checked>
                        <label class="form-check-label fw-bold text-gray-800 fs-7 cursor-pointer" for="dim_filter_solid">Solid</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid form-check-sm mb-0">
                        <input class="form-check-input dimension-type-filter" type="radio" name="dimension_type_filter" id="dim_filter_ratio" value="ratio">
                        <label class="form-check-label fw-bold text-gray-800 fs-7 cursor-pointer" for="dim_filter_ratio">Ratio</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid form-check-sm mb-0">
                        <input class="form-check-input dimension-type-filter" type="radio" name="dimension_type_filter" id="dim_filter_all" value="all">
                        <label class="form-check-label fw-bold text-gray-800 fs-7 cursor-pointer" for="dim_filter_all">All</label>
                    </div>
                </div>
                <a href="{{ route('styles.dimensions.export', [$style->id, 'type' => 'solid']) }}" 
                   class="btn btn-sm btn-light border border-gray-300 py-1.5 px-3 fw-bold fs-8 text-gray-700 shadow-2xs d-inline-flex align-items-center gap-1.5 ms-1" 
                   id="btn_dimension_export_csv"
                   title="Export to CSV">
                    <i class="fa-regular fa-file-lines text-muted fs-8"></i> Save As Csv
                </a>
            </div>

            <!-- Right: Upload Dimensions Link & Add Dimension Button -->
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-primary fw-bold fs-7 text-hover-underline d-inline-flex align-items-center gap-1.5" 
                   data-bs-toggle="modal" data-bs-target="#uploadCartonDimensionModal" id="btn_open_upload_dimension">
                    Upload Product Dimensions <i class="fa-solid fa-pen-to-square fs-7 text-primary"></i>
                </a>
                <button type="button" class="btn btn-sm btn-primary fw-bold py-1.5 px-3 fs-8 d-inline-flex align-items-center gap-1" 
                        data-bs-toggle="modal" data-bs-target="#addCartonDimensionModal" id="btn_open_add_dimension">
                    <i class="fa-solid fa-plus fs-9"></i> Add Dimension
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-responsive">
            <table class="table table-row-bordered table-row-gray-200 align-middle fs-7 gy-2 mb-0" id="table_carton_dimensions" style="width: 100%;">
                <thead class="border-bottom border-gray-300 bg-light-subtle">
                    <tr class="text-start text-gray-700 fw-bolder fs-7 text-nowrap">
                        <th class="ps-3 w-40px">#</th>
                        <th>Size</th>
                        <th>Pcs/Ctn</th>
                        <th>Length</th>
                        <th>Width</th>
                        <th>Height</th>
                        <th>Kgs/Pcs</th>
                        <th>Carton Weight</th>
                        <th>CBM/Carton</th>
                        <th>Carton Size</th>
                        <th class="text-end pe-3 w-90px">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-800" id="carton_dimensions_tbody">
                    @php
                        $dimensions = $style->cartonDimensions ?: collect();
                    @endphp
                    @forelse($dimensions as $index => $item)
                        @php
                            $isRatio = (int)$item->ratio_pack_carton === 1;
                            $packType = $isRatio ? 'ratio' : 'solid';
                        @endphp
                        <tr class="dimension-row align-middle" data-pack-type="{{ $packType }}" data-id="{{ $item->idtbl_carton_dimension }}">
                            <td class="ps-3 text-muted fw-bold row-index">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-light-primary text-primary fw-bold fs-8 px-2 py-1">{{ $item->tbl_style_size_desc }}</span>
                                @if($isRatio)
                                    <span class="badge bg-light-warning text-warning fs-9 ms-1">Ratio</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $item->pcs_per_carton }}</td>
                            <td>{{ number_format($item->carton_length, 2) }}</td>
                            <td>{{ number_format($item->carton_width, 2) }}</td>
                            <td>{{ number_format($item->carton_height, 2) }}</td>
                            <td>{{ number_format($item->item_weight, 3) }}</td>
                            <td>{{ number_format($item->carton_weight, 2) }}</td>
                            <td class="fw-bold text-primary">{{ number_format($item->cbm_per_carton, 3) }}</td>
                            <td class="fw-bold text-gray-900">{{ number_format($item->carton_volume, 2) }}</td>
                            <td class="text-end pe-3 text-nowrap">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-light-warning h-26px w-26px btn-edit-dimension"
                                            data-dimension="{{ json_encode($item) }}" title="Edit Dimension">
                                        <i class="fa-solid fa-pen fs-9"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger h-26px w-26px btn-delete-dimension"
                                            data-id="{{ $item->idtbl_carton_dimension }}" title="Delete Dimension">
                                        <i class="fa-solid fa-trash fs-9"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty_carton_dimension_row">
                            <td colspan="11" class="p-0 border-0">
                                <div class="text-center py-2 text-white fw-semibold fs-7">
                                    No matching records found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Dynamic Filter Empty Banner-->
        <div id="filter_no_records_banner" class="mt-0 {{ $dimensions->isNotEmpty() ? 'd-none' : 'd-none' }}">
            <div class="text-center py-2 text-white fw-semibold fs-7 rounded-bottom">
                No matching records found
            </div>
        </div>
    </div>
</div>

