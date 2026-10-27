$(document).ready(function () {

    if ($('#colorsTable').length) {
        if (typeof window.ColorRoutes === 'undefined') return;

        const colorsTable = initializeDataTable('#colorsTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.ColorRoutes.index,
                data: function (d) {
                    d.status_filter = $('#colorStatusFilter').val();
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
                                    <a href="#" class="menu-link px-3 btn-edit-color" data-id="${id}">
                                        <span class="menu-icon"><i class="fa-solid fa-pencil text-warning"></i></span>
                                        <span class="menu-title">Edit</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger btn-delete-color" data-id="${id}" data-name="${row.name}">
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
        $('#btnApplyColorFilter').on('click', function () {
            colorsTable.ajax.reload();
        });

        // Open create modal
        $('#btnOpenCreateColorModal').on('click', function () {
            $('#createColorForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#createColorModal').modal('show');
        });

        // Submit create color
        $('#createColorForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const submitBtn = $('#btnSaveColor');
            const originalText = submitBtn.html();

            $.ajax({
                url: window.ColorRoutes.store,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#createColorModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        colorsTable.ajax.reload();
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
                            const element = $(`#create_color_${key}`);
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
        $(document).on('click', '.btn-edit-color', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const url = window.ColorRoutes.show.replace(':id', id);

            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const color = response.color;
                        $('#edit_color_id').val(color.id);
                        $('#edit_color_name').val(color.name);
                        $('#edit_color_status').val(color.status);
                        $('#editColorModal').modal('show');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to retrieve color details.', 'error');
                }
            });
        });

        // Submit edit color
        $('#editColorForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const id = $('#edit_color_id').val();
            const url = window.ColorRoutes.update.replace(':id', id);
            const submitBtn = $('#btnUpdateColor');
            const originalText = submitBtn.html();

            $.ajax({
                url: url,
                type: 'POST', // Handled as PUT via standard laravel _method field
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#editColorModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        colorsTable.ajax.reload();
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
                            const element = $(`#edit_color_${key}`);
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

        // Delete Color
        $(document).on('click', '.btn-delete-color', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = window.ColorRoutes.destroy.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete Color '${name}'. This action cannot be undone!`,
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
                                colorsTable.ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to delete color.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                }
            });
        });
    }

});
