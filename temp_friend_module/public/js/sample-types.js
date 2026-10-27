$(document).ready(function () {

    if ($('#sampleTypesTable').length) {
        if (typeof window.SampleTypeRoutes === 'undefined') return;

        const sampleTypesTable = initializeDataTable('#sampleTypesTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.SampleTypeRoutes.index,
                data: function (d) {
                    d.status_filter = $('#sampleTypeStatusFilter').val();
                }
            },
            columns: [
                { data: 'name', name: 'name', className: 'fw-bold' },
                { data: 'code', name: 'code', defaultContent: '—' },
                { data: 'description', name: 'description', defaultContent: '—' },
                { data: 'samples_count', name: 'samples_count', className: 'text-center', orderable: false, searchable: false },
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
                                    <a href="#" class="menu-link px-3 btn-edit-sample-type" data-id="${id}">
                                        <span class="menu-icon"><i class="fa-solid fa-pencil text-warning"></i></span>
                                        <span class="menu-title">Edit</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger btn-delete-sample-type" data-id="${id}" data-name="${row.name}">
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
        $('#btnApplySampleTypeFilter').on('click', function () {
            sampleTypesTable.ajax.reload();
        });

        // Open create modal
        $('#btnOpenCreateSampleTypeModal').on('click', function () {
            $('#createSampleTypeForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#createSampleTypeModal').modal('show');
        });

        // Submit create sample type
        $('#createSampleTypeForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const submitBtn = $('#btnSaveSampleType');
            const originalText = submitBtn.html();

            $.ajax({
                url: window.SampleTypeRoutes.store,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#createSampleTypeModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        sampleTypesTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            const input = $(`#create_sample_type_${field}`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(errors[field][0]);
                        }
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to save sample type.', 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Open edit modal
        $(document).on('click', '.btn-edit-sample-type', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const showUrl = window.SampleTypeRoutes.show.replace(':id', id);

            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: showUrl,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const st = response.sample_type;
                        $('#edit_sample_type_id').val(st.id);
                        $('#edit_sample_type_name').val(st.name);
                        $('#edit_sample_type_code').val(st.code);
                        $('#edit_sample_type_description').val(st.description);
                        $('#edit_sample_type_status').val(st.status);

                        $('#editSampleTypeModal').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to load details.', 'error');
                }
            });
        });

        // Submit edit sample type
        $('#editSampleTypeForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const id = $('#edit_sample_type_id').val();
            const updateUrl = window.SampleTypeRoutes.update.replace(':id', id);
            const submitBtn = $('#btnUpdateSampleType');
            const originalText = submitBtn.html();

            $.ajax({
                url: updateUrl,
                type: 'PUT',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#editSampleTypeModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        sampleTypesTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            const input = $(`#edit_sample_type_${field}`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(errors[field][0]);
                        }
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update sample type.', 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Delete sample type
        $(document).on('click', '.btn-delete-sample-type', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const destroyUrl = window.SampleTypeRoutes.destroy.replace(':id', id);

            Swal.fire({
                title: 'Delete Sample Type?',
                text: `Are you sure you want to delete "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: destroyUrl,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                sampleTypesTable.ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete sample type.', 'error');
                        }
                    });
                }
            });
        });

    }
});
