@php
    $sizeHeaders = ['Size Name', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'Size Management'">

    <!-- Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-3">
        <div class="px-4 py-3 d-flex flex-column">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold">Sizes Master</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ CREATE MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="createSizeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-plus me-2 text-primary"></i>
                        Add New Size
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="createSizeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Size Name*</label>
                            <input type="text" name="name" id="create_size_name" class="form-control form-input-custom" required placeholder="e.g. XL" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="create_size_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnSaveSize" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Size
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ EDIT MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="editSizeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pencil me-2 text-warning"></i>
                        Edit Size
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editSizeForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_size_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Size Name*</label>
                            <input type="text" name="name" id="edit_size_name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="edit_size_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnUpdateSize" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Size
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
                    <select class="form-select form-input-custom" id="sizeStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" id="btnApplySizeFilter">
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
                <button type="button" class="btn btn-primary" id="btnOpenCreateSizeModal">
                    <i class="fa-solid fa-plus me-1"></i> New Size
                </button>
            </div>
            <x-table :headers="$sizeHeaders" id="sizesTable"></x-table>
        </div>
    </div>

</x-layouts.app>

<script>
    window.SizeRoutes = {
        index:   "{{ route('masters.sizes.index') }}",
        store:   "{{ route('masters.sizes.store') }}",
        show:    "{{ route('masters.sizes.show', ':id') }}",
        update:  "{{ route('masters.sizes.update', ':id') }}",
        destroy: "{{ route('masters.sizes.destroy', ':id') }}"
    };
</script>
