@extends('layouts.app')
@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title mb-30">
                    <h2>Sub Kategori</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card-styles">
        <div class="card-style-3 mb-30">
            <div class="card-content">
                <div class="row mb-3">
                    <div class="col-md-12 text-end">
                        <button class="btn btn-primary" id="btn-add">Tambah Sub Kategori</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="subcategories-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Nama Sub Kategori</th>
                            <th>Batas Harga</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('sub-categories.form')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTable initialization
            var table = $('#subcategories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sub-categories.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'category_name', name: 'category_name'},
                    {data: 'name', name: 'name'},
                    {data: 'price_limit', name: 'price_limit'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            // Load categories into dropdown
            function loadCategories() {
                $.ajax({
                    url: "/categories", // Pastikan route ini tersedia untuk mengambil data kategori
                    method: 'GET',
                    success: function(response) {
                        let options = '<option value="">Pilih Kategori</option>';
                        response.data.forEach(function(category) {
                            options += `<option value="${category.id}">${category.name}</option>`;
                        });
                        $('select[name="category_id"]').html(options);
                    }
                });
            }

            // Add button click handler
            $('#btn-add').click(function() {
                $('#subcategoryForm').trigger('reset');
                $('#subcategoryForm').attr('data-mode', 'add');
                loadCategories();
                $('#subcategoryModal').modal('show');
            });

            // Form submission handler
            $('#subcategoryForm').submit(function(e) {
                e.preventDefault();

                let url = "{{ route('sub-categories.store') }}";
                let method = 'POST';

                // If in edit mode, change URL and method
                if ($(this).attr('data-mode') === 'edit') {
                    const id = $(this).attr('data-id');
                    url = `/sub-categories/${id}`;
                    method = 'PUT';
                }

                // Clear previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#subcategoryModal').modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            const input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                        });
                    }
                });
            });

            // Edit button click handler
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                loadCategories();

                // Fetch subcategory data
                $.ajax({
                    url: `/sub-categories/${id}`,
                    method: 'GET',
                    success: function(data) {
                        $('#subcategoryForm').trigger('reset');
                        $('#subcategoryForm').attr('data-mode', 'edit');
                        $('#subcategoryForm').attr('data-id', id);

                        // Fill the form with subcategory data
                        $('select[name="category_id"]').val(data.category_id);
                        $('input[name="name"]').val(data.name);
                        $('input[name="price_limit"]').val(data.price_limit);

                        $('#subcategoryModal').modal('show');
                    }
                });
            });

            // Delete button click handler
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                if (confirm('Apakah Anda yakin ingin menghapus sub kategori ini?')) {
                    $.ajax({
                        url: `/sub-categories/${id}`,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                            }
                        }
                    });
                }
            });

            // Clear validation errors when modal is hidden
            $('#subcategoryModal').on('hidden.bs.modal', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });
        });
    </script>
@endpush
