<div class="card card-flush shadow-sm border mb-6">
    <div class="card-header pt-5 border-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                <i class="fa-solid fa-folder-open text-primary me-2"></i> STYLE DOCUMENTS & FILES
            </h3>
            <span class="text-muted fs-8">Organized technical documents and attachments classified by master File Type</span>
        </div>
        
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <select id="file_type_filter" class="form-select form-select-sm w-200px form-select-solid fw-bold text-gray-800 no-select2">
                <option value="">All File Types</option>
                @foreach($fileTypes ?? [] as $ft)
                    <option value="{{ strtolower($ft->name) }}">{{ $ft->name }}</option>
                @endforeach
            </select>

            <div class="w-200px position-relative">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-gray-400 fs-8"></i>
                <input type="text" id="file_search_input" class="form-control form-control-sm form-control-solid ps-8 fs-8" placeholder="Search Files..." />
            </div>

            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
                <i class="fa-solid fa-cloud-arrow-up fs-8 me-1"></i> Upload Document
            </button>
        </div>
    </div>
    <div class="card-body pt-2">
        <div class="table-responsive">
            <table class="table table-row-dashed table-sm align-middle fs-7 gy-3 mb-0" id="style_files_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase bg-light">
                        <th class="ps-3 w-30px">#</th>
                        <th class="min-w-220px">Document Name</th>
                        <th class="min-w-260px">File Type & Specification Description</th>
                        <th class="w-80px">Ext</th>
                        <th class="w-90px">Size</th>
                        <th class="min-w-130px">Uploaded By</th>
                        <th class="min-w-130px">Uploaded On</th>
                        <th class="text-end pe-3 w-90px">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-800">
                    @forelse($style->files as $index => $df)
                        @php
                            $ext = strtoupper($df->file_type);
                            $iconClass = match(true) {
                                in_array($ext, ['PDF']) => 'fa-file-pdf text-danger',
                                in_array($ext, ['PNG', 'JPG', 'JPEG', 'WEBP', 'GIF', 'SVG']) => 'fa-file-image text-info',
                                in_array($ext, ['XLS', 'XLSX', 'CSV']) => 'fa-file-excel text-success',
                                in_array($ext, ['DOC', 'DOCX']) => 'fa-file-word text-primary',
                                in_array($ext, ['ZIP', 'RAR', '7Z']) => 'fa-file-zipper text-warning',
                                in_array($ext, ['AI', 'PSD', 'EPS']) => 'fa-file-code text-purple',
                                in_array($ext, ['DXF', 'PDS', 'MDL']) => 'fa-bezier-curve text-primary',
                                default => 'fa-file text-gray-500',
                            };
                            $typeName = $df->fileType ? $df->fileType->name : 'General File';
                            $typeDesc = $df->fileType ? $df->fileType->description : '';
                        @endphp
                        <tr class="file-row" 
                            data-type="{{ strtolower($typeName) }}"
                            data-name="{{ strtolower($df->file_name) }} {{ strtolower($df->description ?? '') }}">
                            <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid {{ $iconClass }} fs-4 me-2 flex-shrink-0"></i>
                                    <div>
                                        <div class="fw-bold text-gray-900 fs-7">{{ $df->file_name }}</div>
                                        @if($df->description)
                                            <div class="text-muted fs-9 mt-0.5">{{ $df->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($df->fileType)
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge badge-light-primary fw-bold fs-9">
                                                {{ $df->fileType->name }}
                                            </span>
                                            @if($df->fileType->code)
                                                <span class="badge badge-light-secondary font-monospace fs-9 py-0 px-1 border">{{ $df->fileType->code }}</span>
                                            @endif
                                        </div>
                                        @if($df->fileType->description)
                                            <div class="text-gray-600 fs-9 mt-1 line-clamp-2" title="{{ $df->fileType->description }}">
                                                <i class="fa-solid fa-circle-info text-info me-1 fs-9"></i>{{ $df->fileType->description }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge badge-light-secondary fs-9">General / Unclassified</span>
                                @endif
                            </td>
                            <td><span class="badge badge-light-secondary fs-9 font-monospace">{{ $df->file_type }}</span></td>
                            <td class="text-muted fs-8 font-monospace">{{ $df->file_size }}</td>
                            <td class="fs-8 text-gray-700">{{ $df->uploaded_by ?: 'System' }}</td>
                            <td class="text-muted fs-8">{{ $df->created_at ? $df->created_at->format('d M Y, h:i A') : '—' }}</td>
                            <td class="text-end pe-3 text-nowrap">
                                <a href="{{ asset('uploads/styles/files/' . $df->file_path) }}" target="_blank" download class="btn btn-sm btn-light btn-icon me-1" title="Download File">
                                    <i class="fa-solid fa-download fs-8 text-primary"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-light btn-icon btn-delete-file" data-id="{{ $df->id }}" title="Delete File">
                                    <i class="fa-solid fa-trash fs-8 text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-6">
                                <i class="fa-solid fa-folder-open fs-2x text-gray-400 d-block mb-2"></i>
                                <span class="d-block fw-bold text-gray-700 fs-7">No documents uploaded yet</span>
                                <span class="fs-8 text-muted">Click "Upload Document" to attach files classified by master File Type.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($style->files->count() > 0)
            <div class="text-muted fs-8 mt-3 text-end">Total {{ $style->files->count() }} Files uploaded</div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Filter Files table by File Type and search query
        function applyFilesFilter() {
            var type = $('#file_type_filter').val();
            var search = $('#file_search_input').val().toLowerCase();

            $('.file-row').each(function() {
                var rowType = $(this).data('type') || '';
                var rowName = $(this).data('name') || '';

                var matchType = !type || rowType === type;
                var matchSearch = !search || rowName.indexOf(search) !== -1;

                if (matchType && matchSearch) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('#file_type_filter').on('change', applyFilesFilter);
        $('#file_search_input').on('keyup', applyFilesFilter);

        // Show File Type description dynamically in the Upload modal
        $('#modal_upload_file_type_id').on('change', function() {
            var selected = $(this).find('option:selected');
            var desc = selected.data('desc');

            if (desc) {
                $('#modal_file_type_desc_text').text(desc);
                $('#modal_file_type_desc_container').removeClass('d-none');
            } else {
                $('#modal_file_type_desc_container').addClass('d-none');
            }
        });
    });
</script>
@endpush
