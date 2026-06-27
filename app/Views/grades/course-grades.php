<?php /** Dosen/Admin Course Grades View */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Rekap Nilai Kelas</h1>
        <div class="btn-group">
        <?php if (!empty($courseId)): ?>
            <a href="<?= url('/grades') ?>" class="btn btn-secondary">Pilih Mata Kuliah Lain</a>
            <a href="<?= url('/courses/' . $courseId . '/grades/export') ?>" class="btn btn-outline" target="_blank">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
        <?php endif; ?>
        </div>
    </div>

    <?php if (empty($courseId)): ?>
        <div class="card">
            <div class="card-body text-center" style="padding:60px 20px;">
                <p class="mb-4">Pilih Mata Kuliah dari menu atau dashboard untuk melihat rekap nilai kelas.</p>
                <a href="<?= url('/courses') ?>" class="btn btn-primary">Lihat Mata Kuliah</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body" style="padding:0;">
                <?php if (empty($grades)): ?>
                    <div class="empty-state"><p>Belum ada nilai pada mata kuliah ini.</p></div>
                <?php else: ?>
                    <div class="table-wrapper">
                        <table class="table">
                            <thead><tr><th>Mahasiswa</th><th>Jenis</th><th>Skor</th><th>Catatan</th><th>Waktu Input</th></tr></thead>
                            <tbody>
                                <?php foreach ($grades as $g): ?>
                                <tr>
                                    <td><strong><?= e($g['student_name']) ?></strong><br><span class="text-xs text-muted"><?= e($g['nim_nidn']) ?></span></td>
                                    <td><span class="badge badge-<?= $g['grade_type'] === 'quiz' ? 'info' : 'primary' ?>"><?= ucfirst($g['grade_type']) ?></span></td>
                                    <td><strong><?= $g['score'] ?></strong> / <?= $g['max_score'] ?></td>
                                    <td class="text-sm text-muted"><?= e($g['notes'] ?? '-') ?></td>
                                    <td class="text-sm text-muted"><?= format_date($g['created_at'], 'd M Y H:i') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
