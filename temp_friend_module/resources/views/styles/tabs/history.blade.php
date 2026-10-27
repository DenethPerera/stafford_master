<div class="card card-flush shadow-sm">
    <div class="card-header pt-5 border-0">
        <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase">REVISION & AUDIT HISTORY</h3>
    </div>
    <div class="card-body pt-0">
        <div class="timeline-label">
            @forelse($style->revisions as $rev)
                <div class="d-flex align-items-center mb-4">
                    <i class="fa-solid fa-code-commit fs-5 me-3 text-primary"></i>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-gray-900 fs-7">{{ $rev->revision_no }} - {{ $rev->status }}</span>
                            <span class="text-muted fs-8">{{ $rev->created_at ? $rev->created_at->format('d M Y, h:i A') : '—' }}</span>
                        </div>
                        <div class="text-muted fs-8">{{ $rev->changes_summary ?: 'Style modification updated in PLM.' }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5 border border-dashed rounded">
                    <i class="fa-solid fa-clock-rotate-left fs-2x text-gray-400 mb-2 d-block"></i>
                    No revision history recorded yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
