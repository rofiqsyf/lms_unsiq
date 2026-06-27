<?php /** @var array $currentUser */ ?>
<div class="animate-fade-in">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <h2>👋 Halo, Administrator!</h2>
        <p>Selamat datang di panel administrasi LMS UNSIQ. Berikut ringkasan platform Anda.</p>
    </div>

    <!-- Stats Grid (Redesigned as Course-like Cards) -->
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
        <!-- Card 1: Total Users -->
        <div onclick="window.location.href='<?= url('/users') ?>'" style="cursor: pointer; text-decoration: none; color: inherit; background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <div style="height: 110px; background: linear-gradient(135deg, #c4b5fd, #a78bfa); padding: 12px; position: relative;">
                <span style="background: rgba(30, 41, 59, 0.75); color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; backdrop-filter: blur(4px);">
                    <?= $totalUsers ?? 0 ?> Terdaftar
                </span>
                <!-- Decorative pattern -->
                <svg width="100" height="100" style="position: absolute; right: -10px; bottom: -20px; opacity: 0.2; color: white;" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 22h20L12 2z"/></svg>
            </div>
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; color: #4f46e5; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    <span>Statistik</span>
                </div>
                <h4 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; line-height: 1.3;">
                    Total Seluruh Users
                </h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <a href="<?= url('/users') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Semua Role</a>
                    <a href="<?= url('/users?role=admin') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Sistem</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Mata Kuliah Aktif -->
        <div onclick="window.location.href='<?= url('/courses') ?>'" style="cursor: pointer; text-decoration: none; color: inherit; background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <div style="height: 110px; background: linear-gradient(135deg, #fde68a, #fcd34d); padding: 12px; position: relative;">
                <span style="background: rgba(30, 41, 59, 0.75); color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; backdrop-filter: blur(4px);">
                    <?= $activeCourses ?? 0 ?> Aktif
                </span>
                <!-- Decorative pattern -->
                <svg width="100" height="100" style="position: absolute; right: -10px; bottom: -20px; opacity: 0.2; color: #b45309;" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
            </div>
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; color: #d97706; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    <span>Akademik</span>
                </div>
                <h4 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; line-height: 1.3;">
                    Mata Kuliah Aktif
                </h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <a href="<?= url('/courses?status=published') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Published</a>
                    <a href="<?= url('/courses?status=published') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Berjalan</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Dosen -->
        <div onclick="window.location.href='<?= url('/users?role=dosen') ?>'" style="cursor: pointer; text-decoration: none; color: inherit; background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <div style="height: 110px; background: linear-gradient(135deg, #bfdbfe, #93c5fd); padding: 12px; position: relative;">
                <span style="background: rgba(30, 41, 59, 0.75); color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; backdrop-filter: blur(4px);">
                    <?= $totalDosen ?? 0 ?> Dosen
                </span>
                <!-- Decorative pattern -->
                <svg width="100" height="100" style="position: absolute; right: -10px; bottom: -20px; opacity: 0.2; color: #1d4ed8;" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/></svg>
            </div>
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; color: #2563eb; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.66 4 3 6 3s6-1.34 6-3v-5"/></svg>
                    <span>Pengajar</span>
                </div>
                <h4 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; line-height: 1.3;">
                    Total Dosen
                </h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <a href="<?= url('/users?role=dosen') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Tetap</a>
                    <a href="<?= url('/users?role=dosen') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Luar Biasa</a>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Mahasiswa -->
        <div onclick="window.location.href='<?= url('/users?role=mahasiswa') ?>'" style="cursor: pointer; text-decoration: none; color: inherit; background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
            <div style="height: 110px; background: linear-gradient(135deg, #bbf7d0, #86efac); padding: 12px; position: relative;">
                <span style="background: rgba(30, 41, 59, 0.75); color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; backdrop-filter: blur(4px);">
                    <?= $totalMahasiswa ?? 0 ?> Mahasiswa
                </span>
                <!-- Decorative pattern -->
                <svg width="100" height="100" style="position: absolute; right: -10px; bottom: -20px; opacity: 0.2; color: #15803d;" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div style="padding: 16px;">
                <div style="display: flex; align-items: center; gap: 6px; color: #16a34a; font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <span>Peserta Didik</span>
                </div>
                <h4 style="font-size: 17px; font-weight: 700; color: #1e293b; margin: 0 0 16px 0; line-height: 1.3;">
                    Mahasiswa Aktif
                </h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <a href="<?= url('/users?role=mahasiswa') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">S1</a>
                    <a href="<?= url('/users?role=mahasiswa') ?>" onclick="event.stopPropagation()" style="text-decoration: none; background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Terdaftar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Recent Users -->
        <div class="card">
            <div class="card-header">
                <h3>👤 User Terbaru</h3>
                <a href="<?= url('/users') ?>" class="btn btn-sm btn-secondary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <?php if (!empty($recentUsers)): ?>
                    <div class="table-wrapper">
                        <table class="table">
                            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Terdaftar</th></tr></thead>
                            <tbody>
                                <?php foreach ($recentUsers as $u): ?>
                                <tr>
                                    <td><strong><?= e($u['name']) ?></strong></td>
                                    <td><?= e($u['email']) ?></td>
                                    <td><?= status_badge($u['role'] === 'admin' ? 'active' : ($u['role'] === 'dosen' ? 'published' : 'submitted')) ?></td>
                                    <td class="text-muted text-sm"><?= time_ago($u['created_at']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Belum ada data user.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="card">
            <div class="card-header">
                <h3>📢 Pengumuman Terbaru</h3>
                <a href="<?= url('/announcements') ?>" class="btn btn-sm btn-secondary">Kelola</a>
            </div>
            <div class="card-body">
                <?php if (!empty($recentAnnouncements)): ?>
                    <?php foreach ($recentAnnouncements as $ann): ?>
                        <div class="activity-item">
                            <div class="activity-icon icon-announcement">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/></svg>
                            </div>
                            <div class="activity-content">
                                <p><strong><?= e($ann['title']) ?></strong></p>
                                <p class="text-sm text-muted"><?= str_limit(strip_tags($ann['content']), 80) ?></p>
                                <span class="activity-time"><?= time_ago($ann['created_at']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Belum ada pengumuman.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
