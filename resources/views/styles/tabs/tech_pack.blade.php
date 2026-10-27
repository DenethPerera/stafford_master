<div class="row g-6 mb-6">
    <!-- Left: Specifications Table -->
    <div class="col-xl-7 col-lg-6">
        <div class="card card-flush shadow-sm border mb-6">
            <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">Specification Details</h3>
                <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#editTechPackModal">
                    <i class="fa-solid fa-pen fs-8 me-1"></i> Edit Tech Pack
                </button>
            </div>
            <div class="card-body pt-2">
                <table class="table table-bordered table-sm align-middle fs-7 mb-0">
                    <thead class="bg-light text-gray-700 fw-bold fs-8 text-uppercase">
                        <tr>
                            <th class="w-180px ps-3">Specification</th>
                            <th class="ps-3">Details</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-800">
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Fitting</td><td class="ps-3">{{ $style->techPack?->fitting ?: 'N/A' }}</td></tr>
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Stitching</td><td class="ps-3">{{ $style->techPack?->stitching ?: 'N/A' }}</td></tr>
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Label & Branding</td><td class="ps-3">{{ $style->techPack?->label_branding ?: 'N/A' }}</td></tr>
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Care Instructions</td><td class="ps-3">{{ $style->techPack?->care_instructions ?: 'N/A' }}</td></tr>
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Packaging Notes</td><td class="ps-3">{{ $style->techPack?->packaging_notes ?: 'N/A' }}</td></tr>
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Drawcord Notes</td><td class="ps-3">{{ $style->techPack?->drawcord_notes ?: 'N/A' }}</td></tr>
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Seam Notes</td><td class="ps-3">{{ $style->techPack?->seam_notes ?: 'N/A' }}</td></tr>
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Thread Notes</td><td class="ps-3">{{ $style->techPack?->thread_notes ?: 'N/A' }}</td></tr>
                        <tr><td class="ps-3 fw-bold bg-light-subtle text-muted">Remarks</td><td class="ps-3">{{ $style->techPack?->remarks ?: 'N/A' }}</td></tr>
            
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card: Tech Pack Documents Classified by Type -->
        <div class="card card-flush shadow-sm border mb-6">
            <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                        <i class="fa-solid fa-folder-tree text-primary me-2"></i> Tech Pack Documents
                    </h3>
                    <span class="text-muted fs-8">Documents classified by Tech Pack Type</span>
                </div>
                <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#uploadTechPackDocumentModal">
                    <i class="fa-solid fa-cloud-arrow-up me-1 fs-8"></i> Upload Document
                </button>
            </div>
            <div class="card-body pt-2">
                @php
                    $docs = $style->techPackDocuments ?? collect();
                @endphp

                @if($docs->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle fs-7 mb-0">
                            <thead class="bg-light text-gray-700 fw-bold fs-8 text-uppercase">
                                <tr>
                                    <th class="ps-3 w-160px">Tech Pack Type</th>
                                    <th>Document</th>
                                    <th class="w-80px text-center">Size</th>
                                    <th class="w-110px text-center">Uploaded</th>
                                    <th class="w-100px text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-800">
                                @foreach($docs as $doc)
                                    @php
                                        $ext = strtoupper($doc->file_type);
                                        $iconClass = 'fa-file text-secondary';
                                        if (in_array($ext, ['PDF'])) {
                                            $iconClass = 'fa-file-pdf text-danger';
                                        } elseif (in_array($ext, ['XLS', 'XLSX', 'CSV'])) {
                                            $iconClass = 'fa-file-excel text-success';
                                        } elseif (in_array($ext, ['DOC', 'DOCX'])) {
                                            $iconClass = 'fa-file-word text-primary';
                                        } elseif (in_array($ext, ['PNG', 'JPG', 'JPEG', 'SVG', 'WEBP'])) {
                                            $iconClass = 'fa-file-image text-info';
                                        } elseif (in_array($ext, ['ZIP', 'RAR', '7Z'])) {
                                            $iconClass = 'fa-file-zipper text-warning';
                                        }
                                        $fileUrl = asset('uploads/styles/techpacks/documents/' . $doc->file_path);
                                    @endphp
                                    <tr>
                                        <td class="ps-3 align-middle">
                                            <span class="badge badge-light-primary fw-bold fs-8 text-truncate d-inline-block" style="max-width: 150px;" title="{{ $doc->techPackType?->name ?: $doc->type_name ?: 'General' }}">
                                                {{ $doc->techPackType?->name ?: $doc->type_name ?: 'General' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid {{ $iconClass }} fs-4"></i>
                                                <div>
                                                    <a href="{{ $fileUrl }}" target="_blank" class="fw-bold text-gray-900 text-hover-primary fs-7 d-block text-truncate" style="max-width: 260px;" title="{{ $doc->document_name }}">
                                                        {{ $doc->document_name }}
                                                    </a>
                                                    @if($doc->notes)
                                                        <div class="text-muted fs-9 text-truncate" style="max-width: 260px;" title="{{ $doc->notes }}">
                                                            {{ $doc->notes }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle text-muted fs-8">
                                            {{ $doc->file_size ?: '—' }}
                                        </td>
                                        <td class="text-center align-middle text-muted fs-8">
                                            {{ $doc->created_at ? $doc->created_at->format('d M Y') : '—' }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-icon btn-sm btn-light-primary border w-28px h-28px rounded-2" title="View Document">
                                                    <i class="fa-solid fa-eye fs-9"></i>
                                                </a>
                                                <a href="{{ $fileUrl }}" download class="btn btn-icon btn-sm btn-light-success border w-28px h-28px rounded-2" title="Download Document">
                                                    <i class="fa-solid fa-download fs-9"></i>
                                                </a>
                                                <button type="button" class="btn btn-icon btn-sm btn-light-danger border w-28px h-28px rounded-2 btn-delete-techpack-doc" data-doc-id="{{ $doc->id }}" data-doc-name="{{ $doc->document_name }}" title="Delete Document">
                                                    <i class="fa-solid fa-trash-can fs-9"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6 px-4 bg-light rounded border border-dashed">
                        <i class="fa-solid fa-folder-open text-gray-400 fs-1 mb-2 d-block"></i>
                        <span class="text-gray-700 fw-bold d-block fs-7">No Tech Pack documents uploaded yet</span>
                        <span class="text-muted fs-8 d-block mb-3">Upload specification sheets, BOM PDFs, measurement charts classified by Tech Pack Type.</span>
                        <button type="button" class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#uploadTechPackDocumentModal">
                            <i class="fa-solid fa-plus me-1"></i> Upload First Document
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right: Technical Vector Sketch & 4 BOM Summary Metrics -->
    <div class="col-xl-5 col-lg-6">
        <!-- 4 BOM Metric Summary Cards (2x2 Grid) -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card card-flush shadow-xs border bg-light-primary p-3 text-center">
                    <div class="fs-2x fw-extrabold text-primary mb-0">{{ $style->bomItems->count() }}</div>
                    <div class="text-gray-600 fs-9 fw-bold text-uppercase">Total Components</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-flush shadow-xs border bg-light-success p-3 text-center">
                    <div class="fs-2x fw-extrabold text-success mb-0">{{ number_format($style->bomItems->where('component_type', 'Fabric')->sum('qty_per_size'), 2) }}</div>
                    <div class="text-gray-600 fs-9 fw-bold text-uppercase">Fabric Qty</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-flush shadow-xs border bg-light-warning p-3 text-center">
                    <div class="fs-2x fw-extrabold text-warning mb-0">{{ $style->bomItems->where('component_type', '!=', 'Fabric')->count() }}</div>
                    <div class="text-gray-600 fs-9 fw-bold text-uppercase">Total Trims/Labels</div>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-flush shadow-xs border bg-light-info p-3 text-center">
                    <div class="fs-2x fw-extrabold text-info mb-0">${{ number_format($style->bomItems->sum(fn($i) => $i->total_cost ?: ($i->qty_per_size * $i->unit_cost)), 2) }}</div>
                    <div class="text-gray-600 fs-9 fw-bold text-uppercase">Total BOM Cost</div>
                </div>
            </div>
        </div>

        <!-- Technical Vector Sketch Card -->
        <div class="card card-flush shadow-sm border text-center p-5">
            <h5 class="fw-bold text-gray-700 fs-7 text-uppercase mb-3">TECHNICAL VECTOR SKETCH</h5>
            <div class="bg-light rounded p-4 border d-flex align-items-center justify-content-center" style="min-height: 210px;">
                @if($primaryImg = $style->images->first())
                    <img src="{{ asset('uploads/styles/' . $primaryImg->image_path) }}" alt="Technical Vector" class="img-fluid max-h-200px rounded shadow-xs">
                @else
                    <svg width="180" height="190" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M60 40 Q100 20 140 40 L170 80 L145 90 L140 170 L60 170 L55 90 L30 80 Z" stroke="#334155" stroke-width="2.5" fill="none"/>
                        <path d="M80 40 C80 15, 120 15, 120 40" stroke="#334155" stroke-width="2" fill="none"/>
                        <path d="M75 120 L125 120 L120 150 L80 150 Z" stroke="#334155" stroke-width="1.5" fill="none"/>
                        <path d="M60 160 L140 160" stroke="#334155" stroke-width="2"/>
                    </svg>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- BOM Summary Breakdown Component Table -->
<div class="card card-flush shadow-sm border">
    <div class="card-header pt-5 border-0">
        <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">BOM Components Breakdown</h3>
    </div>
    <div class="card-body pt-2">
        <div class="table-responsive">
            <table class="table table-row-dashed table-sm align-middle fs-7 gy-2 mb-0">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase bg-light">
                        <th class="ps-3 w-40px">#</th>
                        <th>Component Type</th>
                        <th>Item / Description</th>
                        <th>Supplier</th>
                        <th>Placement</th>
                        <th>Color</th>
                        <th>Qty / Size</th>
                        <th>UOM</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-800">
                    @forelse($style->bomItems as $index => $item)
                        <tr>
                            <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                            <td><span class="badge badge-light-primary fs-9">{{ $item->component_type }}</span></td>
                            <td class="fw-bold">{{ $item->item_description }}</td>
                            <td>{{ $item->supplier ?: '—' }}</td>
                            <td>{{ $item->placement ?: '—' }}</td>
                            <td>{{ $item->color ?: '—' }}</td>
                            <td>{{ number_format($item->qty_per_size, 2) }}</td>
                            <td>{{ $item->uom }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No BOM items defined yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
