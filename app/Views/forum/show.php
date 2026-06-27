<?php /** Forum Thread Detail */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1><?= e($thread['title']) ?></h1>
            <p class="text-muted">Mata Kuliah: <a href="<?= url('/courses/' . $course['id']) ?>" class="text-primary"><?= e($course['name']) ?></a></p>
        </div>
        <div class="btn-group">
            <a href="<?= url('/courses/' . $course['id'] . '/forum') ?>" class="btn btn-secondary">Kembali ke Forum</a>
        </div>
    </div>

    <!-- Original Thread -->
    <div class="card mb-4" style="border-left: 4px solid var(--accent-primary);">
        <div class="card-header d-flex justify-between align-center">
            <div class="d-flex align-center gap-3">
                <div class="user-avatar" style="width:40px;height:40px;">
                    <?php if ($thread['author_avatar']): ?>
                        <img src="<?= upload_url($thread['author_avatar']) ?>" alt="Avatar">
                    <?php else: ?>
                        <span class="avatar-initial"><?= strtoupper(substr($thread['author_name'], 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <strong><?= e($thread['author_name']) ?></strong>
                    <span class="badge badge-<?= $thread['author_role'] === 'mahasiswa' ? 'secondary' : 'primary' ?> ml-1" style="font-size:10px;padding:2px 6px;"><?= e($thread['author_role']) ?></span>
                    <div class="text-xs text-muted"><?= format_date($thread['created_at'], 'd M Y H:i') ?></div>
                </div>
            </div>
            <div class="btn-group">
                <?php if ($thread['is_pinned']): ?><span class="badge badge-primary">📌 Pinned</span><?php endif; ?>
                <?php if ($thread['is_locked']): ?><span class="badge badge-warning">🔒 Locked</span><?php endif; ?>
                <?php if (has_role('admin', 'dosen')): ?>
                    <button class="btn btn-sm btn-danger btn-icon" data-confirm="Hapus topik ini beserta semua balasannya?" data-action="<?= url('/forum/thread/' . $thread['id'] . '/delete') ?>">Hapus</button>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body" style="line-height:1.7;font-size:15px;padding-top:16px;">
            <?= nl2br(e($thread['body'])) ?>
        </div>
    </div>

    <!-- Replies -->
    <h3 class="mb-3"><?= count($replies) ?> Balasan</h3>
    
    <div class="dashboard-grid" style="grid-template-columns:1fr;">
        <?php foreach ($replies as $r): ?>
            <div class="card" id="reply-<?= $r['id'] ?>">
                <div class="card-header d-flex justify-between align-center" style="padding:12px 20px;background:var(--bg-secondary);">
                    <div class="d-flex align-center gap-2">
                        <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                            <?php if ($r['author_avatar']): ?>
                                <img src="<?= upload_url($r['author_avatar']) ?>" alt="Avatar">
                            <?php else: ?>
                                <span class="avatar-initial"><?= strtoupper(substr($r['author_name'], 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <strong style="font-size:var(--font-size-sm);"><?= e($r['author_name']) ?></strong>
                            <span class="text-xs text-muted ml-2"><?= time_ago($r['created_at']) ?></span>
                        </div>
                    </div>
                    <?php if (has_role('admin', 'dosen')): ?>
                        <button class="text-danger text-sm" style="background:none;border:none;cursor:pointer;" data-confirm="Hapus balasan ini?" data-action="<?= url('/forum/reply/' . $r['id'] . '/delete') ?>">Hapus</button>
                    <?php endif; ?>
                </div>
                <div class="card-body" style="padding:16px 20px;line-height:1.6;">
                    <?= nl2br(e($r['body'])) ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Reply Form -->
        <?php if ($thread['is_locked'] && !has_role('admin', 'dosen')): ?>
            <div class="alert alert-warning mt-4">
                <div class="alert-message">Topik diskusi ini telah dikunci. Anda tidak dapat mengirim balasan.</div>
            </div>
        <?php else: ?>
            <div class="card mt-2">
                <div class="card-body">
                    <form method="POST" action="<?= url('/forum/thread/' . $thread['id'] . '/reply') ?>">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label class="form-label">Tulis Balasan</label>
                            <textarea name="body" class="form-control" rows="4" required placeholder="Ketikan balasan Anda..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Kirim Balasan</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
