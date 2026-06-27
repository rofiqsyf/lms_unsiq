<?php /** Create/Edit Material Form */ $isEdit = isset($material); ?>
<div class="animate-fade-in">
    <div class="page-header">
        <h1><?= $isEdit ? 'Edit Materi' : 'Tambah Materi' ?></h1>
        <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
    </div>

    <div class="card" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="<?= $isEdit ? url('/materials/' . $material['id'] . '/update') : url('/courses/' . $course['id'] . '/materials') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Judul Materi <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= e($isEdit ? $material['title'] : old('title')) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Bagian / Sesi (opsional)</label>
                    <input type="text" name="section" class="form-control" placeholder="Contoh: Pertemuan 1, Bab Pendahuluan" value="<?= e($isEdit ? $material['section'] : old('section')) ?>">
                    <div class="form-text">Gunakan ini untuk mengelompokkan materi.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Konten / Deskripsi Singkat</label>
                    <textarea name="content" class="form-control" rows="5"><?= e($isEdit ? $material['content'] : old('content')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">URL Video (opsional)</label>
                        <input type="url" name="video_url" class="form-control" placeholder="Link YouTube/Vimeo" value="<?= e($isEdit ? $material['video_url'] : old('video_url')) ?>">
                    </div>
                    <?php if ($isEdit): ?>
                    <div class="form-group">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= $material['sort_order'] ?>" min="1">
                    </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Upload File Pendukung (opsional)</label>
                    <?php if ($isEdit && $material['file_path']): ?>
                        <div class="mb-2 text-sm text-muted">File saat ini: <strong><?= e($material['file_name']) ?></strong> (Upload baru akan menggantikan file lama)</div>
                    <?php endif; ?>
                    <div class="file-input-wrapper">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary mb-2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <span class="file-label font-semibold">Klik untuk upload file</span>
                        <span class="text-xs text-muted">PDF, PPT, DOCX, dll (Max 10MB)</span>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="form-check">
                        <input type="checkbox" name="is_published" value="1" <?= ($isEdit ? $material['is_published'] : 1) ? 'checked' : '' ?>>
                        Langsung terbitkan materi (Bisa dilihat oleh mahasiswa)
                    </label>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Materi' ?></button>
                    <a href="<?= url('/courses/' . $course['id']) ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
