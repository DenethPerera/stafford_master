@extends('layouts.app')

@section('header', 'Activity Logs')

@section('content')

{{-- Page Heading --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h5 fw-bolder text-dark mb-0">System Activity Logs</h2>
        <p class="small text-muted fw-semibold mt-1">Comprehensive audit trail of all system operations</p>
    </div>
    <div class="badge bg-white text-secondary border rounded-pill px-3 py-2 shadow-sm fw-semibold">
        Total: {{ number_format($activities->total()) }} records
    </div>
</div>

{{-- Filter Card --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-muted">Search Logs</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control border-start-0 rounded-end-4"
                           placeholder="Description, module, user...">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">Module / Log Name</label>
                <select name="log_name" class="form-select rounded-4">
                    <option value="">All Modules</option>
                    @foreach($logNames as $ln)
                        <option value="{{ $ln }}" {{ request('log_name') === $ln ? 'selected' : '' }}>
                            {{ ucfirst($ln) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">Action / Event</label>
                <select name="event" class="form-select rounded-4">
                    <option value="">All Actions</option>
                    <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="bubble-btn w-100">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                <a href="{{ route('activity-logs.index') }}" class="btn btn-light border rounded-pill" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Results Table --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="py-3 px-4 text-muted text-uppercase fw-bolder" style="font-size: 11px; letter-spacing: 1px; width: 170px;">Timestamp</th>
                    <th class="py-3 px-4 text-muted text-uppercase fw-bolder" style="font-size: 11px; letter-spacing: 1px; width: 180px;">User</th>
                    <th class="py-3 px-4 text-muted text-uppercase fw-bolder" style="font-size: 11px; letter-spacing: 1px; width: 120px;">Action</th>
                    <th class="py-3 px-4 text-muted text-uppercase fw-bolder" style="font-size: 11px; letter-spacing: 1px; width: 130px;">Module</th>
                    <th class="py-3 px-4 text-muted text-uppercase fw-bolder" style="font-size: 11px; letter-spacing: 1px;">Description / Subject</th>
                    <th class="py-3 px-4 text-muted text-uppercase fw-bolder text-end" style="font-size: 11px; letter-spacing: 1px; width: 140px;">Changes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                    <tr>
                        <td class="py-3 px-4 text-nowrap">
                            <div class="fw-semibold text-dark small">{{ $activity->created_at->format('M d, Y') }}</div>
                            <div class="text-muted" style="font-size: 11px;">{{ $activity->created_at->format('h:i:s A') }}</div>
                            <div class="text-muted" style="font-size: 10px;">{{ $activity->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                     style="width:32px; height:32px; font-size:12px;">
                                    {{ strtoupper(substr($activity->causer->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark small">{{ $activity->causer->name ?? 'System' }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ $activity->causer->email ?? 'Automated' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $descLower = strtolower($activity->description);
                                $badgeClass = match(true) {
                                    str_contains($descLower, 'created') => 'bg-success bg-opacity-10 text-success',
                                    str_contains($descLower, 'updated') => 'bg-warning bg-opacity-25 text-dark',
                                    str_contains($descLower, 'deleted') => 'bg-danger bg-opacity-10 text-danger',
                                    str_contains($descLower, 'seeded'), str_contains($descLower, 'configured') => 'bg-info bg-opacity-10 text-info',
                                    default => 'bg-light border text-secondary',
                                };
                            @endphp
                            <span class="badge rounded-pill px-2 py-1 text-uppercase fw-bolder {{ $badgeClass }}" style="font-size: 10px;">
                                {{ $activity->description }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="badge bg-light text-dark border rounded-pill px-2" style="font-size: 11px;">
                                {{ ucfirst($activity->log_name ?: 'system') }}
                            </span>
                        </td>
                        <td class="py-3 px-4 small text-dark">
                            {{ $activity->getExtraProperty('log_message') ?: $activity->description }}
                            @if($activity->subject_type)
                                <span class="text-muted ms-1">({{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }})</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-end">
                            @php
                                $hasChanges = !empty($activity->properties) && count($activity->properties) > 0;
                            @endphp
                            @if($hasChanges)
                                <button class="btn btn-sm btn-light text-primary rounded-pill border fw-bold"
                                        onclick="showActivityDetails({{ $activity->id }})"
                                        style="font-size: 12px;">
                                    <i class="bi bi-code-slash me-1"></i> View Diff
                                </button>
                                <div id="details-json-{{ $activity->id }}" class="d-none">
                                    {{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-5 text-center text-muted fw-semibold small">
                            <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                            No activity logs matched your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($activities->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} results
            </div>
            <div>
                {{ $activities->links() }}
            </div>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function showActivityDetails(id) {
        const rawJson = document.getElementById('details-json-' + id).textContent;
        let formatted = rawJson;
        try {
            const parsed = JSON.parse(rawJson);
            formatted = JSON.stringify(parsed, null, 2);
        } catch(e) {}

        // Use Bootstrap modal if SweetAlert2 is not available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '<span class="fs-5 fw-bold">Activity Log Properties</span>',
                html: `
                    <div class="text-start mt-2">
                        <pre class="bg-dark text-light p-3 rounded fs-7 overflow-auto" style="max-height: 400px; text-align: left; font-size: 12px;">${formatted}</pre>
                    </div>
                `,
                width: '700px',
                showConfirmButton: true,
                confirmButtonText: 'Close',
                confirmButtonColor: '#00A39A'
            });
        } else {
            // Fallback: show in confirm modal
            const modal = new bootstrap.Modal(document.getElementById('activityDiffModal'));
            document.getElementById('activityDiffContent').textContent = formatted;
            modal.show();
        }
    }
</script>

{{-- Fallback modal for environments without SweetAlert2 --}}
<div class="modal fade" id="activityDiffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bolder text-dark">Activity Log Properties</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre id="activityDiffContent" class="bg-dark text-light p-3 rounded overflow-auto" style="max-height: 400px; font-size: 12px;"></pre>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endpush
