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
    <title>Registrasi — <?= e($siteName) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/auth.css?v=' . time()) ?>">
    <style>
        .login-card { max-width: 500px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    </style>
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
                    <h1>Buat Akun Baru</h1>
                    <p>Daftar sebagai mahasiswa untuk mulai belajar</p>
                </div>

                <?php if (!empty($flashMessages['error'])): ?>
                    <?php foreach ($flashMessages['error'] as $msg): ?>
                        <div class="login-alert login-alert-error"><?= e($msg) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <form class="login-form" method="POST" action="<?= url('/register') ?>">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" class="form-control" value="<?= e(old('name')) ?>" required autofocus>
                        </div>
                        <?php if ($err = Session::getError('name')): ?><div class="text-danger text-sm mt-1"><?= e($err) ?></div><?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="nim_nidn">NIM <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="text" id="nim_nidn" name="nim_nidn" class="form-control" value="<?= e(old('nim_nidn')) ?>" required>
                            </div>
                            <?php if ($err = Session::getError('nim_nidn')): ?><div class="text-danger text-sm mt-1"><?= e($err) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email" class="form-control" value="<?= e(old('email')) ?>" required>
                            </div>
                            <?php if ($err = Session::getError('email')): ?><div class="text-danger text-sm mt-1"><?= e($err) ?></div><?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="password">Password <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="password" id="password" name="password" class="form-control" required minlength="6">
                            </div>
                            <?php if ($err = Session::getError('password')): ?><div class="text-danger text-sm mt-1"><?= e($err) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                            <div class="input-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required minlength="6">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="login-btn mt-4">Daftar Akun</button>
                    
                    <div class="text-center mt-4 text-sm">
                        Sudah punya akun? <a href="<?= url('/login') ?>" class="text-primary font-medium text-decoration-none">Masuk di sini</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html>
