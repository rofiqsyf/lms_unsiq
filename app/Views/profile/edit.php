<?php /** Edit Profile */ ?>
<div class="animate-fade-in">
    <div class="page-header"><h1>Pengaturan Profil Saya</h1></div>

    <div class="dashboard-grid" style="grid-template-columns: 1fr 2fr;">
        <!-- Avatar Section -->
        <div class="card text-center mb-4" style="height:max-content;">
            <div class="card-body">
                <div class="user-avatar" style="width:120px;height:120px;font-size:40px;margin:0 auto 16px;">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= upload_url($user['avatar']) ?>" alt="Avatar">
                    <?php else: ?>
                        <span class="avatar-initial"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <h3 style="font-size:var(--font-size-lg);"><?= e($user['name']) ?></h3>
                <p class="text-muted text-sm"><?= e($user['email']) ?></p>
                <div class="mt-3"><span class="badge badge-primary"><?= ucfirst($user['role']) ?></span></div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="card">
            <div class="card-header"><h3>Informasi Pribadi</h3></div>
            <div class="card-body">
                <form method="POST" action="<?= url('/profile/update') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" value="<?= e($user['phone']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">NIM / NIDN (Tidak dapat diubah)</label>
                            <input type="text" class="form-control" value="<?= e($user['nim_nidn']) ?>" disabled style="opacity:0.7">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio Singkat</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="Ceritakan sedikit tentang Anda..."><?= e($user['bio']) ?></textarea>
                    </div>

                    <hr style="border:0;border-top:1px solid var(--border-color);margin:24px 0;">
                    <h4 class="mb-3">Ganti Password & Foto</h4>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin ganti" minlength="6">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ubah Foto Profil</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                            <span class="form-text text-muted">Format: JPG, PNG. Maksimal 2MB.</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 w-full" style="justify-content:center;">Simpan Perubahan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>
