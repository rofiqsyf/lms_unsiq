<?php /** Quiz Result Page */ ?>
<div class="animate-fade-in">
    <div class="page-header text-center">
        <h1>Hasil Kuis</h1>
        <p class="text-muted">Kuis: <strong><?= e($quiz['title']) ?></strong></p>
    </div>

    <div style="max-width:800px;margin:0 auto;">
        <div class="card mb-4" style="text-align:center;padding:40px 20px;">
            <div style="font-size:1.2rem;margin-bottom:16px;color:var(--text-secondary);">Nilai Akhir Anda</div>
            <div style="font-size:5rem;font-weight:800;line-height:1;margin-bottom:20px;color:<?= $attempt['score'] >= $quiz['passing_score'] ? 'var(--accent-success)' : 'var(--accent-danger)' ?>">
                <?= $attempt['score'] ?>
            </div>
            
            <?php if ($attempt['score'] >= $quiz['passing_score']): ?>
                <div class="badge badge-success" style="font-size:1rem;padding:8px 24px;">LULUS KKM</div>
            <?php else: ?>
                <div class="badge badge-danger" style="font-size:1rem;padding:8px 24px;">BELUM LULUS KKM</div>
                <p class="text-sm text-muted mt-3">KKM: <?= $quiz['passing_score'] ?></p>
            <?php endif; ?>

            <div class="mt-4">
                <a href="<?= url('/quizzes/' . $quiz['id']) ?>" class="btn btn-secondary">Kembali ke Info Kuis</a>
                <a href="<?= url('/courses/' . $quiz['course_id']) ?>" class="btn btn-primary">Kembali ke Mata Kuliah</a>
            </div>
        </div>

        <?php if ($quiz['show_result']): ?>
            <h3 class="mb-3">Detail Jawaban</h3>
            <?php foreach ($attempt['answers'] ?? [] as $i => $ans): ?>
                <div class="card mb-3" style="border-left:4px solid <?= $ans['is_correct'] ? 'var(--accent-success)' : 'var(--accent-danger)' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-between w-full mb-2">
                            <strong class="text-sm">Soal No. <?= $i + 1 ?></strong>
                            <span class="badge <?= $ans['is_correct'] ? 'badge-success' : 'badge-danger' ?>">
                                <?= $ans['is_correct'] ? 'Benar' : 'Salah' ?> (<?= $ans['points_earned'] ?>/<?= $ans['points'] ?> Pts)
                            </span>
                        </div>
                        <p class="mb-3 font-medium"><?= nl2br(e($ans['question_text'])) ?></p>
                        
                        <div class="p-3" style="background:var(--bg-tertiary);border-radius:var(--border-radius-sm);">
                            <div class="text-sm text-muted mb-1">Jawaban Anda:</div>
                            <div class="font-semibold mb-3 <?= $ans['is_correct'] ? 'text-success' : 'text-danger' ?>">
                                <?php if ($ans['type'] === 'multiple_choice'): ?>
                                    <?php 
                                    $opts = json_decode($ans['options'], true);
                                    echo strtoupper($ans['answer_text']) . '. ' . e($opts[$ans['answer_text']] ?? '-');
                                    ?>
                                <?php else: ?>
                                    <?= nl2br(e($ans['answer_text'])) ?>
                                <?php endif; ?>
                            </div>

                            <?php if (!$ans['is_correct'] && $ans['type'] === 'multiple_choice'): ?>
                                <div class="text-sm text-muted mb-1">Jawaban Benar:</div>
                                <div class="font-semibold text-success">
                                    <?= strtoupper($ans['correct_answer']) . '. ' . e($opts[$ans['correct_answer']] ?? '') ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($ans['explanation'])): ?>
                                <hr style="border:0;border-top:1px solid var(--border-color);margin:12px 0;">
                                <div class="text-sm">
                                    <span class="text-muted">Penjelasan:</span><br>
                                    <?= nl2br(e($ans['explanation'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
