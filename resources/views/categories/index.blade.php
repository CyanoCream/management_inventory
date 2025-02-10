@extends('layouts.app')
@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title mb-30">
                    <h2>Kategori</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card-styles">
        <div class="card-style-3 mb-30">
            <div class="card-content">
                <div class="row mb-3">
                    <div class="col-md-12 text-end">
                        <button class="btn btn-primary" id="btn-add">Tambah Kategori</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="categories-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Kategori</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('categories.form')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTable initialization
            var table = $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('categories.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'code', name: 'code'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            // Add Category button click handler
            $('#btn-add').click(function() {
                $('#categoryForm').trigger('reset');
                $('#categoryForm').attr('data-mode', 'add');
                $('#categoryModal').modal('show');
            });

            // Form submission handler
            $('#categoryForm').submit(function(e) {
                e.preventDefault();

                let url = "{{ route('categories.store') }}";
                let method = 'POST';

                // If in edit mode, change URL and method
                if ($(this).attr('data-mode') === 'edit') {
                    const id = $(this).attr('data-id');
                    url = `/categories/${id}`;
                    method = 'PUT';
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#categoryModal').modal('hide');
                            table.ajax.reload();
                            // You might want to add a success message here
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        // Handle validation errors here
                        // You might want to display error messages to the user
                    }
                });
            });

            // Edit button click handler
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');

                // Fetch category data
                $.get(`/categories/${id}/edit`, function(data) {
                    $('#categoryForm').trigger('reset');
                    $('#categoryForm').attr('data-mode', 'edit');
                    $('#categoryForm').attr('data-id', id);

                    // Fill the form with category data
                    $('input[name="code"]').val(data.code);
                    $('input[name="name"]').val(data.name);

                    $('#categoryModal').modal('show');
                });
            });

            // Delete button click handler
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                if (confirm('Are you sure you want to delete this category?')) {
                    $.ajax({
                        url: `/categories/${id}`,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                // You might want to add a success message here
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
