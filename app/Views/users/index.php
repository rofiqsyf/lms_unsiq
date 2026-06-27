<?php /** @var array $users, $pagination, $search, $role */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Kelola Users</h1>
            <p class="text-muted">Manajemen akun pengguna sistem LMS</p>
        </div>
        <a href="<?= url('/users/create') ?>" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah User
        </a>
    </div>

    <div class="filter-bar">
        <form id="filter-form" method="GET" action="<?= url('/users') ?>" style="display:flex;gap:12px;flex-wrap:wrap;width:100%;">
            <input type="text" name="search" class="form-control smart-search-input" placeholder="Cari nama, email..." value="<?= e($search) ?>">
            <select name="role" class="form-control smart-search-select" style="max-width:180px;">
                <option value="">Semua Role</option>
                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="dosen" <?= $role === 'dosen' ? 'selected' : '' ?>>Dosen</option>
                <option value="mahasiswa" <?= $role === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
            </select>
            <button type="submit" class="btn btn-secondary" style="position: relative; z-index: 50; display: none;">Filter</button>
            <?php if ($search || $role): ?>
                <a href="<?= url('/users') ?>" class="btn btn-secondary" style="position: relative; z-index: 50;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div id="data-container">

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>NIM/NIDN</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr><td colspan="8" class="text-center text-muted" style="padding:40px;">Tidak ada data user.</td></tr>
                        <?php else: ?>
                            <?php foreach ($users as $i => $u): ?>
                            <tr>
                                <td><?= $pagination->getOffset() + $i + 1 ?></td>
                                <td>
                                    <div class="d-flex align-center gap-2">
                                        <div class="user-avatar" style="width:32px;height:32px;">
                                            <?php if (!empty($u['avatar'])): ?>
                                                <img src="<?= upload_url($u['avatar']) ?>" alt="">
                                            <?php else: ?>
                                                <span class="avatar-initial" style="font-size:12px;"><?= strtoupper(substr($u['name'], 0, 1)) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <strong><?= e($u['name']) ?></strong>
                                    </div>
                                </td>
                                <td><?= e($u['email']) ?></td>
                                <td><span class="badge badge-<?= $u['role'] === 'admin' ? 'danger' : ($u['role'] === 'dosen' ? 'primary' : 'info') ?>"><?= ucfirst($u['role']) ?></span></td>
                                <td><?= e($u['nim_nidn'] ?? '-') ?></td>
                                <td><?= $u['is_active'] ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>' ?></td>
                                <td class="text-sm text-muted"><?= format_date($u['created_at']) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= url('/users/' . $u['id'] . '/edit') ?>" class="btn btn-sm btn-secondary">Edit</a>
                                        <button class="btn btn-sm btn-danger"
                                                data-confirm="Apakah Anda yakin ingin menghapus user <?= e($u['name']) ?>?"
                                                data-action="<?= url('/users/' . $u['id'] . '/delete') ?>"
                                                data-title="Hapus User">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <?php require VIEWS_PATH . '/partials/pagination.php'; ?>
        </div>
    </div>
    </div>
</div>
