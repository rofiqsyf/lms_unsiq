<?php
use App\Core\Session;
use App\Core\CSRF;

$settingModel = new \App\Models\Setting();
$siteName = $settingModel->getValue('site_name', APP_NAME);
$siteLogo = $settingModel->getValue('site_logo');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — <?= e($siteName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/auth.css') ?>">
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-logo">
                        <?php if (!empty($siteLogo)): ?>
                            <img src="<?= e(str_starts_with($siteLogo, 'http') ? $siteLogo : upload_url($siteLogo)) ?>" alt="Logo" style="height: 48px; object-fit: contain;">
                        <?php else: ?>
                            <div class="login-logo-icon"><svg width="28" height="28" viewBox="0 0 32 32" fill="none"><path d="M8 24L16 8L24 24H8Z" fill="white" opacity="0.95"/></svg></div>
                        <?php endif; ?>
                        <span class="login-logo-text"><?= e($siteName) ?></span>
                    </div>
                    <h1>Lupa Password?</h1>
                    <p>Masukkan email Anda untuk menerima instruksi reset password.</p>
                </div>

                <?php if (!empty($flashMessages['info'])): ?>
                    <div class="login-alert login-alert-success" style="background:#eff6ff;color:#1e40af;border-color:#bfdbfe;">
                        <?php foreach ($flashMessages['info'] as $msg): ?>
                            <div><?= $msg ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($flashMessages['success'])): ?>
                    <div class="login-alert login-alert-success">
                        <?php foreach ($flashMessages['success'] as $msg): ?>
                            <?= e($msg) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($flashMessages['error'])): ?>
                    <div class="login-alert login-alert-error">
                        <?php foreach ($flashMessages['error'] as $msg): ?>
                            <?= e($msg) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form class="login-form" method="POST" action="<?= url('/forgot-password') ?>">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" class="form-control" placeholder="nama@lms.unsiq.ac.id" required autofocus>
                            <span class="input-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="login-btn mt-4">Kirim Link Reset</button>
                    
                    <div class="text-center mt-4 text-sm">
                        <a href="<?= url('/login') ?>" class="text-primary font-medium text-decoration-none">Kembali ke halaman login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
