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
    <title>Reset Password — <?= e($siteName) ?></title>
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
                    <h1>Reset Password</h1>
                    <p>Masukkan password baru untuk akun <strong><?= e($email) ?></strong></p>
                </div>

                <?php if (!empty($flashMessages['error'])): ?>
                    <div class="login-alert login-alert-error">
                        <?php foreach ($flashMessages['error'] as $msg): ?>
                            <?= e($msg) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form class="login-form" method="POST" action="<?= url('/reset-password/' . $token) ?>">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="password">Password Baru <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-control" required minlength="6" autofocus>
                        </div>
                        <?php if ($err = Session::getError('password')): ?><div class="text-danger text-sm mt-1"><?= e($err) ?></div><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password Baru <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required minlength="6">
                        </div>
                    </div>

                    <button type="submit" class="login-btn mt-4">Simpan Password Baru</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
