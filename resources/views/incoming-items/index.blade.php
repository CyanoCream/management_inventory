@extends('layouts.app')
@section('content')
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title mb-30">
                    <h2>Barang Masuk</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card-styles">
        <div class="card-style-3 mb-30">
            <div class="card-content">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select" id="category-filter">
                            <option value="">Semua Kategori</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="subcategory-filter">
                            <option value="">Semua Sub Kategori</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="year-filter">
                            <option value="">Semua Tahun</option>
                            @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary" id="btn-add">Tambah Baru</button>
                        <button class="btn btn-success" id="btn-export">Export Excel</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="incoming-items-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Sub Kategori</th>
                            <th>Asal Barang</th>
                            <th>Operator</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('incoming-items.form')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTable initialization
            var table = $('#incoming-items-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('incoming-items.index') }}",
                    data: function(d) {
                        d.category_id = $('#category-filter').val();
                        d.sub_category_id = $('#subcategory-filter').val();
                        d.year = $('#year-filter').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'category', name: 'category'},
                    {data: 'sub_category', name: 'sub_category'},
                    {data: 'source', name: 'source'},
                    {data: 'operator', name: 'operator'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            // Load initial categories for filters
            loadCategories('#category-filter');

            // Category filter change handler
            $('#category-filter').change(function() {
                const categoryId = $(this).val();
                if (categoryId) {
                    loadSubCategories('#subcategory-filter', categoryId);
                } else {
                    $('#subcategory-filter').html('<option value="">Semua Sub Kategori</option>');
                }
                table.draw();
            });

            // Other filters change handler
            $('#subcategory-filter, #year-filter').change(function() {
                table.draw();
            });

            // Function to load categories
            function loadCategories(selector) {
                $.get("/categories", function(response) {
                    let options = '<option value="">Semua Kategori</option>';
                    response.data.forEach(function(category) {
                        options += `<option value="${category.id}">${category.name}</option>`;
                    });
                    $(selector).html(options);
                });
            }

            // Function to load sub categories
            function loadSubCategories(selector, categoryId) {
                $.get(`/sub-categories/by-category/${categoryId}`, function(response) {
                    let options = '<option value="">Semua Sub Kategori</option>';
                    response.forEach(function(subCategory) {
                        options += `<option value="${subCategory.id}" data-price-limit="${subCategory.price_limit}">${subCategory.name}</option>`;
                    });
                    $(selector).html(options);
                });
            }

            // Function to create item row template
            function createItemRow() {
                return `
            <div class="row mb-3 item-row">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="items[][name]" placeholder="Nama Barang" required>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="items[][price]" placeholder="Harga" required min="0">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="items[][quantity]" placeholder="Jumlah" required min="1">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="items[][unit]" placeholder="Satuan" required>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="items[][expired_date]">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-item">X</button>
                </div>
            </div>
        `;
            }

            // Add new item row
            $('#add-item').click(function() {
                $('.items-container').append(createItemRow());
            });

            // Remove item row
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
            });

            // Add button click handler
            $('#btn-add').click(function() {
                $('#itemForm').trigger('reset');
                $('#itemForm').attr('data-mode', 'add');
                $('.items-container .item-row').remove();
                loadCategories('#category_id');
                $('#itemModal').modal('show');
                // Add initial item row
                $('.items-container').append(createItemRow());
            });

            // Category change in modal
            $('#category_id').change(function() {
                const categoryId = $(this).val();
                if (categoryId) {
                    loadSubCategories('#sub_category_id', categoryId);
                } else {
                    $('#sub_category_id').html('<option value="">Pilih Sub Kategori</option>');
                    $('#price_limit').val('');
                }
            });

            // Sub category change in modal
            $('#sub_category_id').change(function() {
                const priceLimit = $('option:selected', this).data('price-limit');
                $('#price_limit').val(priceLimit ? priceLimit.toLocaleString('id-ID') : '');
            });

            // Form submission handler
            $('#itemForm').submit(function(e) {
                e.preventDefault();

                let url = "{{ route('incoming-items.store') }}";
                let method = 'POST';

                if ($(this).attr('data-mode') === 'edit') {
                    const id = $(this).attr('data-id');
                    url = `/incoming-items/${id}`;
                    method = 'PUT';
                }

                // Create FormData object for file upload
                const formData = new FormData(this);

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#itemModal').modal('hide');
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

                // Load categories first
                loadCategories('#category_id');

                // Then fetch item data
                $.get(`/incoming-items/${id}/edit`, function(data) {
                    $('#itemForm').trigger('reset');
                    $('#itemForm').attr('data-mode', 'edit');
                    $('#itemForm').attr('data-id', id);

                    // Load subcategories and fill form
                    loadSubCategories('#sub_category_id', data.sub_category.category_id, function() {
                        $('#category_id').val(data.sub_category.category_id);
                        $('#sub_category_id').val(data.sub_category_id);
                        $('input[name="source"]').val(data.source);
                        $('input[name="letter_number"]').val(data.letter_number);

                        // Clear existing items and add saved items
                        $('.items-container .item-row').remove();
                        data.details.forEach(function(detail) {
                            const row = $(createItemRow());
                            row.find('[name="items[][name]"]').val(detail.name);
                            row.find('[name="items[][price]"]').val(detail.price);
                            row.find('[name="items[][quantity]"]').val(detail.quantity);
                            row.find('[name="items[][unit]"]').val(detail.unit);
                            row.find('[name="items[][expired_date]"]').val(detail.expired_date);
                            $('.items-container').append(row);
                        });
                    });

                    $('#itemModal').modal('show');
                });
            });

            // Verify button click handler
            $(document).on('click', '.verify-btn', function() {
                const id = $(this).data('id');
                const isActive = $(this).hasClass('active');

                if (!isActive && !confirm('Verifikasi barang masuk ini?')) {
                    return;
                }

                $.ajax({
                    url: `/incoming-items/${id}/toggle-verification`,
                    method: 'POST',
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                        }
                    }
                });
            });

            // Print button click handler
            $(document).on('click', '.print-btn', function() {
                const id = $(this).data('id');
                window.open(`/incoming-items/${id}/print`, '_blank');
            });

            // Export button click handler
            $('#btn-export').click(function() {
                const params = {
                    category_id: $('#category-filter').val(),
                    sub_category_id: $('#subcategory-filter').val(),
                    year: $('#year-filter').val()
                };

                const queryString = Object.keys(params)
                    .filter(key => params[key])
                    .map(key => `${key}=${params[key]}`)
                    .join('&');

                window.location.href = `/incoming-items/export?${queryString}`;
            });
        });
    </script>
@endpush
