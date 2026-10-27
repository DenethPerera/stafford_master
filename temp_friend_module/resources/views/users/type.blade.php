@php
	$typeHeaders = ['User Type', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'User Types'">
	<!-- Full Width Header Bar -->
	<div class="container-fluid mb-4">
		<div class="d-flex flex-column">
			<h1 class="h3 fw-bold text-dark mb-0">User Types</h1>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item text-muted small">Manage User Types</li>
				</ol>
			</nav>
		</div>
	</div>


	<div class="container-fluid py-4">

		@if (hasAnyPrivilege(2, ['add', 'edit']))
			<div class="card border-0 shadow-sm mb-4 overflow-hidden rounded-3">
				<!-- Card Header with your specific Blue -->
				<div class="card-header border-0 py-3" style="background-color: #1C4D8D;">
					<h3 class="card-title h6 mb-0 fw-bold text-white">
						<i class="fa-solid fa-tags me-2"></i>Add User Type
					</h3>
				</div>

				<!-- Card Body -->
				<div class="card-body p-4">
					<form id="userTypeForm" method="POST" action="{{ route('users.addusertype') }}">
						@csrf
						<div class="row g-4 mb-4">
							<div class="col-12 col-md-6">
								<label for="type" class="form-label fw-bold small ">
									Type Name <span class="text-danger">*</span>
								</label>
								<input type="text" name="type" id="type" class="form-control"
									placeholder="e.g., Admin, Manager, Staff" required />
								<div class="form-text text-muted small mt-1">Enter the user type name</div>
							</div>
						</div>

						<!-- Form Actions -->
						<div class="d-flex justify-content-end gap-2">
							<button type="reset" class="btn btn-light px-4 fw-medium border">
								Clear
							</button>
							<button type="submit" class="btn btn-primary px-4 fw-medium d-flex align-items-center">
								<i class="fa-solid fa-plus me-2"></i>Add User Type
							</button>
						</div>
					</form>
				</div>
			</div>
		@endif

		{{-- User Types Table Wrapper --}}
		<div class="card border-0 shadow-sm rounded-3">
			<div class="card-body p-0">
				<x-table :headers="$typeHeaders" id="datatable">
				</x-table>
			</div>
		</div>
	</div>
	@push('scripts')
		<script>
			@if(session('success'))
				Swal.fire({ icon: 'success', title: 'Success', text: '{{ session('success') }}' });
			@endif
			@if(session('error'))
				Swal.fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}' });
			@endif
			@if ($errors->any())
				Swal.fire({ icon: 'error', title: 'Validation Error', html: '{!! implode('<br>', $errors->all()) !!}' });
			@endif

			$(document).ready(function () {
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				let table = initializeDataTable('#datatable', {
					ajax: { url: "{{ route('users.typedata') }}", type: 'GET' },
					drawCallback: function () {
						if (typeof KTMenu !== 'undefined') {
							KTMenu.createInstances();
						}
					},
					columns: [
						{ data: 'type', name: 'type' },
						{
							data: 'status',
							name: 'status',
							orderable: false,
							searchable: false,
							render: function (data) {
								if (data == 1) return '<span class="badge rounded-pill bg-success text-white px-2.5 py-1 ">Active</span>';
								if (data == 2) return '<span class="badge rounded-pill bg-warning text-white px-2.5 py-1 ">Inactive</span>';
								return '<span class="badge rounded-pill bg-danger text-white px-2.5 py-1 ">Deleted</span>';
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

								// 1. Edit User Type
								@if(checkPrivilege(2, 'edit'))
									actions += `
											<div class="menu-item px-3">
												<a class="menu-link px-3 editUser" href="#" data-id="${row.idtbl_user_type}">
													<span class="menu-icon"><i class="fa-solid fa-pen-to-square text-warning"></i></span>
													<span class="menu-title">Edit</span>
												</a>
											</div>`;
								@endif

								// 2. Status Change (Activate/Deactivate)
								@if (checkPrivilege(2, 'statuschange'))
									actions += `<div class="menu-item px-3">`;
									if (row.status == 1) {
										actions += `
											<a href="#" class="menu-link px-3 deactivateUser" data-id="${row.idtbl_user_type}">
												<span class="menu-icon"><i class="fa-solid fa-ban text-danger"></i></span>
												<span class="menu-title">Deactivate</span>
											</a>`;
									} else if (row.status == 2) {
										actions += `
											<a href="#" class="menu-link px-3 activateUser" data-id="${row.idtbl_user_type}">
												<span class="menu-icon"><i class="fa-solid fa-check-circle text-success"></i></span>
												<span class="menu-title">Activate</span>
											</a>`;
									}
									actions += `</div>`;
								@endif

								// 3. Remove/Delete
								@if (checkPrivilege(2, 'remove'))
									actions += `
											<div class="menu-item px-3">
												<a class="menu-link px-3" href="#" onclick="confirmDelete('{{ url('users/typedata') }}/${row.idtbl_user_type}', '#datatable')">
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

				// Edit user type handler
				$(document).on('click', '.editUser', function (e) {
					e.preventDefault();
					const id = $(this).data('id');
					$.ajax({
						url: `/users/typedata/${id}/edit`,
						type: 'GET',
						success: function (data) {
							$('#type').val(data.type);
							$('#userTypeForm').attr('action', `/users/typedata/${id}`);
							if ($('#userTypeForm input[name="_method"]').length === 0) {
								$('#userTypeForm').append('<input type="hidden" name="_method" value="PUT">');
							}
							$('#userTypeForm button[type="submit"]').html('<i class="fa-solid fa-save mr-1"></i>Update User Type');
							$('html, body').animate({
								scrollTop: $('#userTypeForm').offset().top - 100
							}, 400);
						},
						error: function () {
							Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load user type data' });
						}
					});
				});



				// Status update handlers
				function updateUserStatus(id, status, text) {
					Swal.fire({
						title: 'Are you sure?',
						text: text,
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Yes'
					}).then((result) => {
						if (!result.isConfirmed) return;
						$.post(`/users/typedata/${id}/status`, { status: status })
							.done(function (res) {
								Swal.fire('Success', res.message, 'success');
								$('#datatable').DataTable().ajax.reload(null, false);
							})
							.fail(function () {
								Swal.fire('Error', 'Action failed', 'error');
							});
					});
				}

				$(document).on('click', '.activateUser', function (e) {
					e.preventDefault();
					updateUserStatus($(this).data('id'), 1, 'Activate this user type?');
				});

				$(document).on('click', '.deactivateUser', function (e) {
					e.preventDefault();
					updateUserStatus($(this).data('id'), 2, 'Deactivate this user type?');
				});

				// Reset form handler
				$('#userTypeForm').on('reset', function () {
					setTimeout(() => {
						$('#userTypeForm').attr('action', "{{ route('users.addusertype') }}");
						$('#userTypeForm input[name="_method"]').remove();
						$('#userTypeForm button[type="submit"]').html('<i class="fa-solid fa-plus mr-1"></i>Add User Type');
					}, 100);
				});
			});
		</script>
	@endpush
</x-layouts.app>
