<?php /** Hasil Pencarian Global */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Hasil Pencarian</h1>
            <p class="text-muted">Mencari: <strong><?= e($keyword ?: 'Semua') ?></strong></p>
        </div>
    </div>

    <?php if (empty($keyword)): ?>
        <div class="card">
            <div class="card-body text-center p-5">
                <p>Silakan ketikkan kata kunci di kotak pencarian di atas.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="dashboard-grid" style="grid-template-columns:1fr 1fr;">
            <!-- Hasil Mata Kuliah -->
            <div class="card">
                <div class="card-header">
                    <h3>📚 Mata Kuliah (<?= count($results['courses']) ?>)</h3>
                </div>
                <div class="card-body" style="padding:0;">
                    <?php if (empty($results['courses'])): ?>
                        <div class="empty-state">Tidak ada mata kuliah ditemukan.</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($results['courses'] as $c): ?>
                                <a href="<?= url('/courses/' . $c['id']) ?>" class="list-group-item" style="padding:16px;display:block;text-decoration:none;border-bottom:1px solid var(--border-color);">
                                    <div class="d-flex align-center gap-3">
                                        <div style="width:48px;height:48px;border-radius:6px;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                            <?php if ($c['thumbnail']): ?>
                                                <img src="<?= upload_url($c['thumbnail']) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                                            <?php else: ?>
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <strong style="color:var(--text-color);"><?= e($c['name']) ?></strong>
                                            <div class="text-xs text-muted"><?= e($c['code']) ?> • <?= e($c['dosen_name']) ?></div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Hasil Pengguna -->
            <div class="card">
                <div class="card-header">
                    <h3>👥 Pengguna (<?= count($results['users']) ?>)</h3>
                </div>
                <div class="card-body" style="padding:0;">
                    <?php if (empty($results['users'])): ?>
                        <div class="empty-state">Tidak ada pengguna ditemukan.</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($results['users'] as $u): ?>
                                <a href="<?= url('/users/' . $u['id']) ?>" class="list-group-item" style="padding:16px;display:block;text-decoration:none;border-bottom:1px solid var(--border-color);">
                                    <div class="d-flex align-center gap-3">
                                        <div class="user-avatar" style="width:40px;height:40px;">
                                            <?php if ($u['avatar']): ?>
                                                <img src="<?= upload_url($u['avatar']) ?>" alt="Avatar">
                                            <?php else: ?>
                                                <span class="avatar-initial"><?= strtoupper(substr($u['name'], 0, 1)) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <strong style="color:var(--text-color);"><?= e($u['name']) ?></strong>
                                            <div class="d-flex align-center gap-2 mt-1">
                                                <span class="badge badge-<?= $u['role'] === 'mahasiswa' ? 'secondary' : 'primary' ?>" style="font-size:10px;padding:2px 6px;"><?= e($u['role']) ?></span>
                                                <span class="text-xs text-muted"><?= e($u['nim_nidn'] ?? '') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
