@props(['status'])

@php
    $statusStr = match (true) {
        $status instanceof \BackedEnum => (string)$status->value,
        $status instanceof \UnitEnum => (string)$status->name,
        is_string($status) => $status,
        is_numeric($status) => (string)$status,
        default => (string)($status ?? 'Unknown'),
    };

    $normalized = strtolower(trim($statusStr));

    $badgeClass = match ($normalized) {
        'active', 'approved', 'completed', 'success'   => 'bg-light-success text-success border border-success border-opacity-25',
        'pending', 'draft', 'qc', 'partial'          => 'bg-light-warning text-warning border border-warning border-opacity-25',
        'processing', 'processed', 'running', 'sent'  => 'bg-light-primary text-primary border border-primary border-opacity-25',
        'ironing', 'sample', 'initial', 'info'        => 'bg-light-info text-info border border-info border-opacity-25',
        'inactive', 'cancelled', 'rejected', 'disabled' => 'bg-light-danger text-danger border border-danger border-opacity-25',
        default                                       => 'bg-light-secondary text-secondary border border-secondary border-opacity-25',
    };
@endphp

<span class="badge {{ $badgeClass }} px-3 py-2 fs-8 fw-bold">{{ ucfirst($statusStr) }}</span>
