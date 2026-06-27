<?php /** Edit Quiz + Question Management */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Edit Kuis</h1>
            <p class="text-muted">Mata Kuliah: <strong><?= e($quiz['course_name']) ?></strong></p>
        </div>
        <a href="<?= url('/quizzes/' . $quiz['id']) ?>" class="btn btn-secondary">Kembali ke Detail</a>
    </div>

    <!-- Quiz Settings -->
    <div class="card mb-4" style="max-width:800px;">
        <div class="card-header"><h3>Pengaturan Kuis</h3></div>
        <div class="card-body">
            <form method="POST" action="<?= url('/quizzes/' . $quiz['id'] . '/update') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Judul Kuis <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= e($quiz['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi / Instruksi</label>
                    <textarea name="description" class="form-control" rows="3"><?= e($quiz['description']) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Durasi (Menit) <span class="required">*</span></label>
                        <input type="number" name="duration_minutes" class="form-control" value="<?= e($quiz['duration_minutes']) ?>" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Percobaan <span class="required">*</span></label>
                        <input type="number" name="max_attempts" class="form-control" value="<?= e($quiz['max_attempts']) ?>" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nilai KKM (Passing Score)</label>
                        <input type="number" name="passing_score" class="form-control" value="<?= e($quiz['passing_score']) ?>" min="0" max="100">
                    </div>
                </div>

                <div class="form-row mt-2 mb-3">
                    <div class="form-group">
                        <label class="form-label">Waktu Mulai (Opsional)</label>
                        <input type="datetime-local" name="start_time" class="form-control" value="<?= $quiz['start_time'] ? date('Y-m-d\TH:i', strtotime($quiz['start_time'])) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Waktu Berakhir (Opsional)</label>
                        <input type="datetime-local" name="end_time" class="form-control" value="<?= $quiz['end_time'] ? date('Y-m-d\TH:i', strtotime($quiz['end_time'])) : '' ?>">
                    </div>
                </div>

                <div class="form-row mt-2">
                    <div class="form-group"><label class="form-check"><input type="checkbox" name="shuffle_questions" value="1" <?= $quiz['shuffle_questions'] ? 'checked' : '' ?>> Acak Pertanyaan</label></div>
                    <div class="form-group"><label class="form-check"><input type="checkbox" name="show_result" value="1" <?= $quiz['show_result'] ? 'checked' : '' ?>> Tampilkan Hasil Setelah Selesai</label></div>
                    <div class="form-group"><label class="form-check"><input type="checkbox" name="is_published" value="1" <?= $quiz['is_published'] ? 'checked' : '' ?>> Publish Kuis</label></div>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Question Management -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-between align-center">
            <h3>Daftar Pertanyaan</h3>
            <span class="badge badge-secondary"><?= count($questions) ?> Soal</span>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (empty($questions)): ?>
                <div class="empty-state" style="padding:48px;"><p>Belum ada pertanyaan. Tambah pertanyaan di bawah.</p></div>
            <?php else: ?>
                <?php foreach ($questions as $i => $q): ?>
                    <div class="p-4" style="border-bottom:1px solid var(--border-color);">
                        <div class="d-flex justify-between align-center mb-3">
                            <div>
                                <strong>Soal <?= $i + 1 ?></strong>
                                <span class="badge badge-<?= $q['type'] === 'multiple_choice' ? 'primary' : 'warning' ?> ml-2" style="margin-left:8px;"><?= $q['type'] === 'multiple_choice' ? 'Pilihan Ganda' : 'Essay' ?></span>
                                <span class="text-sm text-muted" style="margin-left:8px;"><?= $q['points'] ?> poin</span>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline" onclick="toggleEditQuestion(<?= $q['id'] ?>)">Edit</button>
                                <button class="btn btn-sm btn-danger" data-confirm="Hapus soal ini?" data-action="<?= url('/questions/' . $q['id'] . '/delete') ?>">Hapus</button>
                            </div>
                        </div>

                        <div style="line-height:1.6;"><?= nl2br(e($q['question_text'])) ?></div>

                        <?php if ($q['type'] === 'multiple_choice' && $q['options']): ?>
                            <?php $options = json_decode($q['options'], true); ?>
                            <?php if ($options): ?>
                                <ul style="margin-top:8px;padding-left:20px;color:var(--text-secondary);font-size:var(--font-size-sm);">
                                    <?php foreach ($options as $opt): ?>
                                        <li style="margin-bottom:4px;" class="<?= strtoupper(trim(substr($opt, 0, 1))) === strtoupper(trim($q['correct_answer'])) ? 'text-success font-bold' : '' ?>">
                                            <?= e($opt) ?>
                                            <?php if (strtoupper(trim(substr($opt, 0, 1))) === strtoupper(trim($q['correct_answer']))): ?>
                                                <span class="text-success">✓</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($q['explanation']): ?>
                            <div class="text-sm text-muted mt-2"><em>Penjelasan: <?= e($q['explanation']) ?></em></div>
                        <?php endif; ?>

                        <!-- Edit Form (hidden by default) -->
                        <div id="editQuestion-<?= $q['id'] ?>" style="display:none;margin-top:16px;padding-top:16px;border-top:1px solid var(--border-color);">
                            <form method="POST" action="<?= url('/questions/' . $q['id'] . '/update') ?>">
                                <?= csrf_field() ?>
                                <div class="form-group">
                                    <label class="form-label">Teks Soal <span class="required">*</span></label>
                                    <textarea name="question_text" class="form-control" rows="3" required><?= e($q['question_text']) ?></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Tipe</label>
                                        <select name="type" class="form-control" onchange="toggleOptionsEdit(this, <?= $q['id'] ?>)">
                                            <option value="multiple_choice" <?= $q['type'] === 'multiple_choice' ? 'selected' : '' ?>>Pilihan Ganda</option>
                                            <option value="essay" <?= $q['type'] === 'essay' ? 'selected' : '' ?>>Essay</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Poin <span class="required">*</span></label>
                                        <input type="number" name="points" class="form-control" value="<?= e($q['points']) ?>" min="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Urutan</label>
                                        <input type="number" name="sort_order" class="form-control" value="<?= e($q['sort_order']) ?>" min="0">
                                    </div>
                                </div>
                                <div id="editOptions-<?= $q['id'] ?>" style="<?= $q['type'] === 'essay' ? 'display:none;' : '' ?>">
                                    <?php $opts = $q['options'] ? json_decode($q['options'], true) : ['A. ', 'B. ', 'C. ', 'D. ']; ?>
                                    <div class="form-group">
                                        <label class="form-label">Opsi Jawaban</label>
                                        <?php foreach ($opts as $oi => $opt): ?>
                                            <input type="text" name="options[]" class="form-control mb-2" value="<?= e($opt) ?>" placeholder="Opsi <?= chr(65 + $oi) ?>" style="margin-bottom:8px;">
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Jawaban Benar (Huruf: A, B, C, D)</label>
                                        <input type="text" name="correct_answer" class="form-control" value="<?= e($q['correct_answer']) ?>" placeholder="A" style="max-width:100px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Penjelasan (Opsional)</label>
                                    <textarea name="explanation" class="form-control" rows="2"><?= e($q['explanation']) ?></textarea>
                                </div>
                                <div class="btn-group mt-2">
                                    <button type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="toggleEditQuestion(<?= $q['id'] ?>)">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add New Question -->
    <div class="card" style="max-width:800px;">
        <div class="card-header"><h3>Tambah Pertanyaan Baru</h3></div>
        <div class="card-body">
            <form method="POST" action="<?= url('/quizzes/' . $quiz['id'] . '/questions') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Teks Soal <span class="required">*</span></label>
                    <textarea name="question_text" class="form-control" rows="3" required placeholder="Tulis pertanyaan di sini..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tipe Soal</label>
                        <select name="type" class="form-control" id="newQuestionType" onchange="toggleNewOptions()">
                            <option value="multiple_choice">Pilihan Ganda</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Poin <span class="required">*</span></label>
                        <input type="number" name="points" class="form-control" value="10" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= count($questions) + 1 ?>" min="0">
                    </div>
                </div>

                <div id="newQuestionOptions">
                    <div class="form-group">
                        <label class="form-label">Opsi Jawaban</label>
                        <input type="text" name="options[]" class="form-control mb-2" placeholder="A. ..." style="margin-bottom:8px;">
                        <input type="text" name="options[]" class="form-control mb-2" placeholder="B. ..." style="margin-bottom:8px;">
                        <input type="text" name="options[]" class="form-control mb-2" placeholder="C. ..." style="margin-bottom:8px;">
                        <input type="text" name="options[]" class="form-control mb-2" placeholder="D. ..." style="margin-bottom:8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jawaban Benar (Huruf: A, B, C, D)</label>
                        <input type="text" name="correct_answer" class="form-control" placeholder="A" style="max-width:100px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Penjelasan Jawaban (Opsional)</label>
                    <textarea name="explanation" class="form-control" rows="2" placeholder="Penjelasan mengapa jawaban tersebut benar..."></textarea>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary">Tambah Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleEditQuestion(id) {
        const el = document.getElementById('editQuestion-' + id);
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    function toggleOptionsEdit(select, id) {
        const el = document.getElementById('editOptions-' + id);
        el.style.display = select.value === 'essay' ? 'none' : '';
    }

    function toggleNewOptions() {
        const type = document.getElementById('newQuestionType').value;
        document.getElementById('newQuestionOptions').style.display = type === 'essay' ? 'none' : '';
    }
</script>
