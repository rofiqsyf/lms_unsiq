<?php /** Student Grades View */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Rekap Nilai Saya</h1>
        <p class="text-muted">Kumpulan nilai dari seluruh tugas dan kuis</p>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <?php if (empty($grades)): ?>
                <div class="empty-state">
                    <p>Belum ada nilai yang masuk.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mata Kuliah</th>
                                <th>Jenis</th>
                                <th>Nilai</th>
                                <th>Catatan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $currentCourse = '';
                            foreach ($grades as $g): 
                                if ($currentCourse !== $g['course_name']): 
                                    $currentCourse = $g['course_name'];
                            ?>
                                <tr style="background:var(--bg-tertiary);"><td colspan="5"><strong><?= e($currentCourse) ?></strong> (<?= e($g['course_code']) ?>)</td></tr>
                            <?php endif; ?>
                            <tr>
                                <td></td>
                                <td><span class="badge badge-<?= $g['grade_type'] === 'quiz' ? 'info' : 'primary' ?>"><?= ucfirst($g['grade_type']) ?></span></td>
                                <td><strong class="text-lg"><?= $g['score'] ?></strong> / <?= $g['max_score'] ?></td>
                                <td class="text-sm text-muted"><?= e($g['notes'] ?? '-') ?></td>
                                <td class="text-sm text-muted"><?= format_date($g['created_at'], 'd M Y') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
