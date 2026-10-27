<div class="card card-flush shadow-sm">
    <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase">BOM COMPONENTS BREAKDOWN</h3>
        <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addBomItemModal">
            <i class="fa-solid fa-plus fs-8 me-1"></i> Add Component
        </button>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-row-dashed table-sm align-middle fs-7 gy-3">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-8 text-uppercase bg-light">
                        <th class="ps-3 w-40px">#</th>
                        <th>Component Type</th>
                        <th>Item / Description</th>
                        <th>Supplier</th>
                        <th>Placement</th>
                        <th>Color</th>
                        <th>Qty / Size</th>
                        <th>UOM</th>
                        <th>Unit Cost</th>
                        <th class="text-end">Total Cost</th>
                        <th class="text-end pe-3 w-100px">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-700">
                    @forelse($style->bomItems as $index => $item)
                        <tr>
                            <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                            <td><span class="badge badge-light-primary fw-bold fs-9">{{ $item->component_type }}</span></td>
                            <td class="fw-bold text-gray-900">{{ $item->item_description }}</td>
                            <td>{{ $item->supplier ?: '—' }}</td>
                            <td>{{ $item->placement ?: '—' }}</td>
                            <td>{{ $item->color ?: '—' }}</td>
                            <td>{{ number_format($item->qty_per_size, 2) }}</td>
                            <td>{{ $item->uom }}</td>
                            <td>${{ number_format($item->unit_cost, 2) }}</td>
                            <td class="text-end fw-bold text-gray-900">${{ number_format($item->total_cost ?: ($item->qty_per_size * $item->unit_cost), 2) }}</td>
                            <td class="text-end pe-3 text-nowrap">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-light-warning h-25px w-25px btn-edit-bom"
                                            data-item="{{ json_encode($item) }}" title="Edit">
                                        <i class="fa-solid fa-pen fs-9"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger h-25px w-25px btn-delete-bom"
                                            data-id="{{ $item->id }}" title="Delete">
                                        <i class="fa-solid fa-trash fs-9"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-5">No BOM components defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($style->bomItems->count() > 0)
                <tfoot class="border-top fw-extrabold text-gray-900">
                    <tr>
                        <td colspan="9" class="text-end pe-3">Total BOM Items ({{ $style->bomItems->count() }} Items):</td>
                        <td class="text-end text-primary fs-6">${{ number_format($style->bomItems->sum(fn($i) => $i->total_cost ?: ($i->qty_per_size * $i->unit_cost)), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
