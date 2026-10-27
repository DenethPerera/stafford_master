@php
    $totalStyles = \App\Models\Style::count();
    $activeClients = \App\Models\Client::where('status', 'Active')->count();
    $totalColors = \App\Models\Color::count();
    $totalSizes = \App\Models\Size::count();

    $stylesInDevelopment = \App\Models\Style::where('status', 'DEVELOPMENT')->count();
    $stylesApproved = \App\Models\Style::where('status', 'APPROVED')->count();

    $recentStyles = \App\Models\Style::with('client')->latest()->take(6)->get();
    $recentActivities = \Spatie\Activitylog\Models\Activity::with('causer')->latest()->take(6)->get();
@endphp

<x-layouts.app :title="'PLM Dashboard'">

    <!-- Page Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-4">
        <div class="px-4 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold mb-0 text-dark">STAF PLM Dashboard</h1>
                <div class="text-muted small">Stafford Product Lifecycle Management — Styles & Tech Packs Overview</div>
            </div>
            <div>
                <a href="{{ route('styles.create') }}" class="btn btn-primary btn-sm px-4">
                    <i class="fa-solid fa-plus me-1"></i> New Style
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">
        <!-- Metric Cards -->
        <div class="row g-4 mb-5">

            <!-- Total Styles -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:52px;height:52px;background:rgba(13,110,253,.12); min-width: 52px;">
                            <i class="fa-solid fa-shirt fs-4 text-primary"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-bold mb-0 text-uppercase">PLM Styles</p>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($totalStyles) }}</h3>
                            <p class="small text-muted mb-0">Total Styles Workspace</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Clients -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:52px;height:52px;background:rgba(25,135,84,.12); min-width: 52px;">
                            <i class="fa-solid fa-users fs-4 text-success"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-bold mb-0 text-uppercase">Active Clients</p>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($activeClients) }}</h3>
                            <p class="small text-muted mb-0">Buyers & Brands</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- In Development -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:52px;height:52px;background:rgba(255,193,7,.15); min-width: 52px;">
                            <i class="fa-solid fa-compass-drafting fs-4 text-warning"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-bold mb-0 text-uppercase">Development</p>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($stylesInDevelopment) }}</h3>
                            <p class="small text-muted mb-0">Under Tech Pack & Costing</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved / Live -->
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:52px;height:52px;background:rgba(13,202,240,.12); min-width: 52px;">
                            <i class="fa-solid fa-circle-check fs-4 text-info"></i>
                        </div>
                        <div>
                            <p class="text-muted small fw-bold mb-0 text-uppercase">Approved Styles</p>
                            <h3 class="fw-bold text-dark mb-0">{{ number_format($stylesApproved) }}</h3>
                            <p class="small text-muted mb-0">Ready for Production</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4 mb-4">
            <!-- Recent PLM Styles -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-shirt text-primary me-2"></i>Recent PLM Styles
                        </h5>
                        <a href="{{ route('styles.index') }}" class="btn btn-sm btn-light-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light fs-7 text-uppercase fw-semibold">
                                    <tr>
                                        <th class="ps-4">Style No</th>
                                        <th>Style Name</th>
                                        <th>Client</th>
                                        <th>Season</th>
                                        <th>Status</th>
                                        <th class="pe-4 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentStyles as $st)
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">
                                                <a href="{{ route('styles.show', $st->id) }}">{{ $st->style_no }}</a>
                                            </td>
                                            <td>{{ \Illuminate\Support\Str::limit($st->style_name, 25) }}</td>
                                            <td>{{ $st->client ? $st->client->name : 'N/A' }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $st->season ?: 'N/A' }}</span></td>
                                            <td>
                                                <span class="badge bg-{{ $st->status === 'APPROVED' ? 'success' : ($st->status === 'DEVELOPMENT' ? 'warning' : 'secondary') }}">
                                                    {{ $st->status ?: 'DRAFT' }}
                                                </span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="{{ route('styles.show', $st->id) }}" class="btn btn-sm btn-light btn-active-light-primary">
                                                    Open
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No styles found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Log (Spatie) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-clock-rotate-left text-info me-2"></i>Recent Activity
                        </h5>
                        <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-light-info">All Logs</a>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline-widget">
                            @forelse($recentActivities as $act)
                                <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-light-primary text-primary" style="width:32px;height:32px;min-width:32px;">
                                        <i class="fa-solid fa-bolt fs-7"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark fs-7">{{ $act->description }}</div>
                                        <div class="text-muted fs-8">
                                            By {{ $act->causer ? $act->causer->name : 'System' }} • {{ $act->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted small">No recent activity recorded.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Navigation Cards -->
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center p-4">
                    <i class="fa-solid fa-shirt fs-1 text-primary mb-3"></i>
                    <h5 class="fw-bold">PLM Styles Hub</h5>
                    <p class="text-muted small">Manage styles, tech packs, BOM, colorways, samples, and measurements.</p>
                    <a href="{{ route('styles.index') }}" class="btn btn-primary btn-sm px-4">Open Workspace</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center p-4">
                    <i class="fa-solid fa-users fs-1 text-success mb-3"></i>
                    <h5 class="fw-bold">Client Directory</h5>
                    <p class="text-muted small">Manage buyer accounts, brand codes, contact persons and style history.</p>
                    <a href="{{ route('clients.index') }}" class="btn btn-success btn-sm px-4 text-white">View Clients</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center p-4">
                    <i class="fa-solid fa-swatchbook fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold">Master Files</h5>
                    <p class="text-muted small">Manage colors, size sequences, scales, and artwork placement positions.</p>
                    <a href="{{ route('masters.colors.index') }}" class="btn btn-warning btn-sm px-4 text-white">Open Masters</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 text-center p-4">
                    <i class="fa-solid fa-shield-halved fs-1 text-info mb-3"></i>
                    <h5 class="fw-bold">Audit & Permissions</h5>
                    <p class="text-muted small">View full Spatie activity audit trail and manage user role privileges.</p>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-info btn-sm px-4 text-white">View Logs</a>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
