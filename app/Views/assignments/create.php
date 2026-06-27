<?php /** Create Assignment Form */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1>Tambah Tugas</h1>
        <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
    </div>

    <div class="card" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="<?= url('/courses/' . $course['id'] . '/assignments') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Judul Tugas <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi / Instruksi</label>
                    <textarea name="description" class="form-control" rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">File Lampiran (Opsional)</label>
                    <input type="file" name="file" class="form-control">
                    <small class="text-muted">PDF, Word, Excel, ZIP (Max 10MB)</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tenggat Waktu (Deadline) <span class="required">*</span></label>
                        <input type="datetime-local" name="deadline" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Skor Maksimal <span class="required">*</span></label>
                        <input type="number" name="max_score" class="form-control" value="100" min="1" max="100" required>
                    </div>
                </div>

                <div class="form-row mt-2">
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="file_required" value="1" checked>
                            Wajib Upload File Lampiran
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="allow_late" value="1" id="allowLateToggle">
                            Izinkan Pengumpulan Terlambat
                        </label>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="form-check">
                        <input type="checkbox" name="is_published" value="1" checked>
                        Langsung terbitkan tugas
                    </label>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Tugas</button>
                    <a href="<?= url('/courses/' . $course['id']) ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
