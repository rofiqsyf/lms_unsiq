<?php /** @var array $currentUser */ ?>
<div class="animate-fade-in">
    <div class="welcome-banner">
        <h2>📚 Halo, <?= e($currentUser['name'] ?? 'Dosen') ?>!</h2>
        <p>Kelola mata kuliah, nilai tugas, dan pantau aktivitas mahasiswa Anda.</p>
    </div>

    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
        <!-- Card 1: Mata Kuliah Saya -->
        <div onclick="window.location.href='<?= url('/courses') ?>'" style="cursor: pointer; text-decoration: none; color: inherit; background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <div style="height: 110px; background: linear-gradient(135deg, #c4b5fd, #a78bfa); padding: 12px; position: relative;">
                <span style="background: rgba(30, 41, 59, 0.75); color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; backdrop-filter: blur(4px);">
                    <?= $totalMyCourses ?? 0 ?> Aktif
                </span>
                <!-- Decorative pattern -->
                <svg width="100" height="100" style="position: absolute; right: -10px; bottom: -20px; opacity: 0.2; color: white;" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 22h20L12 2z"/></svg>
            </div>
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; color: #4f46e5; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    <span>Akademik</span>
                </div>
                <h4 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; line-height: 1.3;">
                    Mata Kuliah Saya
                </h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <a href="<?= url('/courses') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Dikelola</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Mahasiswa -->
        <div onclick="window.location.href='<?= url('/courses') ?>'" style="cursor: pointer; text-decoration: none; color: inherit; background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <div style="height: 110px; background: linear-gradient(135deg, #a7f3d0, #6ee7b7); padding: 12px; position: relative;">
                <span style="background: rgba(30, 41, 59, 0.75); color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; backdrop-filter: blur(4px);">
                    <?= $totalStudents ?? 0 ?> Siswa
                </span>
                <!-- Decorative pattern -->
                <svg width="100" height="100" style="position: absolute; right: -10px; bottom: -20px; opacity: 0.2; color: #047857;" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
            </div>
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; color: #059669; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <span>Statistik</span>
                </div>
                <h4 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; line-height: 1.3;">
                    Total Mahasiswa
                </h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <a href="<?= url('/courses') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Terdaftar</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Tugas Belum Dinilai -->
        <div onclick="window.location.href='<?= url('/courses') ?>'" style="cursor: pointer; text-decoration: none; color: inherit; background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <div style="height: 110px; background: linear-gradient(135deg, #fecaca, #fca5a5); padding: 12px; position: relative;">
                <span style="background: rgba(30, 41, 59, 0.75); color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; backdrop-filter: blur(4px);">
                    <?= $pendingGrading ?? 0 ?> Tugas
                </span>
                <!-- Decorative pattern -->
                <svg width="100" height="100" style="position: absolute; right: -10px; bottom: -20px; opacity: 0.2; color: #b91c1c;" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; color: #dc2626; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>Penilaian</span>
                </div>
                <h4 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; line-height: 1.3;">
                    Tugas Belum Dinilai
                </h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <a href="<?= url('/courses') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Butuh Perhatian</a>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="card">
            <div class="card-header"><h3>📖 Mata Kuliah Saya</h3><a href="<?= url('/courses') ?>" class="btn btn-sm btn-secondary">Lihat Semua</a></div>
            <div class="card-body">
                <?php if (!empty($myCourses)): ?>
                    <?php foreach ($myCourses as $c): ?>
                        <div class="course-mini-card">
                            <div class="course-mini-img"><?= strtoupper(substr($c['name'], 0, 2)) ?></div>
                            <div class="course-mini-info">
                                <h4><a href="<?= url('/courses/' . $c['id']) ?>"><?= e($c['name']) ?></a></h4>
                                <p><?= e($c['code']) ?> • <?= $c['student_count'] ?? 0 ?> mahasiswa</p>
                            </div>
                            <div><?= status_badge($c['status']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state"><p>Anda belum memiliki mata kuliah.</p></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>📝 Pengumpulan Terbaru</h3></div>
            <div class="card-body">
                <?php if (!empty($recentSubmissions)): ?>
                    <?php foreach ($recentSubmissions as $s): ?>
                        <div class="activity-item">
                            <div class="activity-icon icon-assignment"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg></div>
                            <div class="activity-content">
                                <p><strong><?= e($s['student_name']) ?></strong> mengumpulkan</p>
                                <p class="text-sm"><?= e($s['assignment_title']) ?> — <?= e($s['course_name']) ?></p>
                                <span class="activity-time"><?= time_ago($s['submitted_at']) ?></span>
                            </div>
                            <div><?= status_badge($s['status']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state"><p>Belum ada pengumpulan.</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
