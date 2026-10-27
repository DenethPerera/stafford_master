$(document).ready(function () {

    if ($('#scalesTable').length) {
        if (typeof window.ScaleRoutes === 'undefined') return;

        const scalesTable = initializeDataTable('#scalesTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.ScaleRoutes.index,
                data: function (d) {
                    d.status_filter = $('#scaleStatusFilter').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { 
                    data: 'percentage_value', 
                    name: 'percentage_value', 
                    className: 'text-end font-mono',
                    render: function (data) {
                        return parseFloat(data).toFixed(2) + '%';
                    }
                },
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
                                    <a href="#" class="menu-link px-3 btn-edit-scale" data-id="${id}">
                                        <span class="menu-icon"><i class="fa-solid fa-pencil text-warning"></i></span>
                                        <span class="menu-title">Edit</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger btn-delete-scale" data-id="${id}" data-name="${row.name}">
                                        <span class="menu-icon"><i class="fa-solid fa-trash text-danger"></i></span>
                                        <span class="menu-title">Delete</span>
                                    </a>
                                </div>
                            </div>`;
                    }
                }
            ],
            order: [[1, 'asc']],
            pageLength: 15,
            drawCallback: function () {
                if (typeof KTMenu !== 'undefined') {
                    KTMenu.createInstances();
                }
            }
        });

        // Filter trigger
        $('#btnApplyScaleFilter').on('click', function () {
            scalesTable.ajax.reload();
        });

        // Open create modal
        $('#btnOpenCreateScaleModal').on('click', function () {
            $('#createScaleForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#createScaleModal').modal('show');
        });

        // Submit create scale
        $('#createScaleForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const submitBtn = $('#btnSaveScale');
            const originalText = submitBtn.html();

            $.ajax({
                url: window.ScaleRoutes.store,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#createScaleModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        scalesTable.ajax.reload();
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
                            const element = $(`#create_scale_${key}`);
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
        $(document).on('click', '.btn-edit-scale', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const url = window.ScaleRoutes.show.replace(':id', id);

            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const scale = response.scale;
                        $('#edit_scale_id').val(scale.id);
                        $('#edit_scale_name').val(scale.name);
                        $('#edit_scale_percentage_value').val(scale.percentage_value);
                        $('#edit_scale_status').val(scale.status);
                        $('#editScaleModal').modal('show');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to retrieve scale details.', 'error');
                }
            });
        });

        // Submit edit scale
        $('#editScaleForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const id = $('#edit_scale_id').val();
            const url = window.ScaleRoutes.update.replace(':id', id);
            const submitBtn = $('#btnUpdateScale');
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
                        $('#editScaleModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        scalesTable.ajax.reload();
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
                            const element = $(`#edit_scale_${key}`);
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

        // Delete Scale
        $(document).on('click', '.btn-delete-scale', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = window.ScaleRoutes.destroy.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete Scale '${name}'. This action cannot be undone!`,
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
                                scalesTable.ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to delete scale.';
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                }
            });
        });
    }

});
