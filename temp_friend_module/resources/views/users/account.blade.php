@php
    $userHeaders = ['Name', 'Username', 'User Type', 'Status', 'Actions'];
@endphp

<x-layouts.app :title="'User Accounts'">
    <!-- Simple Bootstrap Header -->
   

	<div class="container-fluid mb-4">
        <div class="d-flex flex-column">
            <h1 class="h3 fw-bold text-dark mb-0">User Account</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item text-muted small">Manage System Users and access levels</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid">
        @if (hasAnyPrivilege(3, ['add', 'edit']))
            <div class="card shadow-sm mb-4">
                <!-- Using bg-primary for the header as it's a default BS class -->
                 <div class="card-header border-0 py-3" style="background-color: #1C4D8D;">
                <h3 class="card-title h6 mb-0 fw-bold text-white">
                    Add User Account
                </h3>
            </div>
                <div class="card-body p-4">
                    <form id="userAccountForm" enctype="multipart/form-data" method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email</label>
                                <input type="email" name="email" id="email" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">User Type <span class="text-danger">*</span></label>
                                <select name="user_type" id="user_type" class="form-select" required>
                                    <option value="">Select User Type</option>
                                    @foreach($user_type as $type)
                                        <option value="{{ $type->idtbl_user_type }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Retype Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Profile Image</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*" />
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="reset" id="resetForm" class="btn btn-light border me-2">Clear</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa-solid fa-plus me-1"></i>Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Table Section --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <x-table :headers="$userHeaders" id="datatable"></x-table>
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
				ajax: { url: "{{ route('users.data') }}", type: 'GET' },
				drawCallback: function () {
					if (typeof KTMenu !== 'undefined') {
						KTMenu.createInstances();
					}
				},
				columns: [
					{ data: 'name', name: 'name' },
					{ data: 'username', name: 'username' },
					{
						data: 'user_type.type',
						name: 'user_type.type',
						defaultContent: '<span class="text-gray-400 italic">—</span>'
					},
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
						className: 'text-center',
						orderable: false,
						searchable: false,
						render: function (data, type, row) {
							let actions = `
									<button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
										<i class="ki-duotone ki-down fs-5 ms-1"></i>
									</button>
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
										@if(checkPrivilege(3, 'edit'))
											<div class="menu-item">
												<a class="menu-link editUser" href="#" data-id="${row.id}">
													<span class="menu-icon"><i class="fa-solid fa-pen text-warning"></i></span>
													<span class="menu-title">Edit</span>
												</a>
											</div>
										@endif
										<div class="menu-item">`;

							@if (checkPrivilege(3, 'statuschange'))
								if (row.status == 1) {
									actions += `<a href="#" class="menu-link deactivateUser" data-id="${row.id}">
											<span class="menu-icon"><i class="fa-solid fa-ban text-danger"></i></span>
											<span class="menu-title">Deactivate</span>
										</a>`;
								} else if (row.status == 2) {
									actions += `<a href="#" class="menu-link activateUser" data-id="${row.id}">
											<span class="menu-icon"><i class="fa-solid fa-check text-success"></i></span>
											<span class="menu-title">Activate</span>
										</a>`;
								}
							@endif
							

							@if (checkPrivilege(3, 'remove'))

								actions += `
											</div>
											<div class="menu-item">
												<a class="menu-link" href="#" onclick="confirmDelete('{{ url('users') }}/${row.id}', '#datatable')" >
													<span class="menu-icon"><i class="fa-solid fa-trash-can text-danger"></i></span>
													<span class="menu-title">Delete</span>
												</a>
											</div>
										</div>
									`;
							@endif
							actions += '</div>';
							return actions;
						}
						
					}
				]
			});

			// Edit user handler
			$(document).on('click', '.editUser', function (e) {
				e.preventDefault();
				const id = $(this).data('id');
				$.ajax({
					url: `/users/${id}/edit`,
					type: 'GET',
					success: function (data) {
						$('#name').val(data.name);
						$('#username').val(data.username);
						$('#email').val(data.email);
						$('#user_type').val(data.tbl_user_type_idtbl_user_type);

						$('#password').val('');
						$('#password_confirmation').val('');

						$('#password').prop('required', false);
						$('#password_confirmation').prop('required', false);

						$('#userAccountForm').attr('action', `{{ url('/users') }}/${id}`);

						if ($('#userAccountForm input[name="_method"]').length === 0) {
							$('#userAccountForm').append('<input type="hidden" name="_method" value="PUT">');
						}

						$('#userAccountForm button[type="submit"]').html('<i class="fa-solid fa-save mr-1"></i>Update User');

						$('html, body').animate({
							scrollTop: $('#userAccountForm').offset().top - 100
						}, 400);
					},
					error: function () {
						Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load user data' });
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

					$.post(`/users/${id}/status`, { status: status })
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
				updateUserStatus($(this).data('id'), 1, 'Activate this user?');
			});

			$(document).on('click', '.deactivateUser', function (e) {
				e.preventDefault();
				updateUserStatus($(this).data('id'), 2, 'Deactivate this user?');
			});

			// Reset form handler
			$('#resetForm').on('click', function () {
				$('#userAccountForm')[0].reset();
				$('#userAccountForm').attr('action', "{{ route('users.store') }}");
				$('#userAccountForm input[name="_method"]').remove();
				$('#password').prop('required', true);
				$('#password_confirmation').prop('required', true);

				$('#userAccountForm button[type="submit"]').html('<i class="fa-solid fa-plus mr-1"></i>Add User');
			});
		});
	</script>
@endpush
</x-layouts.app>
