<?php /** User Detail View */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Detail User</h1>
        <div class="btn-group">
            <a href="<?= url('/users/' . $user['id'] . '/edit') ?>" class="btn btn-primary">Edit</a>
            <a href="<?= url('/users') ?>" class="btn btn-secondary">Kembali</a>
            <?php if ($user['id'] != \App\Core\Session::userId()): ?>
                <a href="<?= url('/messages/' . $user['id']) ?>" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;vertical-align:-3px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    Kirim Pesan
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card" style="max-width:700px;">
        <div class="card-body">
            <div class="d-flex align-center gap-4 mb-4">
                <div class="user-avatar" style="width:72px;height:72px;font-size:24px;">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= upload_url($user['avatar']) ?>" alt="">
                    <?php else: ?>
                        <span class="avatar-initial"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <h2 style="font-size:var(--font-size-xl)"><?= e($user['name']) ?></h2>
                    <p class="text-muted"><?= e($user['email']) ?></p>
                    <div class="d-flex gap-2 mt-1">
                        <span class="badge badge-primary"><?= ucfirst($user['role']) ?></span>
                        <?= $user['is_active'] ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>' ?>
                    </div>
                </div>
            </div>
            <table class="table">
                <tr><td class="text-muted" style="width:180px">NIM/NIDN</td><td><?= e($user['nim_nidn'] ?? '-') ?></td></tr>
                <tr><td class="text-muted">Telepon</td><td><?= e($user['phone'] ?? '-') ?></td></tr>
                <tr><td class="text-muted">Bio</td><td><?= e($user['bio'] ?? '-') ?></td></tr>
                <tr><td class="text-muted">Login Terakhir</td><td><?= $user['last_login_at'] ? format_date($user['last_login_at'], 'd M Y H:i') : 'Belum pernah login' ?></td></tr>
                <tr><td class="text-muted">Terdaftar</td><td><?= format_date($user['created_at'], 'd M Y H:i') ?></td></tr>
            </table>
        </div>
    </div>
</div>
