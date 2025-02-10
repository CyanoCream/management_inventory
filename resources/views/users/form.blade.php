{{-- resources/views/users/form.blade.php --}}
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Role</label>
                        <select class="form-select" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Operator">Operator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" required minlength="8" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required minlength="8" maxlength="100">
                        <small class="text-muted">Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan angka</small>
                    </div>
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="name" required minlength="8" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required minlength="8" maxlength="100">
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

