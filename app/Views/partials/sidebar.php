<?php
/**
 * Focus & Flow: Floating Bottom Dock Navigation
 */
$user = $currentUser ?? \App\Core\Session::user();
$role = $user['role'] ?? 'mahasiswa';
$notifCount = 0;
try {
    $notifModel = new \App\Models\Notification();
    $notifCount = $notifModel->countUnread($user['id'] ?? 0);
} catch (\Throwable $e) {}
?>
<nav class="floating-dock">
    <div class="dock-container">
        <!-- Dashboard -->
        <a href="<?= url('/dashboard') ?>" class="dock-item <?= is_active('/dashboard') ? 'active' : '' ?>" title="Beranda">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            <span>Beranda</span>
        </a>

        <!-- Catalog -->
        <a href="<?= url('/courses') ?>" class="dock-item <?= is_active('/courses') ? 'active' : '' ?>" title="Katalog">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <span>Katalog</span>
        </a>

        <?php if ($role === 'mahasiswa'): ?>
        <!-- My Learning -->
        <a href="<?= url('/my-courses') ?>" class="dock-item <?= is_active('/my-courses') ? 'active' : '' ?>" title="Belajar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            <span>Belajar</span>
        </a>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
        <!-- Users -->
        <a href="<?= url('/users') ?>" class="dock-item <?= is_active('/users') ? 'active' : '' ?>" title="Pengguna">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Pengguna</span>
        </a>
        
        <!-- Categories -->
        <a href="<?= url('/categories') ?>" class="dock-item <?= is_active('/categories') ? 'active' : '' ?>" title="Kategori">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
            <span>Kategori</span>
        </a>

        <!-- Admin Logs -->
        <a href="<?= url('/admin/logs') ?>" class="dock-item <?= is_active('/admin/logs') ? 'active' : '' ?>" title="Audit Trail">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
            <span>Log Sistem</span>
        </a>

        <!-- Settings -->
        <a href="<?= url('/settings') ?>" class="dock-item <?= is_active('/settings') ? 'active' : '' ?>" title="Pengaturan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            <span>Pengaturan</span>
        </a>
        <?php endif; ?>

        <!-- Grades -->
        <a href="<?= url('/grades') ?>" class="dock-item <?= is_active('/grades') ? 'active' : '' ?>" title="Nilai">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <span>Nilai</span>
        </a>

        <div class="dock-divider"></div>

        <!-- Profile -->
        <a href="<?= url('/profile') ?>" class="dock-item <?= is_active('/profile') ? 'active' : '' ?>" title="Profil">
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= upload_url($user['avatar']) ?>" alt="Avatar" style="width:24px;height:24px;border-radius:50%;">
            <?php else: ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
            <?php endif; ?>
            <span>Profil</span>
        </a>

        <!-- Logout -->
        <a href="<?= url('/logout') ?>" class="dock-item" title="Keluar" style="color: #ef4444;" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='#ef4444'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Keluar</span>
        </a>
    </div>
</nav>

