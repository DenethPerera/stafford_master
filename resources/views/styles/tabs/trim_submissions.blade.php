@php
    $trims = $style->trimSubmissions ?? collect();
    $totalCount = $trims->count();
    $approvedCount = $trims->where('status', 'APPROVED')->count();
    $pendingCount = $trims->where('status', 'PENDING')->count();
    $rejectedCount = $trims->whereIn('status', ['REJECTED', 'RESUBMIT'])->count();
@endphp

<!-- Stat Summary Cards -->
<div class="row g-4 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-flush shadow-xs border bg-light-primary p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-gray-600 fs-8 fw-bold text-uppercase d-block mb-1">Total Submissions</span>
                    <span class="fs-2hx fw-black text-primary">{{ $totalCount }}</span>
                </div>
                <div class="w-45px h-45px rounded-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-tags text-primary fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-flush shadow-xs border bg-light-success p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-gray-600 fs-8 fw-bold text-uppercase d-block mb-1">Approved</span>
                    <span class="fs-2hx fw-black text-success">{{ $approvedCount }}</span>
                </div>
                <div class="w-45px h-45px rounded-3 bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-circle-check text-success fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-flush shadow-xs border bg-light-warning p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-gray-600 fs-8 fw-bold text-uppercase d-block mb-1">Pending Sign-off</span>
                    <span class="fs-2hx fw-black text-warning">{{ $pendingCount }}</span>
                </div>
                <div class="w-45px h-45px rounded-3 bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-clock text-warning fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-flush shadow-xs border bg-light-danger p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-gray-600 fs-8 fw-bold text-uppercase d-block mb-1">Rejected / Resubmit</span>
                    <span class="fs-2hx fw-black text-danger">{{ $rejectedCount }}</span>
                </div>
                <div class="w-45px h-45px rounded-3 bg-danger bg-opacity-10 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-circle-xmark text-danger fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card card-flush shadow-sm border mb-6">
    <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">
                <i class="fa-solid fa-tags text-primary me-2"></i> Trim Submissions & Development
            </h3>
            <span class="text-muted fs-8">Manage supplier trim swatches, physical cards, submissions, and buyer approval sign-offs</span>
        </div>
        <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addTrimSubmissionModal">
            <i class="fa-solid fa-plus fs-8 me-1"></i> Add Trim Submission
        </button>
    </div>
    <div class="card-body pt-2">
        @if($trims->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle fs-7 mb-0">
                    <thead class="bg-light text-gray-700 fw-bold fs-8 text-uppercase">
                        <tr>
                            <th class="ps-3 w-40px text-center">#</th>
                            <th class="ps-3 w-160px">Trim Type</th>
                            <th>Item Description</th>
                            <th>Supplier & Placement</th>
                            <th class="w-120px">Color / Finish</th>
                            <th class="w-130px text-center">Stage & Sent</th>
                            <th class="w-120px text-center">Status</th>
                            <th class="w-140px">Approved By / Date</th>
                            <th class="w-110px text-center">Attachment</th>
                            <th class="w-90px text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-800">
                        @foreach($trims as $index => $t)
                            @php
                                $status = strtoupper($t->status);
                                $badgeClass = match($status) {
                                    'APPROVED'               => 'badge-light-success text-success',
                                    'APPROVED WITH COMMENTS' => 'badge-light-info text-info',
                                    'PENDING'                => 'badge-light-primary text-primary',
                                    'RESUBMIT'               => 'badge-light-warning text-warning',
                                    'REJECTED'               => 'badge-light-danger text-danger',
                                    default                  => 'badge-light-secondary text-muted',
                                };
                            @endphp
                            <tr>
                                <td class="ps-3 text-center align-middle text-muted fs-8">{{ $index + 1 }}</td>
                                <td class="ps-3 align-middle">
                                    <span class="badge badge-light-primary fw-bold fs-8">
                                        {{ $t->trim_type }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="fw-bold text-gray-900 fs-7">{{ $t->item_name }}</div>
                                    @if($t->remarks)
                                        <div class="text-muted fs-9 text-truncate" style="max-width: 250px;" title="{{ $t->remarks }}">
                                            <i class="fa-solid fa-comment-dots text-gray-400 me-1"></i>{{ $t->remarks }}
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="fw-bold text-gray-800 fs-8">{{ $t->supplier ?: '—' }}</div>
                                    @if($t->placement)
                                        <div class="text-muted fs-9">{{ $t->placement }}</div>
                                    @endif
                                </td>
                                <td class="align-middle fs-8 text-gray-700">
                                    {{ $t->color ?: '—' }}
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-light fw-bold text-gray-700 fs-9 d-block mb-1">{{ $t->submission_stage ?: '1st Submission' }}</span>
                                    <span class="text-muted fs-9">{{ $t->sent_date ? $t->sent_date->format('d M Y') : '—' }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge {{ $badgeClass }} fw-bold fs-9 text-uppercase px-2 py-1">
                                        {{ $t->status }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="fw-bold text-gray-800 fs-8">{{ $t->approved_by ?: '—' }}</div>
                                    <div class="text-muted fs-9">{{ $t->approval_date ? $t->approval_date->format('d M Y') : '—' }}</div>
                                </td>
                                <td class="text-center align-middle">
                                    @if($t->attachment)
                                        @php
                                            $ext = strtolower(pathinfo($t->attachment, PATHINFO_EXTENSION));
                                            $isImg = in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg']);
                                        @endphp
                                        @if($isImg)
                                            <a href="{{ $t->attachment_url }}" target="_blank" class="d-inline-block position-relative border rounded p-1 bg-white" title="View Trim Swatch">
                                                <img src="{{ $t->attachment_url }}" alt="Trim" class="rounded w-35px h-35px object-fit-cover">
                                            </a>
                                        @else
                                            <a href="{{ $t->attachment_url }}" target="_blank" class="btn btn-sm btn-light-primary px-2 py-1 fs-9" title="View Document">
                                                <i class="fa-solid fa-file-pdf me-1"></i> Doc
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted fs-9">—</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle text-nowrap">
                                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                        <button type="button" class="btn btn-icon btn-sm btn-light-warning border w-28px h-28px rounded-2 btn-edit-trim-submission" 
                                                data-trim="{{ json_encode($t) }}" 
                                                data-update-url="{{ route('styles.trim-submissions.update', $t->id) }}"
                                                title="Edit Trim Submission">
                                            <i class="fa-solid fa-pen fs-9"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger border w-28px h-28px rounded-2 btn-delete-trim-submission" 
                                                data-id="{{ $t->id }}" 
                                                data-name="{{ $t->item_name }}"
                                                data-delete-url="{{ route('styles.trim-submissions.destroy', $t->id) }}"
                                                title="Delete">
                                            <i class="fa-solid fa-trash fs-9"></i>
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
                <i class="fa-solid fa-tags text-gray-400 fs-2tx mb-3 d-block"></i>
                <h5 class="text-gray-800 fw-bold d-block fs-6 mb-1">No Trim Submissions Recorded</h5>
                <p class="text-muted fs-8 d-block mb-4 max-w-500px mx-auto">Track buttons, zippers, labels, drawcords, hangtags, and trims submitted to the buyer for bulk approval.</p>
                <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addTrimSubmissionModal">
                    <i class="fa-solid fa-plus me-1"></i> Add First Trim Submission
                </button>
            </div>
        @endif
    </div>
</div>
