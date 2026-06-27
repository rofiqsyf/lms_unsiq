<?php /** Buat Pertemuan Baru */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Buat Pertemuan</h1>
            <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
        </div>
    </div>

    <div class="card" style="max-width:600px;">
        <div class="card-body">
            <form method="POST" action="<?= url('/courses/' . $course['id'] . '/attendance') ?>">
                <?= csrf_field() ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pertemuan Ke- <span class="required">*</span></label>
                        <input type="number" name="meeting_number" class="form-control" value="<?= $nextMeeting ?>" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Pertemuan <span class="required">*</span></label>
                        <input type="date" name="meeting_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Topik Pembahasan (Opsional)</label>
                    <input type="text" name="topic" class="form-control" placeholder="Contoh: Pengantar Algoritma" autofocus>
                </div>

                <div class="alert alert-info mt-3">
                    <div class="alert-message">
                        Saat Anda menyimpan, presensi semua mahasiswa terdaftar akan diinisialisasi sebagai <strong>Alpa</strong> secara otomatis.
                    </div>
                </div>

                <div class="btn-group mt-4">
                    <button type="submit" class="btn btn-primary">Simpan dan Isi Presensi</button>
                    <a href="<?= url('/courses/' . $course['id'] . '/attendance') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
