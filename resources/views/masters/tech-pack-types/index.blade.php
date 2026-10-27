@php
    $techPackTypeHeaders = ['Type Name', 'Code', 'Description', 'Associated Documents', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'Tech Pack Types Management'">

    <!-- Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-3">
        <div class="px-4 py-3 d-flex flex-column">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold">Tech Pack Types Master</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createTechPackTypeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-plus me-2 text-primary"></i>
                        Add New Tech Pack Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="createTechPackTypeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Type Name*</label>
                            <input type="text" name="name" id="create_tech_pack_type_name" class="form-control form-input-custom" required placeholder="e.g. Full Tech Pack, Measurement Spec Sheet" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Code / Abbreviation</label>
                            <input type="text" name="code" id="create_tech_pack_type_code" class="form-control form-input-custom" placeholder="e.g. FTP, SPEC, BOM, CONST" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Description / Notes</label>
                            <textarea name="description" id="create_tech_pack_type_description" class="form-control form-input-custom" rows="2" placeholder="Brief details about what this tech pack document covers..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="create_tech_pack_type_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnSaveTechPackType" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editTechPackTypeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pencil me-2 text-warning"></i>
                        Edit Tech Pack Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editTechPackTypeForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_tech_pack_type_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Type Name*</label>
                            <input type="text" name="name" id="edit_tech_pack_type_name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Code / Abbreviation</label>
                            <input type="text" name="code" id="edit_tech_pack_type_code" class="form-control form-input-custom" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Description / Notes</label>
                            <textarea name="description" id="edit_tech_pack_type_description" class="form-control form-input-custom" rows="2"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="edit_tech_pack_type_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnUpdateTechPackType" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Status Filter</label>
                    <select class="form-select form-input-custom" id="techPackTypeStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" id="btnApplyTechPackTypeFilter">
                        <i class="fa-solid fa-filter me-2"></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-end mb-4">
                <button type="button" class="btn btn-primary" id="btnOpenCreateTechPackTypeModal">
                    <i class="fa-solid fa-plus me-1"></i> New Tech Pack Type
                </button>
            </div>
            <x-table :headers="$techPackTypeHeaders" id="techPackTypesTable"></x-table>
        </div>
    </div>

</x-layouts.app>

<script>
    window.TechPackTypeRoutes = {
        index:   "{{ route('masters.tech-pack-types.index') }}",
        store:   "{{ route('masters.tech-pack-types.store') }}",
        show:    "{{ route('masters.tech-pack-types.show', ':id') }}",
        update:  "{{ route('masters.tech-pack-types.update', ':id') }}",
        destroy: "{{ route('masters.tech-pack-types.destroy', ':id') }}"
    };
</script>
