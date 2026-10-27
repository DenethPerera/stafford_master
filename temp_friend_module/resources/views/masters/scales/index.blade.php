@php
    $scaleHeaders = ['Scale Name', 'Percentage Value', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'Scales Management'">

    <!-- Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-3">
        <div class="px-4 py-3 d-flex flex-column">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold">Scales Master</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ CREATE MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="createScaleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-plus me-2 text-primary"></i>
                        Add New Scale
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="createScaleForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Scale Name*</label>
                            <input type="text" name="name" id="create_scale_name" class="form-control form-input-custom" required placeholder="e.g. 100%" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Percentage Value (%)*</label>
                            <input type="number" step="0.01" name="percentage_value" id="create_scale_percentage_value" class="form-control form-input-custom" required placeholder="e.g. 100.00" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="create_scale_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnSaveScale" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Scale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ EDIT MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="editScaleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pencil me-2 text-warning"></i>
                        Edit Scale
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editScaleForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_scale_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Scale Name*</label>
                            <input type="text" name="name" id="edit_scale_name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Percentage Value (%)*</label>
                            <input type="number" step="0.01" name="percentage_value" id="edit_scale_percentage_value" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="edit_scale_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnUpdateScale" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Scale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ FILTERS ════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Status Filter</label>
                    <select class="form-select form-input-custom" id="scaleStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" id="btnApplyScaleFilter">
                        <i class="fa-solid fa-filter me-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ TABLE ════════════════════════════════════════ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-end mb-4">
                <button type="button" class="btn btn-primary" id="btnOpenCreateScaleModal">
                    <i class="fa-solid fa-plus me-1"></i> New Scale
                </button>
            </div>
            <x-table :headers="$scaleHeaders" id="scalesTable"></x-table>
        </div>
    </div>
    <script>
        window.ScaleRoutes = {
            index:   "{{ route('masters.scales.index') }}",
            store:   "{{ route('masters.scales.store') }}",
            show:    "{{ route('masters.scales.show', ':id') }}",
            update:  "{{ route('masters.scales.update', ':id') }}",
            destroy: "{{ route('masters.scales.destroy', ':id') }}"
        };
    </script>

    @push('scripts')
        <script src="{{ asset('js/scales.js') }}"></script>
    @endpush

</x-layouts.app>
