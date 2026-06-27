<?php /** My Enrolled Courses (Mahasiswa) */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Kursus Saya</h1>
        <a href="<?= url('/courses') ?>" class="btn btn-outline">Jelajahi Mata Kuliah</a>
    </div>
    <?php if (empty($courses)): ?>
        <div class="card"><div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/></svg>
            <h3>Belum ada mata kuliah</h3><p>Anda belum mendaftar mata kuliah. Jelajahi dan daftar sekarang!</p>
            <a href="<?= url('/courses') ?>" class="btn btn-primary">Jelajahi</a>
        </div></div>
    <?php else: ?>
        <div class="grid-3">
            <?php foreach ($courses as $c): ?>
                <div class="course-card">
                    <div class="course-card-img"><div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:1.5rem;font-weight:700;color:rgba(255,255,255,0.7);"><?= e($c['code']) ?></div></div>
                    <div class="course-card-body">
                        <h3><a href="<?= url('/courses/' . $c['course_id']) ?>"><?= e($c['course_name']) ?></a></h3>
                        <p class="text-sm text-muted"><?= e($c['dosen_name']) ?> • <?= $c['sks'] ?> SKS</p>
                        <div class="mt-3">
                            <div class="d-flex justify-between mb-1"><span class="text-xs">Progress</span><span class="text-xs font-bold"><?= number_format($c['progress'], 0) ?>%</span></div>
                            <div class="progress-bar"><div class="progress-fill <?= $c['progress'] >= 80 ? 'progress-success' : ($c['progress'] >= 40 ? '' : 'progress-warning') ?>" style="width:<?= $c['progress'] ?>%"></div></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
