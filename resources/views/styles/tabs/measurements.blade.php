@php
    $sizesList = [
        'xs'  => 'XS',
        's'   => 'S',
        'm'   => 'M',
        'l'   => 'L',
        'xl'  => 'XL',
        'xxl' => 'XXL',
        '3xl' => '3XL',
        '4xl' => '4XL',
        '5xl' => '5XL',
        '6xl' => '6XL',
    ];
@endphp

<div class="card card-flush shadow-sm">
    <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase mb-1">MEASUREMENT SPECIFICATIONS GRID</h3>
            <div class="text-muted fs-8">Measurement Set: Spec Range ({{ $style->size_range ?: 'XS - 6XL' }})</div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addMeasurementModal">
                <i class="fa-solid fa-plus fs-8 me-1"></i> Add Measurement
            </button>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle fs-7 text-center">
                <thead class="bg-light fw-bold text-gray-700">
                    <tr>
                        <th class="w-40px">#</th>
                        <th class="text-start min-w-180px">Measurement Point</th>
                        <th class="w-50px">Spec</th>
                        <th class="w-50px">Tol (-)</th>
                        <th class="w-50px">Tol (+)</th>
                        @foreach($sizesList as $key => $label)
                            <th class="w-45px {{ $key === 'm' ? 'bg-light-primary text-primary' : '' }}">{{ $label }}</th>
                        @endforeach
                        <th class="w-45px">UOM</th>
                        <th class="w-90px text-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-700">
                    @forelse($style->measurements as $index => $m)
                        <tr>
                            <td class="text-muted fs-8">{{ $index + 1 }}</td>
                            <td class="text-start fw-bold text-gray-900">{{ $m->measurement_point }}</td>
                            <td class="fw-bold">{{ number_format($m->spec_value, 1) }}</td>
                            <td class="text-muted">{{ number_format($m->tol_minus, 1) }}</td>
                            <td class="text-muted">{{ number_format($m->tol_plus, 1) }}</td>
                            @foreach($sizesList as $key => $label)
                                @php $val = $m->{$key}; @endphp
                                <td class="{{ $key === 'm' ? 'bg-light-primary fw-bold text-primary' : '' }}">
                                    {{ !is_null($val) ? number_format($val, 1) : '—' }}
                                </td>
                            @endforeach
                            <td class="text-muted fs-8">{{ $m->uom }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-light-warning h-24px w-24px btn-edit-measurement"
                                            data-measurement="{{ json_encode($m) }}" title="Edit">
                                        <i class="fa-solid fa-pen fs-9"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger h-24px w-24px btn-delete-measurement"
                                            data-id="{{ $m->id }}" title="Delete">
                                        <i class="fa-solid fa-trash fs-9"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 6 + count($sizesList) }}" class="text-center text-muted py-5">No measurements defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="fs-9 text-muted text-end mt-2">All measurements are in inches unless specified.</div>
    </div>
</div>
