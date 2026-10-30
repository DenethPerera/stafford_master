$(document).ready(function () {

    if ($('#constructionsTable').length) {
        if (typeof window.ConstructionRoutes === 'undefined') return;

        const constructionsTable = initializeDataTable('#constructionsTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.ConstructionRoutes.index,
                data: function (d) {
                    d.status_filter = $('#constructionStatusFilter').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'code', name: 'code', defaultContent: '-' },
                { data: 'status_badge', name: 'status', className: 'text-center', orderable: true },
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (id, type, row) {
                        return `
                            <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" 
                                    data-kt-menu-trigger="click" 
                                    data-kt-menu-placement="bottom-end">
                                Actions
                                <i class="fa-solid fa-chevron-down fs-9 ms-2"></i>
                            </button>
                            
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 btn-edit-construction" data-id="${id}">
                                        <span class="menu-icon"><i class="fa-solid fa-pencil text-warning"></i></span>
                                        <span class="menu-title">Edit</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger btn-delete-construction" data-id="${id}" data-name="${row.name}">
                                        <span class="menu-icon"><i class="fa-solid fa-trash text-danger"></i></span>
                                        <span class="menu-title">Delete</span>
                                    </a>
                                </div>
                            </div>`;
                    }
                }
            ],
            order: [[0, 'asc']],
            pageLength: 15,
            drawCallback: function () {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Filter trigger
        $('#btnApplyConstructionFilter').on('click', function () {
            constructionsTable.ajax.reload();
        });

        // Open create modal
        $('#btnOpenCreateConstructionModal').on('click', function () {
            $('#createConstructionForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#createConstructionModal').modal('show');
        });

        // Submit create construction
        $('#createConstructionForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const submitBtn = $('#btnSaveConstruction');
            const originalText = submitBtn.html();

            $.ajax({
                url: window.ConstructionRoutes.store,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#createConstructionModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        constructionsTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            const input = $(`#create_construction_${field}`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(messages[0]);
                        });
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to create construction.', 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Open edit modal
        $(document).on('click', '.btn-edit-construction', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const url = window.ConstructionRoutes.show.replace(':id', id);

            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const c = response.construction;
                        $('#edit_construction_id').val(c.id);
                        $('#edit_construction_name').val(c.name);
                        $('#edit_construction_code').val(c.code);
                        $('#edit_construction_status').val(c.status);
                        $('#editConstructionModal').modal('show');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to fetch construction details.', 'error');
                }
            });
        });

        // Submit edit construction
        $('#editConstructionForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const id = $('#edit_construction_id').val();
            const url = window.ConstructionRoutes.update.replace(':id', id);
            const submitBtn = $('#btnUpdateConstruction');
            const originalText = submitBtn.html();

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#editConstructionModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        constructionsTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            const input = $(`#edit_construction_${field}`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(messages[0]);
                        });
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update construction.', 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Delete construction
        $(document).on('click', '.btn-delete-construction', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = window.ConstructionRoutes.destroy.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete construction "${name}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                constructionsTable.ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete construction.', 'error');
                        }
                    });
                }
            });
        });

    }

});
