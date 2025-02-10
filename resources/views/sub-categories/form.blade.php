{{-- resources/views/sub-categories/form.blade.php --}}
<div class="modal fade" id="subcategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Sub Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="subcategoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nama Sub Kategori</label>
                        <input type="text" class="form-control" name="name" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label>Batas Harga</label>
                        <input type="number" class="form-control" name="price_limit" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
