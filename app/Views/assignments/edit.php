<?php /** Edit Assignment Form */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Edit Tugas</h1>
        <p class="text-muted">Mata Kuliah: <strong><?= e($assignment['course_name']) ?></strong></p>
    </div>

    <div class="card" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="<?= url('/assignments/' . $assignment['id'] . '/update') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Judul Tugas <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= e($assignment['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi / Instruksi</label>
                    <textarea name="description" class="form-control" rows="5"><?= e($assignment['description']) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">File Lampiran</label>
                    <?php if (!empty($assignment['file_path'])): ?>
                        <div style="margin-bottom: 8px; padding: 8px; border: 1px solid var(--border-color); border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; background: var(--bg-secondary);">
                            <a href="<?= upload_url($assignment['file_path']) ?>" target="_blank" class="text-primary text-sm font-bold"><?= e($assignment['file_name']) ?></a>
                            <label style="margin: 0; display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--text-tertiary); cursor: pointer;">
                                <input type="checkbox" name="remove_file" value="1"> Hapus file ini
                            </label>
                        </div>
                        <div style="font-size: 12px; color: var(--text-tertiary); margin-bottom: 4px;">Atau unggah file baru untuk menggantinya:</div>
                    <?php endif; ?>
                    <input type="file" name="file" class="form-control">
                    <small class="text-muted">PDF, Word, Excel, ZIP (Max 10MB)</small>
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
