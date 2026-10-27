<div class="card card-flush shadow-sm border">
    <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                <i class="fa-solid fa-palette text-primary me-2"></i>COLORS & COLORWAYS
            </h3>
            <div class="text-muted fs-8 mt-1">Manage color swatches, Pantone standards, and lab dip references for this style</div>
        </div>
        <button type="button" class="btn btn-sm btn-primary fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#addColorwayModal" id="btn_open_add_colorway">
            <i class="fa-solid fa-plus fs-8 me-1"></i> Add Colorway
        </button>
    </div>
    <div class="card-body pt-3">
        <div class="row g-4">
            @forelse($style->colorways as $c)
                @php
                    $hex = $c->swatch_color_hex ?: '#1e293b';
                    $statusClass = match(strtolower($c->status ?? 'approved')) {
                        'approved' => 'badge-light-success text-success',
                        'pending'  => 'badge-light-warning text-warning',
                        'rejected' => 'badge-light-danger text-danger',
                        default    => 'badge-light-primary text-primary',
                    };
                @endphp
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="card border shadow-xs h-100 overflow-hidden hover-elevation-md transition-all">
                        <!-- Swatch Graphic Preview Header -->
                        <div class="h-140px d-flex align-items-center justify-content-center p-3 text-white fw-bold shadow-inner position-relative" style="background-color: {{ $hex }};">
                            <i class="fa-solid fa-shirt fs-3x opacity-60"></i>
                            
                            <!-- Hex Tag Overlay -->
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <span class="badge bg-dark bg-opacity-75 text-white font-monospace fs-9 fw-normal px-2 py-1 shadow-sm">
                                    <span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background-color: {{ $hex }}; border: 1px solid rgba(255,255,255,0.6);"></span>
                                    {{ strtoupper($hex) }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="position-absolute top-0 end-0 p-2 d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-icon btn-light h-28px w-28px shadow-xs btn-edit-colorway" data-colorway="{{ json_encode($c) }}" title="Edit Colorway">
                                    <i class="fa-solid fa-pen fs-9 text-gray-700"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-light-danger h-28px w-28px shadow-xs btn-delete-colorway" data-id="{{ $c->id }}" title="Delete Colorway">
                                    <i class="fa-solid fa-trash fs-9"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Card Details Body -->
                        <div class="card-body p-4 fs-7 bg-white d-flex flex-column justify-content-between">
                            <div>
                                <div class="fw-bold text-gray-900 fs-6 mb-2 text-truncate" title="{{ $c->color_name }}">
                                    {{ $c->color_name }}
                                </div>

                                <!-- Pantone Code Badge -->
                                <div class="mb-2">
                                    @if(!empty($c->pantone_code))
                                        <span class="badge bg-light-primary text-primary border border-primary-subtle fs-8 fw-bold px-2 py-1 d-inline-flex align-items-center gap-1 shadow-2xs">
                                            <i class="fa-solid fa-swatchbook fs-9"></i> {{ $c->pantone_code }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted fs-9 fw-normal px-2 py-1">
                                            <i class="fa-solid fa-minus fs-9 me-1"></i> No Pantone Code
                                        </span>
                                    @endif
                                </div>

                                <!-- Lab Dip Reference -->
                                <div class="text-muted fs-8 mb-3">
                                    <i class="fa-solid fa-flask fs-9 me-1 text-gray-400"></i>
                                    {{ $c->lab_op_ref ?: 'No Lab Dip Ref' }}
                                </div>
                            </div>

                            <!-- Status Footer -->
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                                <span class="text-muted fs-9">Approval Status:</span>
                                <span class="badge {{ $statusClass }} fs-9 fw-bold px-2 py-1">
                                    {{ $c->status ?: 'Approved' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-8 bg-light rounded border border-dashed">
                        <i class="fa-solid fa-palette fs-3x text-secondary mb-2 d-block opacity-50"></i>
                        <div class="fw-bold fs-6 text-gray-700 mb-1">No Colorways Added Yet</div>
                        <p class="fs-8 text-muted mb-3">Add style colors with Pantone codes, hex swatches, and lab dip approvals.</p>
                        <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addColorwayModal">
                            <i class="fa-solid fa-plus me-1"></i> Add First Colorway
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
