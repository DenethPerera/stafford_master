@php
    $patternFileTypeHeaders = ['Pattern File Type Name', 'Code', 'Description', 'Associated Samples', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'Pattern File Types Management'">

    <!-- Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-3">
        <div class="px-4 py-3 d-flex flex-column">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold">Pattern File Types Master</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createPatternFileTypeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-plus me-2 text-primary"></i>
                        Add New Pattern File Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="createPatternFileTypeForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Pattern File Type Name*</label>
                            <input type="text" name="name" id="create_pattern_file_type_name" class="form-control form-input-custom" required placeholder="e.g. DXF / AAMA Standard CAD, Gerber Accumark" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Code / Extension</label>
                            <input type="text" name="code" id="create_pattern_file_type_code" class="form-control form-input-custom" placeholder="e.g. DXF, GERBER, LECTRA, PDS, ZIP" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Description / Notes</label>
                            <textarea name="description" id="create_pattern_file_type_description" class="form-control form-input-custom" rows="2" placeholder="Brief details regarding this CAD format or pattern specification..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="create_pattern_file_type_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnSavePatternFileType" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Pattern File Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editPatternFileTypeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pencil me-2 text-warning"></i>
                        Edit Pattern File Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editPatternFileTypeForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_pattern_file_type_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Pattern File Type Name*</label>
                            <input type="text" name="name" id="edit_pattern_file_type_name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Code / Extension</label>
                            <input type="text" name="code" id="edit_pattern_file_type_code" class="form-control form-input-custom" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Description / Notes</label>
                            <textarea name="description" id="edit_pattern_file_type_description" class="form-control form-input-custom" rows="2"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="edit_pattern_file_type_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnUpdatePatternFileType" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Pattern File Type
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
                    <select class="form-select form-input-custom" id="patternFileTypeStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" id="btnApplyPatternFileTypeFilter">
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
                <button type="button" class="btn btn-primary" id="btnOpenCreatePatternFileTypeModal">
                    <i class="fa-solid fa-plus me-1"></i> New Pattern File Type
                </button>
            </div>
            <x-table :headers="$patternFileTypeHeaders" id="patternFileTypesTable"></x-table>
        </div>
    </div>

</x-layouts.app>

<script>
    window.PatternFileTypeRoutes = {
        index:   "{{ route('masters.pattern-file-types.index') }}",
        store:   "{{ route('masters.pattern-file-types.store') }}",
        show:    "{{ route('masters.pattern-file-types.show', ':id') }}",
        update:  "{{ route('masters.pattern-file-types.update', ':id') }}",
        destroy: "{{ route('masters.pattern-file-types.destroy', ':id') }}"
    };
</script>
