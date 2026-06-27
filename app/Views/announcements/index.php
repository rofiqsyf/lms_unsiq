<?php /** Announcements List View */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Pengumuman</h1>
            <p class="text-muted">Informasi terbaru dari administrator dan dosen</p>
        </div>
        <?php if (has_role('admin', 'dosen')): ?>
            <a href="<?= url('/announcements/create') ?>" class="btn btn-primary">Buat Pengumuman</a>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <?php if (empty($announcements)): ?>
                <div class="empty-state" style="padding:60px 20px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                    <h3>Belum ada pengumuman</h3>
                    <p>Informasi terbaru akan muncul di sini.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead><tr><th>Terkait</th><th>Pengumuman</th><th>Waktu</th><?php if(has_role('admin', 'dosen')): ?><th>Aksi</th><?php endif; ?></tr></thead>
                        <tbody>
                            <?php foreach ($announcements as $ann): ?>
                                <tr style="<?= $ann['is_pinned'] ? 'background:rgba(99,102,241,0.05);' : '' ?>">
                                    <td style="width:180px;">
                                        <?php if ($ann['course_name']): ?>
                                            <span class="badge badge-primary"><?= e($ann['course_name']) ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">UMUM</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-center gap-2 mb-1">
                                            <?php if ($ann['is_pinned']): ?>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--accent-primary)" stroke="none"><path d="M16 4h2a2 2 0 012 2v14l-8-3-8 3V6a2 2 0 012-2h2"/></svg>
                                            <?php endif; ?>
                                            <strong style="font-size:var(--font-size-base)"><?= e($ann['title']) ?></strong>
                                        </div>
                                        <div class="text-sm" style="color:var(--text-secondary);line-height:1.6;margin-bottom:8px;">
                                            <?= nl2br(e($ann['content'])) ?>
                                        </div>
                                        <div class="text-xs text-muted">Oleh: <?= e($ann['author_name']) ?></div>
                                    </td>
                                    <td class="text-sm text-muted" style="width:160px;"><?= format_date($ann['created_at'], 'd M Y H:i') ?></td>
                                    <?php if(has_role('admin', 'dosen') && (has_role('admin') || $ann['user_id'] == \App\Core\Session::userId())): ?>
                                    <td style="width:120px;">
                                        <div class="btn-group">
                                            <a href="<?= url('/announcements/' . $ann['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                                            <button class="btn btn-sm btn-danger" data-confirm="Hapus pengumuman ini?" data-action="<?= url('/announcements/' . $ann['id'] . '/delete') ?>">Hapus</button>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <?php require VIEWS_PATH . '/partials/pagination.php'; ?>
        </div>
    </div>
</div>
