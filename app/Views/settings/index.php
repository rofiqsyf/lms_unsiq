<?php /** System Settings */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Pengaturan Sistem</h1>
        <p class="text-muted">Konfigurasi umum aplikasi LMS</p>
    </div>

    <form method="POST" action="<?= url('/settings/update') ?>">
        <?= csrf_field() ?>
        
        <div class="dashboard-grid" style="grid-template-columns: 1fr;">
            <?php foreach ($settings as $group => $items): ?>
                <div class="card mb-4">
                    <div class="card-header"><h3 style="text-transform:capitalize;"><?= e($group) ?> Settings</h3></div>
                    <div class="card-body">
                        <?php foreach ($items as $s): ?>
                            <div class="form-group" style="max-width:600px;">
                                <label class="form-label" style="text-transform:capitalize;"><?= str_replace('_', ' ', $s['key_name']) ?></label>
                                <?php if (strlen((string)($s['value'] ?? '')) > 50): ?>
                                    <textarea name="<?= $s['key_name'] ?>" class="form-control" rows="3"><?= e($s['value']) ?></textarea>
                                <?php else: ?>
                                    <input type="text" name="<?= $s['key_name'] ?>" class="form-control" value="<?= e($s['value']) ?>">
                                <?php endif; ?>
                                <?php if ($s['description']): ?>
                                    <span class="form-text"><?= e($s['description']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($settings)): ?>
                <div class="card"><div class="empty-state"><p>Belum ada konfigurasi dinamis yang tersedia.</p></div></div>
            <?php else: ?>
                <div class="mt-2 mb-5">
                    <button type="submit" class="btn btn-primary btn-lg">Simpan Semua Pengaturan</button>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>
