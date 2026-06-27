<?php /** Quiz Detail / Landing Page */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1 style="margin-bottom:8px;"><?= e($quiz['title']) ?></h1>
            <div class="d-flex align-center gap-2 text-muted text-sm">
                <span>Mata Kuliah: <a href="<?= url('/courses/' . $quiz['course_id']) ?>" class="text-primary"><?= e($quiz['course_name']) ?></a></span>
                <span>• Durasi: <strong><?= $quiz['duration_minutes'] ?> Menit</strong></span>
                <span>• Soal: <strong><?= $quiz['question_count'] ?? 0 ?> Butir</strong></span>
            </div>
        </div>
        <div class="btn-group">
            <?php if (has_role('admin', 'dosen')): ?>
                <a href="<?= url('/quizzes/' . $quiz['id'] . '/edit') ?>" class="btn btn-outline">Edit Kuis</a>
                <button class="btn btn-danger" data-confirm="Hapus kuis ini?" data-action="<?= url('/quizzes/' . $quiz['id'] . '/delete') ?>">Hapus Kuis</button>
            <?php endif; ?>
            <a href="<?= url('/courses/' . $quiz['course_id']) ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="dashboard-grid" style="grid-template-columns: 2fr 1fr;">
        <div>
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-3">Informasi Kuis</h3>
                    <div style="line-height:1.7;"><?= nl2br(e($quiz['description'])) ?: '<em>Tidak ada deskripsi.</em>' ?></div>
                    
                    <div class="mt-4 p-4" style="background:var(--bg-tertiary);border-radius:var(--border-radius);">
                        <ul style="list-style:disc;padding-left:20px;color:var(--text-secondary);font-size:var(--font-size-sm);line-height:1.8;">
                            <li>Batas Percobaan: <strong><?= $quiz['max_attempts'] ?> kali</strong></li>
                            <li>Nilai KKM: <strong><?= $quiz['passing_score'] ?></strong></li>
                            <li>Waktu Mulai: <strong><?= $quiz['start_time'] ? format_date($quiz['start_time'], 'd M Y H:i') : 'Kapan saja' ?></strong></li>
                            <li>Waktu Selesai: <strong><?= $quiz['end_time'] ? format_date($quiz['end_time'], 'd M Y H:i') : 'Tidak dibatasi' ?></strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if (has_role('mahasiswa')): ?>
                <?php 
                $canAttempt = true;
                $now = time();
                if ($quiz['start_time'] && strtotime($quiz['start_time']) > $now) $canAttempt = false;
                if ($quiz['end_time'] && strtotime($quiz['end_time']) < $now) $canAttempt = false;
                $attemptCount = count($attempts);
                if ($attemptCount >= $quiz['max_attempts']) $canAttempt = false;
                ?>
                
                <div class="card">
                    <div class="card-header"><h3>Mulai Mengerjakan</h3></div>
                    <div class="card-body text-center" style="padding:40px 24px;">
                        <?php if ($canAttempt): ?>
                            <form method="POST" action="<?= url('/quizzes/' . $quiz['id'] . '/start') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;max-width:300px;justify-content:center;">
                                    Mulai Kuis Sekarang
                                </button>
                            </form>
                            <p class="text-sm text-muted mt-3">Sisa percobaan Anda: <strong><?= $quiz['max_attempts'] - $attemptCount ?></strong></p>
                        <?php else: ?>
                            <div class="alert alert-warning" style="display:inline-flex;text-align:left;">
                                <div class="alert-message">
                                    <strong>Tidak bisa mengerjakan kuis saat ini.</strong><br>
                                    Kemungkinan karena batas waktu belum/sudah lewat, atau batas percobaan Anda habis.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <?php if (has_role('mahasiswa')): ?>
                <div class="card">
                    <div class="card-header"><h3>Riwayat Percobaan Anda</h3></div>
                    <div class="card-body" style="padding:0;">
                        <?php if (empty($attempts)): ?>
                            <div class="empty-state" style="padding:24px;"><p class="text-sm">Belum ada percobaan.</p></div>
                        <?php else: ?>
                            <ul class="activity-list" style="padding:16px;">
                                <?php foreach ($attempts as $i => $att): ?>
                                    <li class="activity-item">
                                        <div class="activity-content">
                                            <p><strong>Percobaan <?= count($attempts) - $i ?></strong></p>
                                            <p class="text-xs text-muted"><?= format_date($att['started_at'], 'd M Y H:i') ?></p>
                                        </div>
                                        <div style="text-align:right;">
                                            <?php if ($att['status'] === 'completed'): ?>
                                                <div class="font-bold <?= $att['score'] >= $quiz['passing_score'] ? 'text-success' : 'text-danger' ?>"><?= $att['score'] ?> / 100</div>
                                                <a href="<?= url('/quizzes/' . $quiz['id'] . '/result/' . $att['id']) ?>" class="text-xs text-primary">Lihat Hasil</a>
                                            <?php else: ?>
                                                <span class="badge badge-warning text-xs">Sedang Dikerjakan</span>
                                                <br><a href="<?= url('/quizzes/' . $quiz['id'] . '/attempt/' . $att['id']) ?>" class="text-xs text-primary mt-1" style="display:inline-block;">Lanjutkan</a>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif (has_role('admin', 'dosen')): ?>
                <div class="card">
                    <div class="card-header d-flex justify-between align-center">
                        <h3>Daftar Pertanyaan</h3>
                        <div class="d-flex align-center gap-2">
                            <span class="badge badge-secondary"><?= count($questions) ?> Soal</span>
                            <a href="<?= url('/quizzes/' . $quiz['id'] . '/edit') ?>" class="btn btn-sm btn-primary">Kelola Soal</a>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <?php if (empty($questions)): ?>
                            <div class="empty-state" style="padding:48px;">
                                <p>Belum ada pertanyaan.</p>
                                <a href="<?= url('/quizzes/' . $quiz['id'] . '/edit') ?>" class="btn btn-primary mt-3">Tambah Pertanyaan</a>
                            </div>
                        <?php else: ?>
                            <ul class="activity-list" style="padding:0;">
                                <?php foreach ($questions as $i => $q): ?>
                                    <li class="activity-item" style="padding:16px 24px;">
                                        <div class="activity-content" style="width:100%;">
                                            <div class="d-flex justify-between align-center">
                                                <p><strong>Soal <?= $i + 1 ?></strong>
                                                    <span class="badge badge-<?= $q['type'] === 'multiple_choice' ? 'primary' : 'warning' ?>" style="margin-left:8px;"><?= $q['type'] === 'multiple_choice' ? 'PG' : 'Essay' ?></span>
                                                </p>
                                                <span class="text-sm text-muted"><?= $q['points'] ?> poin</span>
                                            </div>
                                            <p class="text-sm mt-1"><?= str_limit(e($q['question_text']), 120) ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
