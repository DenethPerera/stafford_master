$(document).ready(function () {

    if ($('#fileTypesTable').length) {
        if (typeof window.FileTypeRoutes === 'undefined') return;

        const fileTypesTable = initializeDataTable('#fileTypesTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.FileTypeRoutes.index,
                data: function (d) {
                    d.status_filter = $('#fileTypeStatusFilter').val();
                }
            },
            columns: [
                { data: 'name', name: 'name', className: 'fw-bold' },
                { data: 'code', name: 'code', defaultContent: '—' },
                { data: 'description', name: 'description', defaultContent: '—' },
                { data: 'files_count', name: 'files_count', className: 'text-center', orderable: false, searchable: false },
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
                                    <a href="#" class="menu-link px-3 btn-edit-file-type" data-id="${id}">
                                        <span class="menu-icon"><i class="fa-solid fa-pencil text-warning"></i></span>
                                        <span class="menu-title">Edit</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger btn-delete-file-type" data-id="${id}" data-name="${row.name}">
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
        $('#btnApplyFileTypeFilter').on('click', function () {
            fileTypesTable.ajax.reload();
        });

        // Open create modal
        $('#btnOpenCreateFileTypeModal').on('click', function () {
            $('#createFileTypeForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#createFileTypeModal').modal('show');
        });

        // Submit create file type
        $('#createFileTypeForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const submitBtn = $('#btnSaveFileType');
            const originalText = submitBtn.html();

            $.ajax({
                url: window.FileTypeRoutes.store,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#createFileTypeModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        fileTypesTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            const input = $(`#create_file_type_${field}`);
                            input.addClass('is-invalid');
                            input.siblings('.invalid-feedback').text(messages[0]);
                        });
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to save file type.', 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Open edit modal
        $(document).on('click', '.btn-edit-file-type', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const url = window.FileTypeRoutes.show.replace(':id', id);

            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const type = response.file_type;
                        $('#edit_file_type_id').val(type.id);
                        $('#edit_file_type_name').val(type.name);
                        $('#edit_file_type_code').val(type.code || '');
                        $('#edit_file_type_description').val(type.description || '');
                        $('#edit_file_type_status').val(type.status);
                        $('#editFileTypeModal').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to load file type details.', 'error');
                }
            });
        });

        // Submit edit file type
        $('#editFileTypeForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const id = $('#edit_file_type_id').val();
            const url = window.FileTypeRoutes.update.replace(':id', id);
            const submitBtn = $('#btnUpdateFileType');
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
                        $('#editFileTypeModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        fileTypesTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            const input = $(`#edit_file_type_${field}`);
                            input.addClass('is-invalid');
                            input.siblings('.invalid-feedback').text(messages[0]);
                        });
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update file type.', 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Delete file type
        $(document).on('click', '.btn-delete-file-type', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = window.FileTypeRoutes.destroy.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete File Type "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                fileTypesTable.ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete file type.', 'error');
                        }
                    });
                }
            });
        });

    }
});
