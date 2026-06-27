<?php
$user = $currentUser ?? \App\Core\Session::user();
$notifCount = 0;
try {
    $notifModel = new \App\Models\Notification();
    $notifCount = $notifModel->countUnread($user['id'] ?? 0);
} catch (\Throwable $e) {}
?>
<div style="display: flex; justify-content: center; position: sticky; top: 24px; z-index: 999; margin-bottom: 24px; pointer-events: none; width: 100%;">
    
<?php
    $settingModel = new \App\Models\Setting();
    $siteName = $settingModel->getValue('site_name', 'LMS UNSIQ');
    $siteDesc = $settingModel->getValue('site_description', 'Portal Akademik');
    $siteLogo = $settingModel->getValue('site_logo');
?>
    <!-- BRANDING LOGO (Top Left) -->
    <a href="<?= url('/dashboard') ?>" style="position: absolute; left: 32px; top: 50%; transform: translateY(-50%); pointer-events: auto; display: flex; align-items: center; gap: 12px; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-50%) scale(1.02)'" onmouseout="this.style.transform='translateY(-50%) scale(1)'">
        <?php if (!empty($siteLogo)): ?>
            <img src="<?= e(str_starts_with($siteLogo, 'http') ? $siteLogo : upload_url($siteLogo)) ?>" alt="Logo" style="width: 40px; height: 40px; border-radius: 8px; object-fit: contain;">
        <?php else: ?>
            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #4f46e5, #4338ca); border-radius: 12px; display: flex; justify-content: center; align-items: center; box-shadow: 0 8px 16px rgba(67,56,202,0.25);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
            </div>
        <?php endif; ?>
        <div>
            <h1 style="font-size: 18px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; margin: 0; line-height: 1.1;"><?= e($siteName) ?></h1>
            <span style="font-size: 11px; font-weight: 700; color: var(--accent-primary); letter-spacing: 0.05em; text-transform: uppercase;"><?= e($siteDesc) ?></span>
        </div>
    </a>

    <!-- Floating Omnipresent Search & Notifications (Dynamic Island Style) -->
    <div style="pointer-events: auto; display: flex; justify-content: space-between; align-items: center; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(16px); padding: 8px 12px 8px 24px; border-radius: 999px; box-shadow: 0 10px 30px rgba(0,0,0,0.15), 0 1px 3px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.1); width: 100%; max-width: 700px; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <form action="<?= url('/search') ?>" method="GET" style="display: flex; align-items: center; gap: 12px; flex: 1; margin:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color: rgba(255,255,255,0.5);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" placeholder="Cari mata kuliah, nama mahasiswa..." style="border: none; background: transparent; width: 100%; font-size: 15px; font-weight: 500; outline: none; color: white; font-family: inherit;" autocomplete="off">
        </form>
        <div style="display: flex; align-items: center; gap: 12px;">
            <?php
                $msgCount = 0;
                try {
                    $msgCount = (new \App\Models\Message())->countUnread($user['id'] ?? 0);
                } catch (\Throwable $e) {}
            ?>
            <a href="<?= url('/messages') ?>" style="position:relative; text-decoration: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: rgba(255,255,255,0.7); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                <?php if ($msgCount > 0): ?>
                    <div style="position:absolute;top:2px;right:2px;width:10px;height:10px;background:var(--accent-success);border-radius:50%;border:2px solid rgba(15, 23, 42, 0.85);"></div>
                <?php endif; ?>
            </a>

            <?php if ($notifCount > 0): ?>
            <a href="<?= url('/notifications') ?>" style="text-decoration: none; background: var(--bg-primary); padding: 6px 12px; border-radius: 99px; font-size: 13px; font-weight: 700; color: var(--accent-primary); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div style="width: 6px; height: 6px; background: var(--accent-success); border-radius: 50%;"></div>
                <?= $notifCount ?> Info
            </a>
            <?php else: ?>
            <a href="<?= url('/notifications') ?>" style="text-decoration: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: rgba(255,255,255,0.7); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            </a>
            <?php endif; ?>

            <div style="width: 1px; height: 24px; background: var(--border-color);"></div>

            <a href="<?= url('/profile') ?>">
                <img src="<?= !empty($user['avatar']) ? upload_url($user['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($user['name'] ?? 'U').'&background=6366f1&color=fff' ?>" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: 2px solid white; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            </a>
        </div>
    </div>
</div>
