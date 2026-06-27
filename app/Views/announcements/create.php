<?php /** Create Announcement Form */ ?>
<div class="animate-fade-in">
    <div class="page-header"><h1>Buat Pengumuman</h1></div>

    <div class="card" style="max-width:800px;">
        <div class="card-body">
            <form method="POST" action="<?= url('/announcements') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Tujuan Pengumuman</label>
                    <select name="course_id" class="form-control">
                        <option value="">Semua Mahasiswa (UMUM)</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['id'] ?>">Mata Kuliah: <?= e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Judul Pengumuman <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Isi Pengumuman <span class="required">*</span></label>
                    <textarea name="content" class="form-control" rows="8" required></textarea>
                </div>

                <div class="form-group mt-3">
                    <label class="form-check">
                        <input type="checkbox" name="is_pinned" value="1">
                        Sematkan (Pin) pengumuman di posisi atas
                    </label>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary">Publikasikan</button>
                    <a href="<?= url('/announcements') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
