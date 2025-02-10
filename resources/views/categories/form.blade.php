{{-- resources/views/categories/form.blade.php --}}
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode Kategori</label>
                        <input type="text" class="form-control" name="code" required maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" class="form-control" name="name" required maxlength="100">
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
