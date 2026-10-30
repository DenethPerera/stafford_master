<div class="card card-flush shadow-sm">
    <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">WORKFLOW & SIGN-OFF APPROVALS</h3>
        <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addApprovalModal">
            <i class="fa-solid fa-plus fs-8 me-1"></i> Add Approval Gate
        </button>
    </div>
    <div class="card-body pt-0 fs-7">
        <div class="table-responsive">
            <table class="table table-row-dashed align-middle fs-7 gy-3">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase bg-light">
                        <th class="ps-3">#</th>
                        <th>Approval Gate</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Date & Time</th>
                        <th>Sign-Off Remarks</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-700">
                    @forelse($style->approvals as $index => $app)
                        <tr>
                            <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold text-gray-900">{{ $app->gate_name }}</td>
                            <td>
                                @php
                                    $st = strtoupper($app->status);
                                    $badgeClass = match($st) {
                                        'APPROVED'    => 'badge-light-success text-success',
                                        'PENDING'     => 'badge-light-primary text-primary',
                                        'REJECTED'    => 'badge-light-danger text-danger',
                                        default       => 'badge-light-secondary text-muted',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} fw-bold fs-9">{{ $app->status }}</span>
                            </td>
                            <td>{{ $app->approved_by ?: '—' }}</td>
                            <td>{{ $app->approval_date ? $app->approval_date->format('d M Y, h:i A') : '—' }}</td>
                            <td class="text-muted fs-8">{{ $app->remarks ?: '—' }}</td>
                            <td class="text-end pe-3 text-nowrap">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-light-warning h-25px w-25px btn-edit-approval" data-approval="{{ json_encode($app) }}" title="Edit">
                                        <i class="fa-solid fa-pen fs-9"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger h-25px w-25px btn-delete-approval" data-id="{{ $app->id }}" title="Delete">
                                        <i class="fa-solid fa-trash fs-9"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">No approval sign-off gates created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
