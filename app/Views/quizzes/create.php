<?php /** Create Quiz Form */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Tambah Kuis</h1>
        <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
    </div>

    <div class="card" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="<?= url('/courses/' . $course['id'] . '/quizzes') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Judul Kuis <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi / Instruksi</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Durasi (Menit) <span class="required">*</span></label>
                        <input type="number" name="duration_minutes" class="form-control" value="60" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Percobaan <span class="required">*</span></label>
                        <input type="number" name="max_attempts" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nilai KKM (Passing Score)</label>
                        <input type="number" name="passing_score" class="form-control" value="60" min="0" max="100">
                    </div>
                </div>

                <div class="form-row mt-2 mb-3">
                    <div class="form-group">
                        <label class="form-label">Waktu Mulai (Opsional)</label>
                        <input type="datetime-local" name="start_time" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Waktu Berakhir (Opsional)</label>
                        <input type="datetime-local" name="end_time" class="form-control">
                    </div>
                </div>

                <div class="form-row mt-2">
                    <div class="form-group"><label class="form-check"><input type="checkbox" name="shuffle_questions" value="1" checked> Acak Pertanyaan</label></div>
                    <div class="form-group"><label class="form-check"><input type="checkbox" name="show_result" value="1" checked> Tampilkan Hasil Setelah Selesai</label></div>
                    <div class="form-group"><label class="form-check"><input type="checkbox" name="is_published" value="1" checked> Publish Kuis</label></div>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary">Buat Kuis</button>
                    <a href="<?= url('/courses/' . $course['id']) ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
