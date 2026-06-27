<?php /** Edit Assignment Form */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Edit Tugas</h1>
        <p class="text-muted">Mata Kuliah: <strong><?= e($assignment['course_name']) ?></strong></p>
    </div>

    <div class="card" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="<?= url('/assignments/' . $assignment['id'] . '/update') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Judul Tugas <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= e($assignment['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi / Instruksi</label>
                    <textarea name="description" class="form-control" rows="5"><?= e($assignment['description']) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tenggat Waktu (Deadline) <span class="required">*</span></label>
                        <input type="datetime-local" name="deadline" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($assignment['deadline'])) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Skor Maksimal <span class="required">*</span></label>
                        <input type="number" name="max_score" class="form-control" value="<?= e($assignment['max_score']) ?>" min="1" max="100" required>
                    </div>
                </div>

                <div class="form-row mt-2">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="file_required" value="1" <?= $assignment['file_required'] ? 'checked' : '' ?>>
                            Wajib Upload File Lampiran
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="allow_late" value="1" <?= $assignment['allow_late'] ? 'checked' : '' ?>>
                            Izinkan Pengumpulan Terlambat
                        </label>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="form-check">
                        <input type="checkbox" name="is_published" value="1" <?= $assignment['is_published'] ? 'checked' : '' ?>>
                        Terbitkan tugas
                    </label>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= url('/assignments/' . $assignment['id']) ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
