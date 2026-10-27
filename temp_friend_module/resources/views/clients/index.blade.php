@php
    $clientHeaders = ['Name', 'Phone', 'Address', 'PLM Styles', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'Client Management'">

    <!-- Header -->
    <div class="container-fluid bg-white border-bottom shadow-sm mb-3">
        <div class="px-4 py-3 d-flex flex-column">
            <div class="d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold">
                        Client Management
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ CREATE MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="createClientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-plus me-2 text-primary"></i>
                        Add New Client
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="createClientForm" data-store-url="{{ route('clients.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Client Name*</label>
                            <input type="text" name="name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Phone Number</label>
                            <input type="text" name="phone" class="form-control form-input-custom" placeholder="07X XXX XXXX" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Address</label>
                            <input type="text" name="address" class="form-control form-input-custom" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end border-top pt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>
                                <span class="btn-text">Save Client</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ EDIT MODAL ════════════════════════════════════════ --}}
    <div class="modal fade" id="editClientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pen-to-square me-2 text-warning"></i>
                        Edit Client
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form id="editClientForm" method="POST" data-base-url="{{ route('clients.index') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_client_id" name="id">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Client Name*</label>
                            <input type="text" name="name" id="edit_name" class="form-control form-input-custom" required autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Phone Number</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control form-input-custom" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Address</label>
                            <input type="text" name="address" id="edit_address" class="form-control form-input-custom" autocomplete="off">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status*</label>
                            <select name="status" id="edit_status" class="form-select form-input-custom" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end border-top pt-3">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>
                                <span class="btn-text">Update Client</span>
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
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Search</label>
                    <input type="text" placeholder="Name, Phone, Address..." class="form-control form-input-custom" id="clientSearch">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Status</label>
                    <select class="form-select form-input-custom" id="clientStatusFilter">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" id="btnApplyFilter">
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
                <button class="btn btn-primary" id="btnOpenCreateModal" data-bs-toggle="modal" data-bs-target="#createClientModal">
                    <i class="fa-solid fa-plus me-1"></i> New Client
                </button>
            </div>
            <x-table :headers="$clientHeaders" id="clientTable"></x-table>
        </div>
    </div>

    <script>
        window.ClientRoutes = {
            index:   "{{ route('clients.index') }}",
            destroy: "{{ route('clients.destroy', ':id') }}",
            show:    "{{ route('clients.show', ':id') }}",
            update:  "{{ route('clients.update', ':id') }}",
        };
    </script>

    @push('scripts')
    <script src="{{ asset('js/clients.js') }}"></script>
    @endpush

</x-layouts.app>
