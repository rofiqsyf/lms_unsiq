<?php /** Isi Presensi */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Isi Presensi</h1>
            <p class="text-muted">Mata Kuliah: <strong><?= e($attendance['course_name']) ?></strong></p>
        </div>
        <div class="btn-group">
            <a href="<?= url('/courses/' . $attendance['course_id'] . '/attendance') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-between align-center" style="flex-wrap:wrap;gap:16px;">
                <div>
                    <h3 class="mb-1">Pertemuan Ke-<?= $attendance['meeting_number'] ?></h3>
                    <div class="text-muted"><?= format_date($attendance['meeting_date'], 'l, d M Y') ?></div>
                </div>
                <?php if ($attendance['topic']): ?>
                    <div class="stat-card stat-info" style="min-width:250px;">
                        <div class="text-sm">Topik Pembahasan</div>
                        <div class="font-medium mt-1"><?= e($attendance['topic']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <form method="POST" action="<?= url('/attendance/' . $attendance['id'] . '/update') ?>">
                <?= csrf_field() ?>
                
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:50px;">No</th>
                                <th>Mahasiswa</th>
                                <th>Status Kehadiran</th>
                                <th>Catatan Tambahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($records as $r): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="d-flex align-center gap-2">
                                            <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">
                                                <?php if ($r['avatar']): ?>
                                                    <img src="<?= upload_url($r['avatar']) ?>" alt="Avatar">
                                                <?php else: ?>
                                                    <span class="avatar-initial"><?= strtoupper(substr($r['student_name'], 0, 1)) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <strong><?= e($r['student_name']) ?></strong>
                                                <div class="text-xs text-muted"><?= e($r['nim_nidn'] ?? '-') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-3">
                                            <label class="form-check" style="margin:0;cursor:pointer;">
                                                <input type="radio" name="status[<?= $r['user_id'] ?>]" value="hadir" <?= $r['status'] === 'hadir' ? 'checked' : '' ?>>
                                                <span class="text-success font-medium">Hadir</span>
                                            </label>
                                            <label class="form-check" style="margin:0;cursor:pointer;">
                                                <input type="radio" name="status[<?= $r['user_id'] ?>]" value="izin" <?= $r['status'] === 'izin' ? 'checked' : '' ?>>
                                                <span class="text-info font-medium">Izin</span>
                                            </label>
                                            <label class="form-check" style="margin:0;cursor:pointer;">
                                                <input type="radio" name="status[<?= $r['user_id'] ?>]" value="sakit" <?= $r['status'] === 'sakit' ? 'checked' : '' ?>>
                                                <span class="text-warning font-medium">Sakit</span>
                                            </label>
                                            <label class="form-check" style="margin:0;cursor:pointer;">
                                                <input type="radio" name="status[<?= $r['user_id'] ?>]" value="alpa" <?= $r['status'] === 'alpa' ? 'checked' : '' ?>>
                                                <span class="text-danger font-medium">Alpa</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="notes[<?= $r['user_id'] ?>]" class="form-control form-control-sm" value="<?= e($r['notes']) ?>" placeholder="Catatan opsional">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer p-3" style="background:var(--bg-secondary);border-top:1px solid var(--border-color);">
                    <button type="submit" class="btn btn-primary">Simpan Presensi</button>
                    <span class="text-sm text-muted ml-3">Perubahan akan langsung tersimpan.</span>
                </div>
            </form>
        </div>
    </div>
</div>
