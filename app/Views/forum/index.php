<?php /** Forum Threads Index */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Forum Diskusi</h1>
            <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
        </div>
        <div class="btn-group">
            <a href="<?= url('/courses/' . $course['id']) ?>" class="btn btn-secondary">Kembali</a>
            <a href="<?= url('/courses/' . $course['id'] . '/forum/create') ?>" class="btn btn-primary">Buat Topik Baru</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <?php if (empty($threads)): ?>
                <div class="empty-state" style="padding:60px 20px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <h3>Belum ada diskusi</h3>
                    <p>Mulai topik diskusi pertama di mata kuliah ini.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead><tr><th>Topik</th><th>Balasan</th><th>Aktivitas Terakhir</th></tr></thead>
                        <tbody>
                            <?php foreach ($threads as $t): ?>
                                <tr style="<?= $t['is_pinned'] ? 'background:rgba(99,102,241,0.05);' : '' ?>">
                                    <td>
                                        <div class="d-flex align-center gap-2 mb-1">
                                            <?php if ($t['is_pinned']): ?>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--accent-primary)" stroke="none"><path d="M16 4h2a2 2 0 012 2v14l-8-3-8 3V6a2 2 0 012-2h2"/></svg>
                                            <?php endif; ?>
                                            <?php if ($t['is_locked']): ?>
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                            <?php endif; ?>
                                            <a href="<?= url('/forum/thread/' . $t['id']) ?>"><strong style="font-size:var(--font-size-base)"><?= e($t['title']) ?></strong></a>
                                        </div>
                                        <div class="d-flex align-center gap-2 text-xs text-muted">
                                            <div class="user-avatar" style="width:20px;height:20px;font-size:8px;">
                                                <?php if ($t['author_avatar']): ?>
                                                    <img src="<?= upload_url($t['author_avatar']) ?>" alt="Avatar">
                                                <?php else: ?>
                                                    <span class="avatar-initial"><?= strtoupper(substr($t['author_name'], 0, 1)) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <span>Oleh: <?= e($t['author_name']) ?></span>
                                            <span class="badge badge-<?= $t['author_role'] === 'mahasiswa' ? 'secondary' : 'primary' ?>" style="font-size:10px;padding:2px 6px;"><?= e($t['author_role']) ?></span>
                                        </div>
                                    </td>
                                    <td style="width:100px;text-align:center;">
                                        <span class="badge badge-secondary"><?= $t['reply_count'] ?></span>
                                    </td>
                                    <td class="text-sm text-muted" style="width:180px;">
                                        <?= time_ago($t['last_reply_at'] ?? $t['created_at']) ?>
                                    </td>
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
