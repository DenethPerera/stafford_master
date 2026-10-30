<!-- Card: Tech Pack Documents Classified by Type -->
<div class="card card-flush shadow-sm border mb-6">
    <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                <i class="fa-solid fa-folder-tree text-primary me-2"></i> Tech Pack Documents
            </h3>
            <span class="text-muted fs-8">Technical specification sheets, CAD files, measurement charts, and documents classified by Tech Pack Type</span>
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
                            <th class="ps-3 w-50px text-center">#</th>
                            <th class="ps-3 w-180px">Tech Pack Type</th>
                            <th>Document</th>
                            <th class="w-100px text-center">Size</th>
                            <th class="w-130px text-center">Uploaded</th>
                            <th class="w-120px text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-800">
                        @foreach($docs as $index => $doc)
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
                                <td class="ps-3 text-center align-middle text-muted fs-8">
                                    {{ $index + 1 }}
                                </td>
                                <td class="ps-3 align-middle">
                                    <span class="badge badge-light-primary fw-bold fs-8 text-truncate d-inline-block" style="max-width: 170px;" title="{{ $doc->techPackType?->name ?: $doc->type_name ?: 'General' }}">
                                        {{ $doc->techPackType?->name ?: $doc->type_name ?: 'General' }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid {{ $iconClass }} fs-4"></i>
                                        <div>
                                            <a href="{{ $fileUrl }}" target="_blank" class="fw-bold text-gray-900 text-hover-primary fs-7 d-block text-truncate" style="max-width: 450px;" title="{{ $doc->document_name }}">
                                                {{ $doc->document_name }}
                                            </a>
                                            @if($doc->notes)
                                                <div class="text-muted fs-9 text-truncate" style="max-width: 450px;" title="{{ $doc->notes }}">
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
            <div class="text-center py-10 px-4 bg-light rounded border border-dashed">
                <i class="fa-solid fa-folder-open text-gray-400 fs-2tx mb-3 d-block"></i>
                <h5 class="text-gray-800 fw-bold d-block fs-6 mb-1">No Tech Pack Documents Uploaded</h5>
                <p class="text-muted fs-8 d-block mb-4 max-w-500px mx-auto">Upload technical specification sheets, CAD sketches, measurement charts, or tech pack PDFs classified by Tech Pack Type.</p>
                <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#uploadTechPackDocumentModal">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload First Document
                </button>
            </div>
        @endif
    </div>
</div>
