<?php /** Quiz Attempt Execution Page */ ?>
<div class="animate-fade-in">
    <div class="page-header d-flex justify-between align-center">
        <div>
            <h1 style="font-size:var(--font-size-xl);margin-bottom:4px;"><?= e($quiz['title']) ?></h1>
            <p class="text-sm text-muted">Percobaan ke-<?= $attempt['id'] ?> • Jangan tutup halaman ini sebelum disubmit.</p>
        </div>
        <div class="stat-card stat-danger" style="padding:12px 20px;border-radius:var(--border-radius-full);">
            <div class="d-flex align-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-danger"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <div style="font-size:var(--font-size-xl);font-weight:700;letter-spacing:1px;color:white;" id="countdownTimer">--:--:--</div>
            </div>
        </div>
    </div>

    <form method="POST" action="<?= url('/quizzes/' . $quiz['id'] . '/attempt/' . $attempt['id'] . '/submit') ?>" id="quizForm">
        <?= csrf_field() ?>
        
        <?php foreach ($questions as $i => $q): ?>
            <div class="card mb-4">
                <div class="card-header" style="background:var(--bg-tertiary);">
                    <div class="d-flex justify-between w-full align-center">
                        <strong>Soal No. <?= $i + 1 ?></strong>
                        <span class="badge badge-secondary"><?= $q['points'] ?> Poin</span>
                    </div>
                </div>
                <div class="card-body">
                    <div style="font-size:var(--font-size-lg);margin-bottom:20px;line-height:1.6;">
                        <?= nl2br(e($q['question_text'])) ?>
                    </div>
                    
                    <?php if ($q['type'] === 'multiple_choice'): ?>
                        <?php $options = json_decode($q['options'], true); ?>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <?php foreach ($options as $key => $val): ?>
                                <label class="form-check" style="padding:16px;border:1px solid var(--border-color);border-radius:var(--border-radius);transition:background 0.2s;">
                                    <input type="radio" name="answer_<?= $q['id'] ?>" value="<?= $key ?>" style="width:20px;height:20px;" required>
                                    <span style="font-size:var(--font-size-base);margin-left:8px;font-weight:500;"><?= strtoupper($key) ?>. <?= e($val) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <textarea name="answer_<?= $q['id'] ?>" class="form-control" rows="5" placeholder="Ketik jawaban Anda di sini..." required></textarea>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="card" style="position:sticky;bottom:24px;z-index:90;box-shadow:var(--shadow-xl);border-color:var(--accent-primary);">
            <div class="card-body d-flex justify-between align-center" style="padding:16px 24px;">
                <div>
                    <span class="text-sm text-muted">Pastikan semua soal sudah dijawab.</span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Anda yakin ingin mensubmit kuis ini? Jawaban tidak dapat diubah lagi.')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Selesai & Submit
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Quiz Timer Script
    const durationMins = <?= $quiz['duration_minutes'] ?>;
    const startedAt = new Date('<?= $attempt['started_at'] ?>').getTime();
    const endTime = startedAt + (durationMins * 60 * 1000);
    
    function updateTimer() {
        const now = new Date().getTime();
        const distance = endTime - now;
        
        if (distance < 0) {
            document.getElementById('countdownTimer').innerHTML = "00:00:00";
            alert('Waktu habis! Kuis akan disubmit otomatis.');
            document.getElementById('quizForm').submit();
            return;
        }
        
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById('countdownTimer').innerHTML = 
            String(hours).padStart(2, '0') + ":" + 
            String(minutes).padStart(2, '0') + ":" + 
            String(seconds).padStart(2, '0');
            
        // Visual warning when < 5 mins
        if (distance < 300000) {
            document.querySelector('.stat-danger').style.animation = 'pulse-badge 1s infinite';
        }
    }
    
    setInterval(updateTimer, 1000);
    updateTimer();
    
    // Checkbox styling active state
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const name = this.name;
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.closest('.form-check').style.background = r.checked ? 'rgba(99, 102, 241, 0.1)' : 'transparent';
                r.closest('.form-check').style.borderColor = r.checked ? 'var(--accent-primary)' : 'var(--border-color)';
            });
        });
    });
</script>
