<x-layouts.app :title="'System Activity Logs'">

    <!-- Page Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-4">
        <div class="px-4 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold mb-0 text-dark">System Activity Logs</h1>
                <div class="text-muted small">Comprehensive audit trail of PLM styles, master changes, and user operations</div>
            </div>
            <div class="text-muted small">
                Total Logs: <span class="fw-bold text-dark">{{ number_format($activities->total()) }}</span>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">
        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Search Logs</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Description, style, client, user...">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted">Module / Log Name</label>
                        <select name="log_name" class="form-select">
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
                        <select name="event" class="form-select">
                            <option value="">All Actions</option>
                            <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('activity-logs.index') }}" class="btn btn-light border" title="Reset">
                            <i class="fa-solid fa-rotate"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-7 text-uppercase fw-semibold">
                            <tr>
                                <th class="ps-4" style="width: 170px;">Timestamp</th>
                                <th style="width: 180px;">User</th>
                                <th style="width: 130px;">Action</th>
                                <th style="width: 130px;">Module</th>
                                <th>Description / Message</th>
                                <th class="pe-4 text-end" style="width: 140px;">Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark">{{ $activity->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted small">{{ $activity->created_at->format('h:i:s A') }} ({{ $activity->created_at->diffForHumans() }})</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width:32px; height:32px; min-width:32px; font-size:12px;">
                                                {{ strtoupper(substr($activity->causer->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark small">{{ $activity->causer->name ?? 'System' }}</div>
                                                <div class="text-muted" style="font-size: 11px;">{{ $activity->causer->email ?? 'Automated' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $descLower = strtolower($activity->description);
                                            $badgeClass = 'bg-secondary';
                                            if (str_contains($descLower, 'created')) $badgeClass = 'bg-success';
                                            elseif (str_contains($descLower, 'updated')) $badgeClass = 'bg-primary';
                                            elseif (str_contains($descLower, 'deleted')) $badgeClass = 'bg-danger';
                                            elseif (str_contains($descLower, 'seeded') || str_contains($descLower, 'configured')) $badgeClass = 'bg-info';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} text-white text-uppercase" style="font-size: 10px;">
                                            {{ $activity->description }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ ucfirst($activity->log_name ?: 'System') }}
                                        </span>
                                    </td>
                                    <td class="text-dark small">
                                        {{ $activity->getExtraProperty('log_message') ?: $activity->description }}
                                        @if($activity->subject_type)
                                            <span class="text-muted ms-1">({{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }})</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        @php
                                            $hasChanges = !empty($activity->properties) && count($activity->properties) > 0;
                                        @endphp
                                        @if($hasChanges)
                                            <button class="btn btn-sm btn-light-primary" onclick="showActivityDetails({{ $activity->id }})">
                                                <i class="fa-solid fa-code me-1"></i> View Diff
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
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-clock-rotate-left fs-2 mb-2 d-block"></i>
                                        No activity logs matched your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
    </div>

    @push('scripts')
        <script>
            function showActivityDetails(id) {
                const rawJson = $('#details-json-' + id).text();
                let formatted = rawJson;
                try {
                    const parsed = JSON.parse(rawJson);
                    formatted = JSON.stringify(parsed, null, 2);
                } catch(e) {}

                Swal.fire({
                    title: '<span class="fs-5 fw-bold">Activity Log Properties</span>',
                    html: `
                        <div class="text-start mt-2">
                            <pre class="bg-dark text-light p-3 rounded fs-7 overflow-auto" style="max-height: 400px; text-align: left;">${formatted}</pre>
                        </div>
                    `,
                    width: '700px',
                    showConfirmButton: true,
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#3085d6'
                });
            }
        </script>
    @endpush

</x-layouts.app>
