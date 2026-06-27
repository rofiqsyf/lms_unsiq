<?php /** Rekap Presensi */ ?>
<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1>Rekap Presensi</h1>
            <p class="text-muted">Mata Kuliah: <strong><?= e($course['name']) ?></strong></p>
        </div>
        <div class="btn-group">
            <a href="<?= url('/courses/' . $course['id'] . '/attendance') ?>" class="btn btn-secondary">Kembali</a>
            <a href="<?= url('/courses/' . $course['id'] . '/attendance/export') ?>" class="btn btn-outline" target="_blank">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding:0;">
            <div class="table-wrapper" style="overflow-x:auto;">
                <table class="table" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width:50px;border-right:1px solid var(--border-color);">No</th>
                            <th rowspan="2" style="min-width:200px;border-right:1px solid var(--border-color);">Mahasiswa</th>
                            <th colspan="<?= count($attendances) ?>" style="text-align:center;border-bottom:1px solid var(--border-color);border-right:1px solid var(--border-color);">Pertemuan Ke-</th>
                            <th colspan="5" style="text-align:center;border-bottom:1px solid var(--border-color);">Rekapitulasi</th>
                        </tr>
                        <tr>
                            <?php foreach ($attendances as $a): ?>
                                <th style="width:40px;text-align:center;font-size:12px;" title="<?= format_date($a['meeting_date'], 'd M Y') ?>"><?= $a['meeting_number'] ?></th>
                            <?php endforeach; ?>
                            <?php if (empty($attendances)): ?><th>-</th><?php endif; ?>
                            
                            <th style="width:40px;text-align:center;color:var(--success-color);" title="Hadir">H</th>
                            <th style="width:40px;text-align:center;color:var(--info-color);" title="Izin">I</th>
                            <th style="width:40px;text-align:center;color:var(--warning-color);" title="Sakit">S</th>
                            <th style="width:40px;text-align:center;color:var(--danger-color);" title="Alpa">A</th>
                            <th style="width:60px;text-align:center;" title="Persentase">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recap)): ?>
                            <tr><td colspan="100%" class="text-center p-4">Belum ada data mahasiswa terdaftar.</td></tr>
                        <?php else: ?>
                            <?php 
                                $no = 1;
                                $recordModel = new \App\Models\AttendanceRecord();
                                foreach ($recap as $r): 
                                    // Calculate percentage
                                    $totalMeetings = count($attendances);
                                    $percentage = $totalMeetings > 0 ? round(($r['hadir'] / $totalMeetings) * 100) : 0;
                                    
                                    // Fetch individual records for this student to show in columns
                                    $studentRecords = $recordModel->getStudentAttendance($course['id'], $r['id']);
                                    // Map by meeting number
                                    $sMap = [];
                                    foreach ($studentRecords as $sr) {
                                        $sMap[$sr['meeting_number']] = $sr['status'];
                                    }
                            ?>
                                <tr>
                                    <td style="border-right:1px solid var(--border-color);"><?= $no++ ?></td>
                                    <td style="border-right:1px solid var(--border-color);">
                                        <strong><?= e($r['student_name']) ?></strong>
                                        <div class="text-xs text-muted"><?= e($r['nim_nidn'] ?? '-') ?></div>
                                    </td>
                                    
                                    <?php foreach ($attendances as $a): ?>
                                        <?php 
                                            $st = $sMap[$a['meeting_number']] ?? 'alpa'; 
                                            $icon = '';
                                            if ($st === 'hadir') $icon = '<span class="text-success font-bold">H</span>';
                                            elseif ($st === 'izin') $icon = '<span class="text-info font-bold">I</span>';
                                            elseif ($st === 'sakit') $icon = '<span class="text-warning font-bold">S</span>';
                                            else $icon = '<span class="text-danger font-bold">A</span>';
                                        ?>
                                        <td style="text-align:center;font-size:12px;"><?= $icon ?></td>
                                    <?php endforeach; ?>
                                    <?php if (empty($attendances)): ?><td></td><?php endif; ?>

                                    <td style="text-align:center;border-left:1px solid var(--border-color);font-weight:bold;"><?= $r['hadir'] ?></td>
                                    <td style="text-align:center;"><?= $r['izin'] ?></td>
                                    <td style="text-align:center;"><?= $r['sakit'] ?></td>
                                    <td style="text-align:center;"><?= $r['alpa'] ?></td>
                                    <td style="text-align:center;">
                                        <span class="badge <?= $percentage >= 75 ? 'badge-success' : 'badge-danger' ?>" style="font-size:11px;">
                                            <?= $percentage ?>%
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer p-3 bg-secondary text-sm">
                <strong>Keterangan:</strong> <span class="text-success font-bold ml-2">H</span> = Hadir, <span class="text-info font-bold ml-2">I</span> = Izin, <span class="text-warning font-bold ml-2">S</span> = Sakit, <span class="text-danger font-bold ml-2">A</span> = Alpa
            </div>
        </div>
    </div>
</div>
