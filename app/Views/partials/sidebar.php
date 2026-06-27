<?php
$user = $currentUser ?? \App\Core\Session::user();
$role = $user['role'] ?? 'mahasiswa';
$notifCount = 0;
$msgCount = 0;
try {
    $notifModel = new \App\Models\Notification();
    $notifCount = $notifModel->countUnread($user['id'] ?? 0);
    $msgCount = (new \App\Models\Message())->countUnread($user['id'] ?? 0);
} catch (\Throwable $e) {}

$settingModel = new \App\Models\Setting();
$siteLogo = $settingModel->getValue('site_logo');

$activeCourses = [];
if ($role === 'mahasiswa') {
    try {
        $activeCourses = (new \App\Models\Enrollment())->getEnrolledCourses($user['id'] ?? 0);
    } catch (\Throwable $e) {}
}
?>
<aside class="dual-sidebar">
    <!-- Primary Rail -->
    <div class="sidebar-rail">
        <div class="rail-top">
            <a href="<?= url('/dashboard') ?>" class="rail-logo">
                <?php if (!empty($siteLogo)): ?>
                    <img src="<?= e(str_starts_with($siteLogo, 'http') ? $siteLogo : upload_url($siteLogo)) ?>" alt="Logo">
                <?php else: ?>
                    <div class="logo-fallback">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                <?php endif; ?>
            </a>
        </div>
        
        <div class="rail-menu">
            <a href="<?= url('/dashboard') ?>" class="rail-item <?= is_active('/dashboard') ? 'active' : '' ?>" title="Beranda">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            </a>
            <a href="<?= url('/courses') ?>" class="rail-item <?= is_active('/courses') ? 'active' : '' ?>" title="Katalog">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </a>
            <?php if ($role === 'mahasiswa'): ?>
            <a href="<?= url('/my-courses') ?>" class="rail-item <?= is_active('/my-courses') ? 'active' : '' ?>" title="Belajar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            </a>
            <?php endif; ?>
            <?php if ($role === 'admin'): ?>
            <a href="<?= url('/users') ?>" class="rail-item <?= is_active('/users') ? 'active' : '' ?>" title="Pengguna">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </a>
            <?php endif; ?>
            <a href="<?= url('/grades') ?>" class="rail-item <?= is_active('/grades') ? 'active' : '' ?>" title="Nilai">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
            </a>
        </div>

        <div class="rail-bottom">
            <a href="<?= url('/messages') ?>" class="rail-item <?= is_active('/messages') ? 'active' : '' ?>" title="Pesan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                <?php if($msgCount > 0): ?><span class="indicator"></span><?php endif; ?>
            </a>
            <?php if ($role === 'admin'): ?>
            <a href="<?= url('/settings') ?>" class="rail-item <?= is_active('/settings') ? 'active' : '' ?>" title="Pengaturan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </a>
            <?php endif; ?>
            <a href="<?= url('/profile') ?>" class="rail-avatar">
                <img src="<?= !empty($user['avatar']) ? upload_url($user['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($user['name'] ?? 'U').'&background=6366f1&color=fff' ?>" alt="Avatar">
            </a>
        </div>
    </div>
    
    <!-- Secondary Submenu -->
    <div class="sidebar-submenu">
        <div class="submenu-header">
            <h2>LMS UNSIQ</h2>
        </div>
        <div class="submenu-scrollable">
            
            <div class="submenu-section">
                <h3 class="submenu-title">Menu Utama</h3>
                <a href="<?= url('/dashboard') ?>" class="submenu-link <?= is_active('/dashboard') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Ringkasan
                </a>
                <a href="<?= url('/courses') ?>" class="submenu-link <?= is_active('/courses') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    Katalog Kelas
                </a>
                <a href="<?= url('/grades') ?>" class="submenu-link <?= is_active('/grades') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Transkrip Nilai
                </a>
            </div>

            <?php if ($role === 'mahasiswa' && !empty($activeCourses)): ?>
            <div class="submenu-section">
                <h3 class="submenu-title">Kelas Aktif <span class="badge"><?= count($activeCourses) ?></span></h3>
                <?php foreach(array_slice($activeCourses, 0, 4) as $ac): ?>
                    <a href="<?= url('/courses/' . $ac['course_id']) ?>" class="submenu-link">
                        <div class="color-dot" style="background: <?= sprintf('#%06X', mt_rand(0, 0xFFFFFF)) ?>;"></div>
                        <span class="truncate"><?= e($ac['course_name']) ?></span>
                    </a>
                <?php endforeach; ?>
                <?php if (count($activeCourses) > 4): ?>
                    <a href="<?= url('/my-courses') ?>" class="submenu-link view-all">Lihat semua...</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
            <div class="submenu-section">
                <h3 class="submenu-title">Administrasi</h3>
                <a href="<?= url('/users') ?>" class="submenu-link <?= is_active('/users') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Manajemen Pengguna
                </a>
                <a href="<?= url('/categories') ?>" class="submenu-link <?= is_active('/categories') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    Kategori Kelas
                </a>
                <a href="<?= url('/admin/logs') ?>" class="submenu-link <?= is_active('/admin/logs') ? 'active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                    Log Sistem
                </a>
            </div>
            <?php endif; ?>
            
            <div class="submenu-section" style="margin-top: auto; padding-top: 20px;">
                <a href="<?= url('/logout') ?>" class="submenu-link text-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Keluar Aplikasi
                </a>
            </div>
        </div>
    </div>
</aside>
