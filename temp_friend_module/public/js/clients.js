$(document).ready(function () {
    // ─── CLIENT MANAGEMENT INDEX ───────────────────────────────────────
    if ($('#clientTable').length) {
        if (typeof window.ClientRoutes === 'undefined') return;

        const clientTable = initializeDataTable("#clientTable", {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.ClientRoutes.index,
                data: function (d) {
                    d.search.value = $("#clientSearch").val();
                    d.status_filter = $("#clientStatusFilter").val();
                },
            },
            columns: [
                { data: "name", name: "name" },
                { data: "phone", name: "phone", defaultContent: "-" },
                { data: "address", name: "address", defaultContent: "-" },
                {
                    data: "job_orders_count",
                    name: "job_orders_count",
                    searchable: false,
                },
                {
                    data: "status_badge",
                    name: "status",
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "id",
                    name: "id",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                    render: function (id, type, row) {
                        const safeName = (row.name || "").replace(/'/g, "\\'");
                        return `
                            <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                Actions
                                <i class="fa-solid fa-chevron-down fs-9 ms-2"></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" onclick="openEditModal(${id})">
                                        <span class="menu-icon"><i class="fa-solid fa-pen-to-square text-warning"></i></span>
                                        <span class="menu-title">Edit</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger" onclick="deleteClient(${id}, '${safeName}')">
                                        <span class="menu-icon"><i class="fa-solid fa-trash text-danger"></i></span>
                                        <span class="menu-title">Delete</span>
                                    </a>
                                </div>
                            </div>`;
                    },
                },
            ],
            order: [[0, "asc"]],
            pageLength: 15,
            language: {
                emptyTable: "No clients found.",
                processing: '<div class="text-slate-500 text-sm py-4">Loading clients...</div>',
            },
        });

        $('#btnApplyFilter').on('click', function () {
            clientTable.ajax.reload();
        });

        $('#clientSearch').on('keyup', function () {
            clientTable.ajax.reload();
        });

        $('#btnOpenCreateModal').on('click', function (e) {
            e.preventDefault();
            $('#createClientForm')[0].reset();
            clearFormErrors('#createClientForm');
            $('#createClientModal').modal('show');
        });

        $('#createClientForm').on('submit', function (e) {
            e.preventDefault();
            const $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true).find('.btn-text').text('Saving...');
            clearFormErrors('#createClientForm');

            $.ajax({
                url: $(this).data('store-url'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    if (res.success) {
                        $('#createClientModal').modal('hide');
                        clientTable.ajax.reload(null, false);
                        showToast(res.message, 'success');
                    } else {
                        showToast(res.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        displayFormErrors('#createClientForm', xhr.responseJSON.errors);
                    } else {
                        showToast('An unexpected error occurred.', 'error');
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false).find('.btn-text').text('Save Client');
                }
            });
        });

        $('#editClientForm').on('submit', function (e) {
            e.preventDefault();
            const clientId = $('#edit_client_id').val();
            const url      = window.ClientRoutes.update.replace(':id', clientId);
            const $btn     = $(this).find('button[type="submit"]');

            $btn.prop('disabled', true).find('.btn-text').text('Updating...');
            clearFormErrors('#editClientForm');

            $.ajax({
                url:    url,
                method: 'POST',
                data:   $(this).serialize(),
                success: function (res) {
                    if (res.success) {
                        $('#editClientModal').modal('hide');
                        clientTable.ajax.reload(null, false);
                        showToast(res.message, 'success');
                    } else {
                        showToast(res.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        displayFormErrors('#editClientForm', xhr.responseJSON.errors);
                    } else {
                        showToast('An unexpected error occurred.', 'error');
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false).find('.btn-text').text('Update Client');
                }
            });
        });
    }
});

// ─── CLIENT MANAGEMENT GLOBAL FUNCTIONS ─────────────────────────────────

window.openEditModal = function(id) {
    if (typeof window.ClientRoutes === 'undefined') return;
    
    clearFormErrors('#editClientForm');
    const url = window.ClientRoutes.show.replace(':id', id);

    $.get(url, function (data) {
        $('#edit_client_id').val(data.id);
        $('#edit_name').val(data.name);
        $('#edit_phone').val(data.phone);
        $('#edit_address').val(data.address);
        $('#edit_status').val(data.status).trigger('change');
        
        $('#editClientModal').modal('show');
    });
};

window.deleteClient = function(id, name) {
    if (typeof window.ClientRoutes === 'undefined') return;

    Swal.fire({
        title: 'Delete Client?',
        text: `"${name}" will be permanently removed.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f5a623',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (!result.isConfirmed) return;

        const url = window.ClientRoutes.destroy.replace(':id', id);

        $.ajax({
            url:    url,
            method: 'POST',
            data:   { _method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.success) {
                    $('#clientTable').DataTable().ajax.reload(null, false);
                    showToast(res.message, 'success');
                } else {
                    showToast(res.message, 'error');
                }
            },
            error: function (xhr) {
                if(xhr.status === 422) {
                    showToast(xhr.responseJSON.message, 'error');
                } else {
                    showToast('Failed to delete client.', 'error');
                }
            }
        });
    });
};

function clearFormErrors(formSelector) {
    $(formSelector).find('.form-input-custom').removeClass('border-red-500 is-invalid');
    $(formSelector).find('.invalid-feedback').text('');
}

function displayFormErrors(formSelector, errors) {
    $.each(errors, function (field, messages) {
        const input = $(formSelector).find(`[name="${field}"]`);
        input.addClass('border-red-500 is-invalid');
        input.siblings('.invalid-feedback').text(messages[0]);
    });
}
