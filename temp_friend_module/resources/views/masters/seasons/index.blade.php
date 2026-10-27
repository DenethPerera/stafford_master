@php
    $seasonHeaders = ['Season Name', 'Code', 'Description', 'Associated Styles', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'Seasons Management'">

    <!-- Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-3">
        <div class="px-4 py-3 d-flex flex-column">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold">Seasons Master</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createSeasonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-plus me-2 text-primary"></i>
                        Add New Season
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="createSeasonForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Season Name*</label>
                            <input type="text" name="name" id="create_season_name" class="form-control form-input-custom" required placeholder="e.g. Spring 2026, FW25, Summer 2026" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Code / Abbreviation</label>
                            <input type="text" name="code" id="create_season_code" class="form-control form-input-custom" placeholder="e.g. SP26, FW25, SS26" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Description / Notes</label>
                            <textarea name="description" id="create_season_description" class="form-control form-input-custom" rows="2" placeholder="Brief details about this season collection..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="create_season_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnSaveSeason" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Season
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editSeasonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pencil me-2 text-warning"></i>
                        Edit Season
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editSeasonForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_season_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Season Name*</label>
                            <input type="text" name="name" id="edit_season_name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Code / Abbreviation</label>
                            <input type="text" name="code" id="edit_season_code" class="form-control form-input-custom" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Description / Notes</label>
                            <textarea name="description" id="edit_season_description" class="form-control form-input-custom" rows="2"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="edit_season_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3 mt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnUpdateSeason" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Season
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
                    <select class="form-select form-input-custom" id="seasonStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" id="btnApplySeasonFilter">
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
                <button type="button" class="btn btn-primary" id="btnOpenCreateSeasonModal">
                    <i class="fa-solid fa-plus me-1"></i> New Season
                </button>
            </div>
            <x-table :headers="$seasonHeaders" id="seasonsTable"></x-table>
        </div>
    </div>

</x-layouts.app>

<script>
    window.SeasonRoutes = {
        index:   "{{ route('masters.seasons.index') }}",
        store:   "{{ route('masters.seasons.store') }}",
        show:    "{{ route('masters.seasons.show', ':id') }}",
        update:  "{{ route('masters.seasons.update', ':id') }}",
        destroy: "{{ route('masters.seasons.destroy', ':id') }}"
    };
</script>
