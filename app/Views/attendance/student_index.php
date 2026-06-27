<?php /** Presensi Saya (Mahasiswa) */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Presensi Saya</h1>
            <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
        </div>
        <div class="btn-group">
            <a href="<?= url('/courses/' . $course['id']) ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="stats-grid">
                <?php 
                    $h = $i = $s = $a = 0;
                    foreach ($studentAttendance as $att) {
                        if ($att['status'] === 'hadir') $h++;
                        elseif ($att['status'] === 'izin') $i++;
                        elseif ($att['status'] === 'sakit') $s++;
                        else $a++;
                    }
                    $total = $h + $i + $s + $a;
                    $percent = $total > 0 ? round(($h / $total) * 100) : 0;
                ?>
                <div class="stat-card stat-success">
                    <div class="text-sm">Hadir</div>
                    <div class="stat-value"><?= $h ?></div>
                </div>
                <div class="stat-card stat-info">
                    <div class="text-sm">Izin</div>
                    <div class="stat-value"><?= $i ?></div>
                </div>
                <div class="stat-card stat-warning">
                    <div class="text-sm">Sakit</div>
                    <div class="stat-value"><?= $s ?></div>
                </div>
                <div class="stat-card stat-danger">
                    <div class="text-sm">Alpa</div>
                    <div class="stat-value"><?= $a ?></div>
                </div>
            </div>
            
            <div class="mt-4 p-3" style="background:var(--bg-secondary);border-radius:var(--border-radius);">
                <div class="d-flex justify-between mb-1">
                    <span class="text-sm font-medium">Persentase Kehadiran</span>
                    <span class="text-sm font-bold"><?= $percent ?>%</span>
                </div>
                <div style="background:var(--border-color);height:8px;border-radius:4px;overflow:hidden;">
                    <div style="width:<?= $percent ?>%;height:100%;background:<?= $percent >= 75 ? 'var(--success-color)' : 'var(--danger-color)' ?>;"></div>
                </div>
                <?php if ($total > 0 && $percent < 75): ?>
                    <p class="text-xs text-danger mt-2">Peringatan: Persentase kehadiran Anda di bawah 75%.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Riwayat Pertemuan</h3></div>
        <div class="card-body" style="padding:0;">
            <?php if (empty($studentAttendance)): ?>
                <div class="empty-state" style="padding:48px;">Belum ada pertemuan.</div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead><tr><th>Pertemuan</th><th>Tanggal</th><th>Topik</th><th>Status</th><th>Catatan</th></tr></thead>
                        <tbody>
                            <?php foreach ($studentAttendance as $att): ?>
                                <tr>
                                    <td>Ke-<?= $att['meeting_number'] ?></td>
                                    <td><?= format_date($att['meeting_date'], 'd M Y') ?></td>
                                    <td><?= e($att['topic'] ?: '-') ?></td>
                                    <td>
                                        <?php if ($att['status'] === 'hadir'): ?>
                                            <span class="badge badge-success">Hadir</span>
                                        <?php elseif ($att['status'] === 'izin'): ?>
                                            <span class="badge badge-info">Izin</span>
                                        <?php elseif ($att['status'] === 'sakit'): ?>
                                            <span class="badge badge-warning">Sakit</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Alpa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-sm text-muted"><?= e($att['notes'] ?: '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
