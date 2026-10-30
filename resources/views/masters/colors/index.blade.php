@php
    $colorHeaders = ['Color Name', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'Color Management'">

    <!-- Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-3">
        <div class="px-4 py-3 d-flex flex-column">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold">Colors Master</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ CREATE MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="createColorModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-plus me-2 text-primary"></i>
                        Add New Color
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="createColorForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Color Name*</label>
                            <input type="text" name="name" id="create_color_name" class="form-control form-input-custom" required placeholder="e.g. Navy Blue" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="create_color_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnSaveColor" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Color
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ EDIT MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="editColorModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pencil me-2 text-warning"></i>
                        Edit Color
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editColorForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_color_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Color Name*</label>
                            <input type="text" name="name" id="edit_color_name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="edit_color_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnUpdateColor" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Color
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
                    <select class="form-select form-input-custom" id="colorStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" id="btnApplyColorFilter">
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
                <button type="button" class="btn btn-primary" id="btnOpenCreateColorModal">
                    <i class="fa-solid fa-plus me-1"></i> New Color
                </button>
            </div>
            <x-table :headers="$colorHeaders" id="colorsTable"></x-table>
        </div>
    </div>

</x-layouts.app>

<script>
    window.ColorRoutes = {
        index:   "{{ route('masters.colors.index') }}",
        store:   "{{ route('masters.colors.store') }}",
        show:    "{{ route('masters.colors.show', ':id') }}",
        update:  "{{ route('masters.colors.update', ':id') }}",
        destroy: "{{ route('masters.colors.destroy', ':id') }}"
    };
</script>
