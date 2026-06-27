<?php /** Kelola Presensi (Dosen/Admin) */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Kelola Presensi</h1>
            <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
        </div>
        <div class="btn-group">
            <a href="<?= url('/courses/' . $course['id']) ?>" class="btn btn-secondary">Kembali</a>
            <a href="<?= url('/courses/' . $course['id'] . '/attendance/recap') ?>" class="btn btn-outline">Lihat Rekap</a>
            <a href="<?= url('/courses/' . $course['id'] . '/attendance/create') ?>" class="btn btn-primary">Buat Pertemuan</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-between align-center" style="flex-wrap: wrap; gap: 16px;">
            <h3>Daftar Pertemuan</h3>
            <span class="badge badge-secondary"><?= $studentCount ?> Mahasiswa Terdaftar</span>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (empty($attendances)): ?>
                <div class="empty-state" style="padding:48px;">
                    <p>Belum ada pertemuan yang dibuat.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pertemuan</th>
                                <th>Tanggal</th>
                                <th>Topik</th>
                                <th>Kehadiran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendances as $a): ?>
                                <?php $percentage = $a['total_count'] > 0 ? round(($a['hadir_count'] / $studentCount) * 100) : 0; ?>
                                <tr>
                                    <td><strong>Ke-<?= $a['meeting_number'] ?></strong></td>
                                    <td><?= format_date($a['meeting_date'], 'd M Y') ?></td>
                                    <td><?= e($a['topic'] ?: '-') ?></td>
                                    <td>
                                        <div class="d-flex align-center gap-2">
                                            <div style="flex:1;background:var(--border-color);height:6px;border-radius:3px;overflow:hidden;max-width:100px;">
                                                <div style="width:<?= $percentage ?>%;height:100%;background:var(--success-color);"></div>
                                            </div>
                                            <span class="text-xs text-muted"><?= $a['hadir_count'] ?>/<?= $studentCount ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?= url('/attendance/' . $a['id']) ?>" class="btn btn-sm btn-primary">Isi Presensi</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
