@php
    $constructionHeaders = ['Construction Name', 'Code', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'Construction Management'">

    <!-- Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-3">
        <div class="px-4 py-3 d-flex flex-column">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold">Constructions Master</h1>
                </div>
            </div>
        </div>
    </div>

    {{--  CREATE MODAL--}}
    <div class="modal fade" id="createConstructionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-plus me-2 text-primary"></i>
                        Add New Construction
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="createConstructionForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Construction Name*</label>
                            <input type="text" name="name" id="create_construction_name" class="form-control form-input-custom" required placeholder="e.g. Single Jersey" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Construction Code</label>
                            <input type="text" name="code" id="create_construction_code" class="form-control form-input-custom" placeholder="e.g. SJ" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="create_construction_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnSaveConstruction" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Construction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--  EDIT MODAL  --}}
    <div class="modal fade" id="editConstructionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pencil me-2 text-warning"></i>
                        Edit Construction
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editConstructionForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_construction_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Construction Name*</label>
                            <input type="text" name="name" id="edit_construction_name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Construction Code</label>
                            <input type="text" name="code" id="edit_construction_code" class="form-control form-input-custom" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="edit_construction_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnUpdateConstruction" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Construction
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
                    <select class="form-select form-input-custom" id="constructionStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" id="btnApplyConstructionFilter">
                        <i class="fa-solid fa-filter me-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{--  TABLE  --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-end mb-4">
                <button type="button" class="btn btn-primary" id="btnOpenCreateConstructionModal">
                    <i class="fa-solid fa-plus me-1"></i> New Construction
                </button>
            </div>
            <x-table :headers="$constructionHeaders" id="constructionsTable"></x-table>
        </div>
    </div>

</x-layouts.app>

<script>
    window.ConstructionRoutes = {
        index:   "{{ route('masters.constructions.index') }}",
        store:   "{{ route('masters.constructions.store') }}",
        show:    "{{ route('masters.constructions.show', ':id') }}",
        update:  "{{ route('masters.constructions.update', ':id') }}",
        destroy: "{{ route('masters.constructions.destroy', ':id') }}"
    };
</script>
