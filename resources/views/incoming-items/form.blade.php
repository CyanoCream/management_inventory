<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Barang Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="itemForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Operator</label>
                            <select class="form-select" name="operator" id="operator" @if(auth()->user()->role != 'Admin') disabled @endif>
                                <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Kategori</label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Sub Kategori</label>
                            <select class="form-select" name="sub_category_id" id="sub_category_id" required>
                                <option value="">Pilih Sub Kategori</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Batas Harga</label>
                            <input type="text" class="form-control" id="price_limit" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Asal Barang</label>
                            <input type="text" class="form-control" name="source" required maxlength="200">
                        </div>
                        <div class="col-md-6">
                            <label>Nomor Surat</label>
                            <input type="text" class="form-control" name="letter_number" maxlength="100">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Lampiran</label>
                        <input type="file" class="form-control" name="attachment" accept=".doc,.docx,.zip">
                    </div>

                    <div class="items-container">
                        <h5>Informasi Barang</h5>
                        <div class="item-row">
                            <!-- Template for item rows - will be cloned by JavaScript -->
                        </div>
                        <button type="button" class="btn btn-sm btn-info" id="add-item">+ Tambah Item</button>
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
