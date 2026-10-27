<div class="card card-flush shadow-sm border mb-6">
    <div class="card-header pt-5 border-0 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                <i class="fa-solid fa-shapes text-primary me-2"></i> Sample Pattern Management
            </h3>
            <span class="text-muted fs-8">Assign CAD pattern file types and upload technical pattern files for each style sample</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-light-primary fw-bold fs-8 py-2 px-3 border">
                <i class="fa-solid fa-paperclip text-primary me-1"></i>
                {{ $style->samples->whereNotNull('pattern_file')->count() }} / {{ $style->samples->count() }} Patterns Uploaded
            </span>
        </div>
    </div>
    <div class="card-body pt-2">
        <div class="table-responsive">
            <table class="table table-row-dashed table-sm align-middle fs-7 gy-3 mb-0" id="sample_patterns_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase bg-light">
                        <th class="ps-3 w-40px">#</th>
                        <th class="min-w-160px">Sample Stage & Name</th>
                        <th class="min-w-120px">Tag / Code</th>
                        <th class="min-w-100px">Season</th>
                        <th class="min-w-110px">Sample Status</th>
                        <th class="min-w-180px">Pattern File Type</th>
                        <th class="min-w-280px pe-3">Pattern File Attachment</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-800">
                    @forelse($style->samples as $index => $s)
                        <tr>
                            <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-gray-900 fs-7">{{ $s->sample_name }}</div>
                                <div class="text-muted fs-9 d-flex align-items-center gap-1 mt-1">
                                    <span class="badge badge-light-info fs-9 py-0 px-2">{{ $s->sampleType ? $s->sampleType->name : 'General Sample' }}</span>
                                    @if($s->sent_date)
                                        <span>• Sent: {{ $s->sent_date->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($s->tag_no)
                                    <div class="badge badge-light-secondary text-gray-800 font-monospace fs-8 border">{{ $s->tag_no }}</div>
                                @endif
                                @if($s->sample_code)
                                    <div class="text-muted fs-9 font-monospace mt-1">{{ $s->sample_code }}</div>
                                @elseif(!$s->tag_no)
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light text-gray-700 border fs-9">{{ $s->season ?: 'N/A' }}</span>
                            </td>
                            <td>
                                @php
                                    $st = strtoupper($s->status);
                                    $statusClass = match($st) {
                                        'APPROVED' => 'bg-light-success text-success border-success-subtle',
                                        'REVISED' => 'bg-light-warning text-warning border-warning-subtle',
                                        'PENDING' => 'bg-light-primary text-primary border-primary-subtle',
                                        'REJECTED' => 'bg-light-danger text-danger border-danger-subtle',
                                        default => 'bg-light text-gray-700 border-gray-300'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} border fs-8 py-1 px-2 fw-bold">
                                    {{ $s->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <select class="form-select form-select-sm py-1 px-2 fs-8 select-change-pattern-type border-gray-300"
                                            data-sample-id="{{ $s->id }}"
                                            data-current-val="{{ $s->pattern_file_type_id }}"
                                            style="min-width: 170px; max-width: 220px;"
                                            title="Select Pattern File Type">
                                        <option value="">-- Select Pattern Type --</option>
                                        @if(isset($patternFileTypes))
                                            @foreach($patternFileTypes as $pft)
                                                <option value="{{ $pft->id }}" {{ $s->pattern_file_type_id == $pft->id ? 'selected' : '' }}>
                                                    {{ $pft->name }}{{ $pft->code ? ' ('.$pft->code.')' : '' }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </td>
                            <td class="pe-3">
                                @if($s->pattern_file)
                                    @php
                                        $ext = strtolower(pathinfo($s->pattern_file, PATHINFO_EXTENSION));
                                        $iconClass = match($ext) {
                                            'dxf' => 'fa-bezier-curve text-primary',
                                            'pdf' => 'fa-file-pdf text-danger',
                                            'ai', 'eps' => 'fa-file-pen text-warning',
                                            'zip', 'rar' => 'fa-file-zipper text-info',
                                            'pds' => 'fa-file-code text-success',
                                            default => 'fa-file-lines text-primary'
                                        };
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border gap-2">
                                        <div class="d-flex align-items-center gap-2 text-truncate me-1">
                                            <i class="fa-solid {{ $iconClass }} fs-4 flex-shrink-0"></i>
                                            <div class="text-truncate">
                                                <div class="fw-bold text-gray-900 fs-8 text-truncate" title="{{ $s->pattern_file }}">
                                                    {{ $s->pattern_file }}
                                                </div>
                                                <div class="text-muted fs-9 d-flex align-items-center gap-1">
                                                    <span class="badge badge-light-secondary fs-9 py-0 px-1 text-uppercase">{{ $ext ?: 'FILE' }}</span>
                                                    @if($s->pattern_uploaded_at)
                                                        <span>• {{ $s->pattern_uploaded_at->format('d M, H:i') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                            <a href="{{ route('styles.samples.pattern.download', $s->id) }}" 
                                               class="btn btn-xs btn-icon btn-light-primary border" 
                                               title="Download Pattern File">
                                                <i class="fa-solid fa-download fs-8"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-xs btn-icon btn-light-danger border btn-delete-sample-pattern" 
                                                    data-sample-id="{{ $s->id }}" 
                                                    title="Delete Pattern File">
                                                <i class="fa-solid fa-trash fs-8"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-xs btn-icon btn-light-warning border btn-open-upload-pattern" 
                                                    data-sample-id="{{ $s->id }}" 
                                                    data-sample-name="{{ $s->sample_name }}" 
                                                    data-pattern-type-id="{{ $s->pattern_file_type_id }}" 
                                                    title="Replace Pattern File">
                                                <i class="fa-solid fa-arrow-rotate-right fs-8"></i>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-2 rounded bg-light border border-dashed text-muted fs-8 d-flex align-items-center justify-content-between">
                                        <span><i class="fa-solid fa-circle-info text-gray-400 me-1"></i> No pattern uploaded</span>
                                        <button type="button" 
                                                class="btn btn-xs btn-outline btn-outline-primary py-0 px-2 fs-9 btn-open-upload-pattern"
                                                data-sample-id="{{ $s->id }}"
                                                data-sample-name="{{ $s->sample_name }}"
                                                data-pattern-type-id="{{ $s->pattern_file_type_id }}">
                                            <i class="fa-solid fa-upload me-1"></i> Upload
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-muted">
                                <i class="fa-solid fa-vial fs-2x text-gray-400 d-block mb-2"></i>
                                <span class="d-block fw-bold text-gray-700 fs-7">No sample records created for this style yet.</span>
                                <span class="fs-8 text-muted">Create sample timeline stages in the <strong>Samples</strong> tab first to attach patterns.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
