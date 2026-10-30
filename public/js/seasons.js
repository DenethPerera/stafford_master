$(document).ready(function () {

    if ($('#seasonsTable').length) {
        if (typeof window.SeasonRoutes === 'undefined') return;

        const seasonsTable = initializeDataTable('#seasonsTable', {
            processing: true,
            serverSide: true,
            ajax: {
                url: window.SeasonRoutes.index,
                data: function (d) {
                    d.status_filter = $('#seasonStatusFilter').val();
                }
            },
            columns: [
                { data: 'name', name: 'name', className: 'fw-bold' },
                { data: 'code', name: 'code', defaultContent: '—' },
                { data: 'description', name: 'description', defaultContent: '—' },
                { data: 'styles_count', name: 'styles_count', className: 'text-center', orderable: false, searchable: false },
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
                                    <a href="#" class="menu-link px-3 btn-edit-season" data-id="${id}">
                                        <span class="menu-icon"><i class="fa-solid fa-pencil text-warning"></i></span>
                                        <span class="menu-title">Edit</span>
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 text-danger btn-delete-season" data-id="${id}" data-name="${row.name}">
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
        $('#btnApplySeasonFilter').on('click', function () {
            seasonsTable.ajax.reload();
        });

        // Open create modal
        $('#btnOpenCreateSeasonModal').on('click', function () {
            $('#createSeasonForm')[0].reset();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#createSeasonModal').modal('show');
        });

        // Submit create season
        $('#createSeasonForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const submitBtn = $('#btnSaveSeason');
            const originalText = submitBtn.html();

            $.ajax({
                url: window.SeasonRoutes.store,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    submitBtn.prop('disabled', true);
                    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#createSeasonModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        seasonsTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            const input = $(`#create_season_${field}`);
                            input.addClass('is-invalid');
                            input.siblings('.invalid-feedback').text(messages[0]);
                        });
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to save season.', 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Open edit modal
        $(document).on('click', '.btn-edit-season', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const url = window.SeasonRoutes.show.replace(':id', id);

            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const season = response.season;
                        $('#edit_season_id').val(season.id);
                        $('#edit_season_name').val(season.name);
                        $('#edit_season_code').val(season.code || '');
                        $('#edit_season_description').val(season.description || '');
                        $('#edit_season_status').val(season.status);
                        $('#editSeasonModal').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to load season details.', 'error');
                }
            });
        });

        // Submit edit season
        $('#editSeasonForm').on('submit', function (e) {
            e.preventDefault();
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            const id = $('#edit_season_id').val();
            const url = window.SeasonRoutes.update.replace(':id', id);
            const submitBtn = $('#btnUpdateSeason');
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
                        $('#editSeasonModal').modal('hide');
                        Swal.fire('Success', response.message, 'success');
                        seasonsTable.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function (field, messages) {
                            const input = $(`#edit_season_${field}`);
                            input.addClass('is-invalid');
                            input.siblings('.invalid-feedback').text(messages[0]);
                        });
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update season.', 'error');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });

        // Delete season
        $(document).on('click', '.btn-delete-season', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');
            const url = window.SeasonRoutes.destroy.replace(':id', id);

            Swal.fire({
                title: 'Delete Season?',
                text: `Are you sure you want to delete season '${name}'? This action cannot be undone.`,
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
                                seasonsTable.ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete season.', 'error');
                        }
                    });
                }
            });
        });

    }
});
