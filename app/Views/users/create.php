<?php /** Create/Edit User Form */ $isEdit = isset($user); ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1><?= $isEdit ? 'Edit User' : 'Tambah User Baru' ?></h1>
    </div>

    <div class="card" style="max-width:700px;">
        <div class="card-body">
            <form method="POST" action="<?= $isEdit ? url('/users/' . $user['id'] . '/update') : url('/users') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= e($isEdit ? $user['name'] : old('name')) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= e($isEdit ? $user['email'] : old('email')) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password <?= $isEdit ? '(kosongkan jika tidak diubah)' : '<span class="required">*</span>' ?></label>
                        <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?> minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role <span class="required">*</span></label>
                        <select name="role" class="form-control" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" <?= ($isEdit ? $user['role'] : old('role')) === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="dosen" <?= ($isEdit ? $user['role'] : old('role')) === 'dosen' ? 'selected' : '' ?>>Dosen</option>
                            <option value="mahasiswa" <?= ($isEdit ? $user['role'] : old('role')) === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">NIM / NIDN</label>
                        <input type="text" name="nim_nidn" class="form-control" value="<?= e($isEdit ? $user['nim_nidn'] : old('nim_nidn')) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control" value="<?= e($isEdit ? $user['phone'] : old('phone')) ?>">
                    </div>
                </div>

                <?php if ($isEdit): ?>
                <div class="form-group">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" value="1" <?= $user['is_active'] ? 'checked' : '' ?>>
                        Akun Aktif
                    </label>
                </div>
                <?php endif; ?>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah User' ?></button>
                    <a href="<?= url('/users') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
