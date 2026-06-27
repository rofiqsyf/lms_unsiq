<?php /** Create/Edit Course Form */ $isEdit = isset($course); ?>
<div class="animate-fade-in">
    <div class="page-header"><h1><?= $isEdit ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' ?></h1></div>

    <div class="card" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="<?= $isEdit ? url('/courses/' . $course['id'] . '/update') : url('/courses') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kode MK <span class="required">*</span></label>
                        <input type="text" name="code" class="form-control" value="<?= e($isEdit ? $course['code'] : old('code')) ?>" required placeholder="MBKP-07.03.204">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Mata Kuliah <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= e($isEdit ? $course['name'] : old('name')) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4"><?= e($isEdit ? $course['description'] : old('description')) ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">SKS <span class="required">*</span></label>
                        <input type="number" name="sks" class="form-control" value="<?= e($isEdit ? $course['sks'] : old('sks', '3')) ?>" min="1" max="6" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-control">
                            <option value="">Tanpa Kategori</option>
                            <?php foreach ($categories ?? [] as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($isEdit ? $course['category_id'] : old('category_id')) == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-control">
                            <option value="">Pilih Semester</option>
                            <option value="Ganjil" <?= ($isEdit ? $course['semester'] : old('semester')) === 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                            <option value="Genap" <?= ($isEdit ? $course['semester'] : old('semester')) === 'Genap' ? 'selected' : '' ?>>Genap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Akademik</label>
                        <input type="text" name="academic_year" class="form-control" placeholder="2025/2026" value="<?= e($isEdit ? $course['academic_year'] : old('academic_year')) ?>">
                    </div>
                </div>

                <?php if (has_role('admin')): ?>
                <div class="form-group">
                    <label class="form-label">Dosen Pengampu</label>
                    <select name="dosen_id" class="form-control">
                        <?php foreach ($dosens ?? [] as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= ($isEdit ? $course['dosen_id'] : old('dosen_id')) == $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?> (<?= e($d['nim_nidn']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="draft" <?= ($isEdit ? $course['status'] : 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= ($isEdit ? $course['status'] : '') === 'published' ? 'selected' : '' ?>>Published</option>
                            <option value="archived" <?= ($isEdit ? $course['status'] : '') === 'archived' ? 'selected' : '' ?>>Archived</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan' : 'Tambah' ?></button>
                    <a href="<?= url('/courses') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
