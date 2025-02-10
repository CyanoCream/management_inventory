@extends('layouts.app')
@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title mb-30">
                    <h2>Manajemen User</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card-styles">
        <div class="card-style-3 mb-30">
            <div class="card-content">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="role-filter">
                            <option value="">Semua Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Operator">Operator</option>
                        </select>
                    </div>
                    <div class="col-md-9 text-end">
                        <button class="btn btn-primary" id="btn-add">Tambah User</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="users-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('users.form')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable (kode yang sudah ada)
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.index') }}",
                    data: function(d) {
                        d.role = $('#role-filter').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'username', name: 'username'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'role', name: 'role'},
                    {data: 'is_locked', name: 'is_locked'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            // Filter berdasarkan role
            $('#role-filter').change(function() {
                table.draw();
            });

            // Handle tombol Tambah User
            $('#btn-add').click(function() {
                $('#userForm').trigger('reset');
                $('#userForm input[name="password"]').prop('required', true);
                $('#userModal').modal('show');
            });

            // Handle submit form
            $('#userForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.data('id') ?
                    `/users/${form.data('id')}` :
                    '/users';
                var method = form.data('id') ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#userModal').modal('hide');
                            table.draw();
                            // Reset form data
                            form.removeData('id');
                            form.trigger('reset');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                $(`[name="${key}"]`).addClass('is-invalid')
                                    .siblings('.invalid-feedback').remove();
                                $(`[name="${key}"]`).after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                            });
                        }
                    }
                });
            });

            // Handle tombol Edit
            $(document).on('click', '.edit-btn', function() {
                var id = $(this).data('id');

                // Fetch user data
                $.get(`/users/${id}`, function(user) {
                    var form = $('#userForm');
                    form.data('id', id);

                    // Fill form with user data
                    form.find('[name="username"]').val(user.username);
                    form.find('[name="name"]').val(user.name);
                    form.find('[name="email"]').val(user.email);
                    form.find('[name="role"]').val(user.role);

                    // Make password optional for edit
                    form.find('[name="password"]').prop('required', false);

                    $('#userModal').modal('show');
                });
            });

            // Handle tombol Lock/Unlock
            $(document).on('click', '.lock-btn, .unlock-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: `/users/${id}/toggle-lock`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            table.draw();
                        }
                    }
                });
            });

            // Reset validation state when modal is hidden
            $('#userModal').on('hidden.bs.modal', function() {
                var form = $('#userForm');
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
                form.removeData('id');
            });
        });
    </script>
@endpush
