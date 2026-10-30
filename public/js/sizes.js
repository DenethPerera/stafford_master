$(document).ready(function () {

    if ($('#sizesTable').length) {
        if (typeof window.SizeRoutes === 'undefined') return;

        const sizesTable = initializeDataTable('#sizesTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.SizeRoutes.index,
                data: function (d) {
                    d.status_filter = $('#sizeStatusFilter').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
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
                                    <a href="#" class="menu-link px-3 btn-edit-size" data-id="${id}">
                                        <span class="menu-icon"><i class="fa-solid fa-pencil text-warning"></i></span>
                                        <span class="menu-title">Edit</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger btn-delete-size" data-id="${id}" data-name="${row.name}">
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
        $('#btnApplySizeFilter').on('click', function () {
            sizesTable.ajax.reload();
        });

        // Open create modal
        $('#btnOpenCreateSizeModal').on('click', function () {
            $('#createSizeForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#createSizeModal').modal('show');
        });

        // Submit create size
        $('#createSizeForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const submitBtn = $('#btnSaveSize');
            const originalText = submitBtn.html();

            $.ajax({
                url: window.SizeRoutes.store,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#createSizeModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        sizesTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function (key) {
                            const element = $(`#create_size_${key}`);
                            if (element.length) {
                                element.addClass('is-invalid');
                                element.siblings('.invalid-feedback').text(errors[key][0]);
                            }
                        });
                    } else {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                        Swal.fire('Error', msg, 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Open Edit Modal
        $(document).on('click', '.btn-edit-size', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const url = window.SizeRoutes.show.replace(':id', id);

            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const size = response.size;
                        $('#edit_size_id').val(size.id);
                        $('#edit_size_name').val(size.name);
                        $('#edit_size_status').val(size.status);
                        $('#editSizeModal').modal('show');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to retrieve size details.', 'error');
                }
            });
        });

        // Submit edit size
        $('#editSizeForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const id = $('#edit_size_id').val();
            const url = window.SizeRoutes.update.replace(':id', id);
            const submitBtn = $('#btnUpdateSize');
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
                        $('#editSizeModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        sizesTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function (key) {
                            const element = $(`#edit_size_${key}`);
                            if (element.length) {
                                element.addClass('is-invalid');
                                element.siblings('.invalid-feedback').text(errors[key][0]);
                            }
                        });
                    } else {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                        Swal.fire('Error', msg, 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Delete Size
        $(document).on('click', '.btn-delete-size', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = window.SizeRoutes.destroy.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete Size '${name}'. This action cannot be undone!`,
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
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE'
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                sizesTable.ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to delete size.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                }
            });
        });
    }

});
