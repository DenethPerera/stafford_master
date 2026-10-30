@php
    $bomTotal = $style->bomItems->sum(fn($i) => $i->total_cost ?: ($i->qty_per_size * $i->unit_cost));
    $mfgCost = 1.95; // Default standard mfg cost
    $directCost = $bomTotal + $mfgCost;

    $costing = $style->costing;
    $currency = $costing?->currency ?: 'USD';
    $overheadPct = $costing?->overhead_percent ?? 10.00;
    $profitPct = $costing?->profit_percent ?? 15.00;

    $overheadAmount = round(($directCost * $overheadPct) / 100, 2);
    $totalCostWithOh = $directCost + $overheadAmount;
    $profitAmount = round(($totalCostWithOh * $profitPct) / 100, 2);
    $targetPrice = $costing?->target_price ?: ($totalCostWithOh + $profitAmount);
@endphp

<div class="row g-6">
    <!-- Cost Sheet Summary Panel -->
    <div class="col-xl-7 col-lg-6">
        <div class="card card-flush shadow-sm border h-100">
            <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">COST SHEET SUMMARY</h3>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-light-dark fs-8 fw-bold">Currency: {{ $currency }}</span>
                    <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#editCostingModal">
                        <i class="fa-solid fa-pen fs-8 me-1"></i> Edit Margins
                    </button>
                </div>
            </div>
            <div class="card-body pt-2 fs-7">
                <!-- Material Cost Section (from BOM) -->
                <div class="table-responsive mb-4">
                    <div class="fw-bold text-gray-700 fs-8 text-uppercase mb-2">Material Costs (Derived from BOM)</div>
                    <table class="table table-bordered table-sm align-middle fs-7 mb-0">
                        <thead class="bg-light fw-bold text-gray-700 fs-8 text-uppercase">
                            <tr>
                                <th class="ps-3">Component Type</th>
                                <th>Item / Description</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">UOM</th>
                                <th class="text-end pe-3">Total Cost ({{ $currency }})</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-800">
                            @forelse($style->bomItems as $item)
                                <tr>
                                    <td class="ps-3"><span class="badge badge-light-primary fs-9">{{ $item->component_type }}</span></td>
                                    <td>{{ $item->item_description }}</td>
                                    <td class="text-end">{{ number_format($item->qty_per_size, 2) }}</td>
                                    <td class="text-end">{{ $item->uom }}</td>
                                    <td class="text-end pe-3 fw-bold">${{ number_format($item->total_cost ?: ($item->qty_per_size * $item->unit_cost), 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">No BOM components added.</td></tr>
                            @endforelse
                            <tr class="table-light fw-extrabold">
                                <td colspan="4" class="ps-3">Sub Total (Material)</td>
                                <td class="text-end pe-3 text-primary">${{ number_format($bomTotal, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Standard Manufacturing Cost Breakdown -->
                <div class="table-responsive">
                    <div class="fw-bold text-gray-700 fs-8 text-uppercase mb-2">Manufacturing Operations Cost</div>
                    <table class="table table-bordered table-sm align-middle fs-7 mb-0">
                        <thead class="bg-light fw-bold text-gray-700 fs-8 text-uppercase">
                            <tr>
                                <th class="ps-3">Operation</th>
                                <th class="text-end">Rate ({{ $currency }})</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">UOM</th>
                                <th class="text-end pe-3">Amount ({{ $currency }})</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-800">
                            <tr><td class="ps-3">Cutting</td><td class="text-end">0.35</td><td class="text-end">1.00</td><td class="text-end">Pcs</td><td class="text-end pe-3 fw-bold">$0.35</td></tr>
                            <tr><td class="ps-3">Sewing</td><td class="text-end">1.20</td><td class="text-end">1.00</td><td class="text-end">Pcs</td><td class="text-end pe-3 fw-bold">$1.20</td></tr>
                            <tr><td class="ps-3">Finishing</td><td class="text-end">0.40</td><td class="text-end">1.00</td><td class="text-end">Pcs</td><td class="text-end pe-3 fw-bold">$0.40</td></tr>
                            <tr class="table-light fw-extrabold"><td colspan="4" class="ps-3">Sub Total (Manufacturing)</td><td class="text-end pe-3 text-primary">$1.95</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Margin & Target Price Summary Card -->
    <div class="col-xl-5 col-lg-6">
        <div class="card card-flush shadow-sm border h-100">
            <div class="card-header pt-5 border-0">
                <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">MARGIN & TARGET PRICE</h3>
            </div>
            <div class="card-body pt-2 fs-7 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="fw-bold text-gray-700">Total Direct Cost (Material + Mfg)</span>
                        <span class="fs-5 fw-extrabold text-gray-900">${{ number_format($directCost, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="text-muted">Overhead % ({{ number_format($overheadPct, 2) }}%)</span>
                        <span class="fw-bold text-gray-800">${{ number_format($overheadAmount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom bg-light px-3 rounded mt-2">
                        <span class="fw-bold text-gray-800">Total Cost + OH</span>
                        <span class="fs-5 fw-bold text-gray-900">${{ number_format($totalCostWithOh, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="text-muted">Profit % ({{ number_format($profitPct, 2) }}%)</span>
                        <span class="fw-bold text-success">${{ number_format($profitAmount, 2) }}</span>
                    </div>
                </div>

                <div class="bg-light-primary p-4 rounded border border-primary border-dashed mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fs-8 text-primary fw-extrabold text-uppercase">Target Price ({{ $currency }})</span>
                        <span class="fs-2x fw-black text-primary">${{ number_format($targetPrice, 2) }}</span>
                    </div>
                    <div class="text-muted fs-9 text-end">Updated: {{ $costing?->updated_at ? $costing->updated_at->format('d M Y') : 'Auto-Calculated' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
