<div class="card card-flush shadow-sm border mb-6">
    <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                <i class="fa-solid fa-vial-virus text-primary me-2"></i> Sample Tracking & Timeline
            </h3>
            <span class="text-muted fs-8">Track sample development stages, approvals, waybill numbers and seasons</span>
        </div>
        <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addSampleModal">
            <i class="fa-solid fa-plus fs-8 me-1"></i> New Sample Record
        </button>
    </div>
    <div class="card-body pt-2">
        <div class="table-responsive">
            <table class="table table-row-dashed table-sm align-middle fs-7 gy-3 mb-0">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase bg-light">
                        <th class="ps-3 w-40px">#</th>
                        <th class="min-w-160px">Sample Type</th>
                        <th class="min-w-140px">Sample Name</th>
                        <th class="min-w-130px">Season</th>
                        <th class="min-w-110px">Status</th>
                        <th class="min-w-115px">Sent & Approval</th>
                        <th class="min-w-110px">Waybill No</th>
                        <th class="text-center min-w-110px">Attachments</th>
                        <th class="text-end pe-3 w-100px">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-800">
                    @forelse($style->samples as $index => $s)
                        <tr>
                            <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <select class="form-select form-select-sm py-1 px-2 fs-8 select-change-sample-type border-gray-300" 
                                            data-sample-id="{{ $s->id }}" 
                                            style="min-width: 150px; max-width: 190px;" 
                                            title="Change sample type">
                                        <option value="">-- Select Type --</option>
                                        @if(isset($sampleTypes))
                                            @foreach($sampleTypes as $st)
                                                <option value="{{ $st->id }}" {{ $s->sample_type_id == $st->id ? 'selected' : '' }}>
                                                    {{ $st->name }}{{ $st->code ? ' ('.$st->code.')' : '' }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($s->sampleType)
                                        <span class="badge badge-light-primary fw-bold fs-9 text-nowrap d-none d-xxl-inline-block">{{ $s->sampleType->code ?: 'TYPE' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-gray-900 fs-7">{{ $s->sample_name }}</div>
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    @if($s->sample_code)
                                        <span class="text-muted fs-9 font-monospace" title="Sample Code">{{ $s->sample_code }}</span>
                                    @endif
                                    @if($s->tag_no)
                                        <span class="badge badge-light-secondary text-gray-700 font-monospace fs-9 py-0 px-1 border" title="Tag No: {{ $s->tag_no }}">
                                            <i class="fa-solid fa-tag text-muted fs-9 me-1"></i>{{ $s->tag_no }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <select class="form-select form-select-sm py-1 px-2 fs-8 select-change-sample-season border-gray-300" 
                                        data-sample-id="{{ $s->id }}" 
                                        style="min-width: 125px; max-width: 160px;" 
                                        title="Change season">
                                    <option value="">-- Select Season --</option>
                                    @if(isset($seasons))
                                        @foreach($seasons as $seasonOpt)
                                            <option value="{{ $seasonOpt }}" {{ (string)$s->season === (string)$seasonOpt ? 'selected' : '' }}>
                                                {{ $seasonOpt }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm py-1 px-2 fs-8 select-change-sample-status fw-bold border {{ match(strtoupper($s->status)) {
                                    'APPROVED' => 'bg-light-success text-success border-success-subtle',
                                    'REVISED'  => 'bg-light-warning text-warning border-warning-subtle',
                                    'PENDING'  => 'bg-light-primary text-primary border-primary-subtle',
                                    'REJECTED' => 'bg-light-danger text-danger border-danger-subtle',
                                    default    => 'bg-light text-gray-700 border-gray-300',
                                } }}" data-sample-id="{{ $s->id }}" data-current-val="{{ $s->status }}" style="min-width: 105px; max-width: 120px;" title="Change sample status">
                                    <option value="Pending" {{ $s->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ $s->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Revised" {{ $s->status == 'Revised' ? 'selected' : '' }}>Revised</option>
                                    <option value="Rejected" {{ $s->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </td>
                            <td class="text-nowrap sample-dates-cell">
                                <div class="fs-8 text-gray-800" title="Sent Date">
                                    <span class="text-muted fs-9">Sent:</span> {{ $s->sent_date ? $s->sent_date->format('d M Y') : '—' }}
                                </div>
                                @if($s->approval_date)
                                    <div class="fs-9 text-success fw-bold mt-1" title="Approved on {{ $s->approval_date->format('d M Y') }}">
                                        <i class="fa-solid fa-circle-check text-success fs-9 me-1"></i>Appr: {{ $s->approval_date->format('d M Y') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($s->waybill_no)
                                    <span class="badge badge-light-dark fw-bold fs-9 text-truncate d-inline-block" style="max-width: 130px;" title="Waybill / Tracking No: {{ $s->waybill_no }}">
                                       {{ $s->waybill_no }}
                                    </span>
                                @else
                                    <span class="text-muted fs-9">—</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                    {{-- QC Report --}}
                                    @if($s->qc_report)
                                        <a href="{{ asset('uploads/styles/samples/qc/' . $s->qc_report) }}" target="_blank"
                                           class="btn btn-icon btn-sm btn-light-danger border border-danger-subtle w-26px h-26px rounded shadow-xs" 
                                           title="QC Report: {{ $s->qc_report }}">
                                            <i class="fa-solid fa-file-pdf fs-8"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-300 fs-8 p-1" title="No QC Report"><i class="fa-regular fa-file-pdf"></i></span>
                                    @endif

                                    {{-- Cost Sheet --}}
                                    @if($s->cost_sheet)
                                        <a href="{{ asset('uploads/styles/samples/cost/' . $s->cost_sheet) }}" target="_blank"
                                           class="btn btn-icon btn-sm btn-light-success border border-success-subtle w-26px h-26px rounded shadow-xs" 
                                           title="Cost Sheet: {{ $s->cost_sheet }}">
                                            <i class="fa-solid fa-file-invoice-dollar fs-8"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-300 fs-8 p-1" title="No Cost Sheet"><i class="fa-regular fa-file-excel"></i></span>
                                    @endif

                                    {{-- Comment Sheet --}}
                                    @if($s->comment_sheet)
                                        <a href="{{ asset('uploads/styles/samples/comments/' . $s->comment_sheet) }}" target="_blank"
                                           class="btn btn-icon btn-sm btn-light-primary border border-primary-subtle w-26px h-26px rounded shadow-xs" 
                                           title="Comment Sheet: {{ $s->comment_sheet }}">
                                            <i class="fa-solid fa-file-lines fs-8"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-300 fs-8 p-1" title="No Comment Sheet"><i class="fa-regular fa-file-lines"></i></span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                    <!-- View Details Button -->
                                    <button type="button" class="btn btn-sm btn-icon btn-light-primary h-26px w-26px btn-view-sample" 
                                            data-sample="{{ json_encode($s) }}" 
                                            data-sample-type-name="{{ $s->sampleType?->name }}"
                                            data-sample-type-code="{{ $s->sampleType?->code }}"
                                            title="View Full Details">
                                        <i class="fa-solid fa-eye fs-9"></i>
                                    </button>
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-sm btn-icon btn-light-warning h-26px w-26px btn-edit-sample" 
                                            data-sample="{{ json_encode($s) }}" 
                                            title="Edit">
                                        <i class="fa-solid fa-pen fs-9"></i>
                                    </button>
                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger h-26px w-26px btn-delete-sample" 
                                            data-id="{{ $s->id }}" 
                                            title="Delete">
                                        <i class="fa-solid fa-trash fs-9"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">No sample tracking records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
