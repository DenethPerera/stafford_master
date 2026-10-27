@php
    $privilegeHeaders = ['User', 'Menu', 'Access', 'Add', 'Edit', 'Status Change', 'Delete', 'Active', 'Actions'];
@endphp



<x-layouts.app :title="'User Privileges'">
    <!-- Full Width Header Bar -->
    <div class="container-fluid mb-4">
        <div class="d-flex flex-column">
            <h1 class="h3 fw-bold text-dark mb-0">User Privileges</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item text-muted small">Assign Privileges to User</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid py-4">

        @if (hasAnyPrivilege(1, ['edit', 'add']))
            <div class="card border-0 shadow-sm mb-4 overflow-hidden rounded-3">
                <!-- Header with custom Gradient -->
                <div class="card-header border-0 py-3" style="background: linear-gradient(to right, #ecfdf5, #f0fdfa);">
                    <h3 class="card-title h6 mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-shield-halved me-2 text-success"></i>Assign Privileges to User
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    <form id="privilegeForm">
                        @csrf
                        <input type="hidden" id="privilege_id" name="privilege_id" />

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">User <span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-select shadow-none" required>
                                    <option value="">Select User</option>
                                    @isset($users)
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->username }}</option>
                                        @endforeach
                                    @else
                                        <option value="">No users available</option>
                                    @endisset
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">Access Menu <span class="text-danger">*</span></label>
                            <select class="form-select shadow-none" id="menu_id" name="menu_id" multiple required>
                                @isset($menus)
                                    @foreach ($menus as $menu)
                                        <option value="{{ $menu->idtbl_menu_list }}">{{ $menu->menu }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            <div class="form-text text-muted small mt-1">Select one or more menus to assign permissions</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">User Privileges <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <!-- Access Checkbox -->
                                <div class="col-6 col-md">
                                    <div class="form-check p-3 border rounded bg-light d-flex align-items-center h-100 transition-all hover-bg-white shadow-hover">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="access_status" id="access_status" value="1" required style="cursor: pointer;" />
                                        <label class="form-check-label small fw-bold text-dark mb-0" for="access_status" style="cursor: pointer;">
                                            Access <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Add Checkbox -->
                                <div class="col-6 col-md">
                                    <div class="form-check p-3 border rounded bg-light d-flex align-items-center h-100 transition-all hover-bg-white shadow-hover">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="add" id="add" value="1" style="cursor: pointer;" />
                                        <label class="form-check-label small fw-bold text-dark mb-0" for="add" style="cursor: pointer;">Add</label>
                                    </div>
                                </div>
                                <!-- Edit Checkbox -->
                                <div class="col-6 col-md">
                                    <div class="form-check p-3 border rounded bg-light d-flex align-items-center h-100 transition-all hover-bg-white shadow-hover">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="edit" id="edit" value="1" style="cursor: pointer;" />
                                        <label class="form-check-label small fw-bold text-dark mb-0" for="edit" style="cursor: pointer;">Edit</label>
                                    </div>
                                </div>
                                <!-- Status Checkbox -->
                                <div class="col-6 col-md">
                                    <div class="form-check p-3 border rounded bg-light d-flex align-items-center h-100 transition-all hover-bg-white shadow-hover">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="statuschange" id="statuschange" value="1" style="cursor: pointer;" />
                                        <label class="form-check-label small fw-bold text-dark mb-0" for="statuschange" style="cursor: pointer;">Status</label>
                                    </div>
                                </div>
                                <!-- Delete Checkbox -->
                                <div class="col-6 col-md">
                                    <div class="form-check p-3 border rounded bg-light d-flex align-items-center h-100 transition-all hover-bg-white shadow-hover">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="remove" id="remove" value="1" style="cursor: pointer;" />
                                        <label class="form-check-label small fw-bold text-dark mb-0" for="remove" style="cursor: pointer;">Delete</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" class="btn btn-light px-4 fw-bold">Clear</button>
                            <button type="submit" id="submitBtn" class="btn btn-primary px-4 fw-bold shadow-sm d-flex align-items-center">
                                <i class="fa-solid fa-plus me-2"></i>Add Privilege
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Privileges Table Wrapper --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <x-table :headers="$privilegeHeaders" id="privilegeTable">
                </x-table>
            </div>
        </div>
    </div>

    <!-- Optional CSS for the hover effects missing in standard Bootstrap -->
    <style>
        .hover-bg-white:hover { background-color: #ffffff !important; border-color: #198754 !important; }
        .shadow-hover:hover { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }
        .transition-all { transition: all 0.2s ease-in-out; }
        .page-header { border-bottom: 1px solid rgba(0,0,0,0.1); }
    </style>

</x-layouts.app>


	<script>
		$(document).ready(function () {
			$('#menu_id').select2({
				placeholder: 'Select menus',
				closeOnSelect: false,
				allowClear: true,
				minimumResultsForSearch: 0,
				width: '100%'
			});
		});

		$(document).ready(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			function getErrorMessage(xhr, fallback = 'An error occurred') {
				if (xhr && xhr.responseJSON) {
					if (xhr.responseJSON.message) return xhr.responseJSON.message;
					if (xhr.responseJSON.errors) {
						const firstKey = Object.keys(xhr.responseJSON.errors)[0];
						if (firstKey && xhr.responseJSON.errors[firstKey] && xhr.responseJSON.errors[firstKey][0]) {
							return xhr.responseJSON.errors[firstKey][0];
						}
					}
				}
				return fallback;
			}

			function badgeYesNo(val) {
				return val == 1
					? '<span class="badge rounded-pill bg-success-subtle text-success-emphasis px-2">Yes</span>'
					: '<span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-2">No</span>';
			}

			let privilegeTable = initializeDataTable('#privilegeTable', {
				ajax: { url: "{{ route('users.privilege.data') }}", type: 'GET' },
				drawCallback: function () {
					if (typeof KTMenu !== 'undefined') {
						KTMenu.createInstances();
					}
				},
				columns: [
					{ data: 'user.name', name: 'user.name', defaultContent: '—' },
					{ data: 'menu.menu', name: 'menu.menu', defaultContent: '—' },
					{
						data: 'access_status', name: 'access_status',
						render: function (data) { return badgeYesNo(data); }
					},
					{
						data: 'add', name: 'add',
						render: function (data) { return badgeYesNo(data); }
					},
					{
						data: 'edit', name: 'edit',
						render: function (data) { return badgeYesNo(data); }
					},
					{
						data: 'statuschange', name: 'statuschange',
						render: function (data) { return badgeYesNo(data); }
					},
					{
						data: 'remove', name: 'remove',
						render: function (data) { return badgeYesNo(data); }
					},
					{
						data: 'status', name: 'status',
						render: function (data) {
							return data == 1
								? '<span class="badge rounded-pill bg-success text-white px-2.5 py-1 ">Active</span>'
								: '<span class="badge rounded-pill bg-danger text-white px-2.5 py-1 ">Inactive</span>';
						}
					},
					{
						data: null,
						orderable: false,
						searchable: false,
						className: 'text-center',
						render: function (data, type, row) {
    // Dropdown Trigger Button
    let actions = `
        <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
            Actions
            <i class="ki-duotone ki-down fs-5 ms-1"></i>
        </button>
        <!-- Dropdown Menu -->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">`;

    // 1. Edit Privilege
    @if(checkPrivilege(1, 'edit'))
        actions += `
            <div class="menu-item px-3">
                <a class="menu-link px-3 editPrivilege" href="#" data-id="${row.idtbl_user_privilege}">
                    <span class="menu-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                    <span class="menu-title">Edit</span>
                </a>
            </div>`;
    @endif

    // 2. Status Change (Activate/Deactivate)
    @if (checkPrivilege(1, 'statuschange'))
        actions += `<div class="menu-item px-3">`;
        if (row.status == 1) {
            actions += `
                <a href="#" class="menu-link px-3 deactivatePrivilege" data-id="${row.idtbl_user_privilege}">
                    <span class="menu-icon"><i class="fa-solid fa-ban"></i></span>
                    <span class="menu-title">Deactivate</span>
                </a>`;
        } else {
            actions += `
                <a href="#" class="menu-link px-3 activatePrivilege" data-id="${row.idtbl_user_privilege}">
                    <span class="menu-icon"><i class="fa-solid fa-check-circle"></i></span>
                    <span class="menu-title">Activate</span>
                </a>`;
        }
        actions += `</div>`;
    @endif

    // 3. Remove/Delete
    @if (checkPrivilege(1, 'remove'))
        actions += `
            <div class="menu-item px-3">
                <a class="menu-link px-3" href="#" onclick="confirmDelete('{{ url('users/privilege') }}/${row.idtbl_user_privilege}/delete', '#privilegeTable')">
                    <span class="menu-icon"><i class="fa-solid fa-trash"></i></span>
                    <span class="menu-title">Delete</span>
                </a>
            </div>`;
    @endif

    actions += '</div>'; // Close dropdown menu div
    return actions;
}
					}
				]
			});

			// Status handlers
			$(document).on('click', '.activatePrivilege', function (e) {
				e.preventDefault();
				changePrivilegeStatus($(this).data('id'), 1);
			});
			$(document).on('click', '.deactivatePrivilege', function (e) {
				e.preventDefault();
				changePrivilegeStatus($(this).data('id'), 0);
			});

			function changePrivilegeStatus(id, status) {
				Swal.fire({
					title: 'Are you sure?',
					text: status == 1 ? 'Activate this privilege?' : 'Deactivate this privilege?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Yes'
				}).then((result) => {
					if (!result.isConfirmed) return;
					$.ajax({
						url: `/users/privilege/${id}/status`,
						type: 'POST',
						data: { status: status, _token: '{{ csrf_token() }}' },
						success: function (res) {
							Swal.fire('Success', res.message, 'success');
							$('#privilegeTable').DataTable().ajax.reload(null, false);
						},
						error: function (xhr) {
							Swal.fire('Error', getErrorMessage(xhr, 'Action failed'), 'error');
						}
					});
				});
			}

			// Submit privilege form
			let editingPrivilegeId = null;
			$('#privilegeForm').on('submit', function (e) {
				e.preventDefault();

				const userId = $('#user_id').val();
				const menuIds = $('#menu_id').val();
				const hasAccess = $('#access_status').is(':checked');

				if (!userId || !menuIds || menuIds.length === 0) {
					Swal.fire({ icon: 'warning', title: 'Required Fields', text: 'Please select user and at least one menu' });
					return;
				}

				if (!hasAccess) {
					Swal.fire({ icon: 'warning', title: 'Required Field', text: 'The "Access" privilege is required. User must have access permission to use any menu.' });
					return;
				}

				const formData = {
					_token: '{{ csrf_token() }}',
					user_id: userId,
					menu_id: menuIds,
					access_status: $('#access_status').is(':checked') ? 1 : 0,
					add: $('#add').is(':checked') ? 1 : 0,
					edit: $('#edit').is(':checked') ? 1 : 0,
					statuschange: $('#statuschange').is(':checked') ? 1 : 0,
					remove: $('#remove').is(':checked') ? 1 : 0
				};

				let submitBtn = $('#submitBtn');
				let originalHtml = submitBtn.html();
				submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-2"></i>Processing...');

				const isEditing = !!editingPrivilegeId;
				let requestUrl = '{{ route('users.privilege.add') }}';
				let requestType = 'POST';
				if (isEditing) {
					requestUrl = `/users/privilege/${editingPrivilegeId}`;
					requestType = 'PUT';
					formData._method = 'PUT';
				}

				$.ajax({
					url: requestUrl,
					type: requestType,
					data: formData,
					success: function (response) {
						Swal.fire({
							icon: 'success',
							title: 'Success',
							text: response.message || (isEditing ? 'Privilege updated' : 'Privileges assigned'),
							timer: 2000
						}).then(() => {
							$('#privilegeForm')[0].reset();
							$('#user_id').val(null).trigger('change');
							$('#menu_id').val(null).trigger('change');
							editingPrivilegeId = null;
							$('#submitBtn').html('<i class="fa-solid fa-plus mr-1"></i>Add Privilege');
						});
						$('#privilegeTable').DataTable().ajax.reload(null, false);
					},
					error: function (xhr) {
						Swal.fire({ icon: 'error', title: 'Error', text: getErrorMessage(xhr, 'An error occurred') });
					},
					complete: function () {
						submitBtn.prop('disabled', false).html(originalHtml);
					}
				});
			});

			// Reset form
			$('#privilegeForm').on('reset', function () {
				setTimeout(() => {
					$('#user_id').val(null).trigger('change');
					$('#menu_id').val(null).trigger('change');
					editingPrivilegeId = null;
					$('#submitBtn').html('<i class="fa-solid fa-plus mr-1"></i>Add Privilege');
				}, 100);
			});

			// Edit privilege handler
			$(document).on('click', '.editPrivilege', function (e) {
				e.preventDefault();
				const id = $(this).data('id');
				$.ajax({
					url: `/users/privilege/${id}/edit`,
					type: 'GET',
					success: function (data) {
						editingPrivilegeId = data.idtbl_user_privilege;
						$('#privilege_id').val(editingPrivilegeId);
						$('#user_id').val(data.tbl_user_idtbl_user).trigger('change');
						$('#menu_id').val([data.tbl_menu_list_idtbl_menu_list]).trigger('change');
						$('#access_status').prop('checked', data.access_status == 1);
						$('#add').prop('checked', data.add == 1);
						$('#edit').prop('checked', data.edit == 1);
						$('#statuschange').prop('checked', data.statuschange == 1);
						$('#remove').prop('checked', data.remove == 1);
						$('#submitBtn').html('<i class="fa-solid fa-save mr-1"></i>Update Privilege');
						$('html, body').animate({ scrollTop: $('#privilegeForm').offset().top - 100 }, 300);
					},
					error: function (xhr) {
						Swal.fire('Error', getErrorMessage(xhr, 'Failed to load privilege'), 'error');
					}
				});
			});


		});
	</script>
