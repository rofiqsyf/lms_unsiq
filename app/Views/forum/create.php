<?php /** Create Forum Thread Form */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Buat Topik Diskusi</h1>
            <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
        </div>
    </div>

    <div class="card" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="<?= url('/courses/' . $course['id'] . '/forum') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Judul Topik <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= e(old('title')) ?>" required autofocus placeholder="Contoh: Pertanyaan terkait materi Bab 1">
                </div>

                <div class="form-group">
                    <label class="form-label">Isi Diskusi <span class="required">*</span></label>
                    <textarea name="body" class="form-control" rows="8" required placeholder="Tuliskan isi diskusi Anda di sini..."><?= e(old('body')) ?></textarea>
                </div>

                <?php if (has_role('admin', 'dosen')): ?>
                    <div class="form-row mt-3">
                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_pinned" value="1">
                                Sematkan (Pin) topik di urutan atas
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="form-check">
                                <input type="checkbox" name="is_locked" value="1">
                                Kunci diskusi (Hanya admin/dosen yang bisa membalas)
                            </label>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary">Posting Diskusi</button>
                    <a href="<?= url('/courses/' . $course['id'] . '/forum') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
