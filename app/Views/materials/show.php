<?php /** Material Detail Page */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1 style="margin-bottom:8px;"><?= e($material['title']) ?></h1>
            <div class="d-flex align-center gap-2">
                <span class="badge badge-primary"><?= e($course['name']) ?></span>
                <?php if ($material['section']): ?><span class="text-sm text-muted">• <?= e($material['section']) ?></span><?php endif; ?>
            </div>
        </div>
        <div class="btn-group">
            <?php if (has_role('admin', 'dosen')): ?>
                <a href="<?= url('/materials/' . $material['id'] . '/edit') ?>" class="btn btn-secondary">Edit</a>
                <button class="btn btn-danger" data-confirm="Hapus materi ini?" data-action="<?= url('/materials/' . $material['id'] . '/delete') ?>">Hapus</button>
            <?php endif; ?>
            <a href="<?= url('/courses/' . $course['id']) ?>" class="btn btn-secondary">Kembali ke Mata Kuliah</a>
        </div>
    </div>

    <div class="dashboard-grid" style="grid-template-columns:2fr 1fr;">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($material['video_url'])): ?>
                    <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:var(--border-radius);margin-bottom:24px;">
                        <?php 
                        $videoUrl = $material['video_url'];
                        if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                            $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                        }
                        ?>
                        <iframe src="<?= e($videoUrl) ?>" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
                
                <div style="font-size:var(--font-size-base);line-height:1.7;">
                    <?= nl2br(e($material['content'])) ?>
                </div>
            </div>
        </div>

        <div>
            <?php if (!empty($material['file_path'])): ?>
            <div class="card mb-4">
                <div class="card-header"><h3>Lampiran File</h3></div>
                <div class="card-body" style="text-align:center;">
                    <div style="margin-bottom:16px;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-primary"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <p class="font-semibold mb-1"><?= e($material['file_name']) ?></p>
                    <p class="text-xs text-muted mb-3"><?= strtoupper($material['file_type']) ?> • <?= format_filesize($material['file_size']) ?> • Didownload <?= $material['download_count'] ?>x</p>
                    <a href="<?= url('/materials/' . $material['id'] . '/download') ?>" class="btn btn-primary w-full justify-center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download File
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <p class="text-sm text-muted mb-2">Terakhir diperbarui:</p>
                    <p class="font-medium"><?= format_date($material['updated_at'] ?? $material['created_at'], 'd M Y H:i') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
