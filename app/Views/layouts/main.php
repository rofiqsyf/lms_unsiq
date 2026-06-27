<?php
    $settingModel = new \App\Models\Setting();
    $siteName = $settingModel->getValue('site_name', APP_NAME);
    $siteDesc = $settingModel->getValue('site_description', 'Sistem Manajemen Pembelajaran');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($siteDesc) ?>">
    <title><?= e($pageTitle ?? 'Dashboard') ?> — <?= e($siteName) ?></title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.min.css">
    
    <!-- App Styles -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">

    <!-- PWA Settings -->
    <link rel="manifest" href="<?= url('/manifest.json') ?>">
    <meta name="theme-color" content="#4f46e5">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= url('/sw.js') ?>')
                    .then(reg => console.log('SW Registered'))
                    .catch(err => console.log('SW Registration Failed', err));
            });
        }
    </script>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <?php require VIEWS_PATH . '/partials/sidebar.php'; ?>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Navbar -->
            <?php require VIEWS_PATH . '/partials/navbar.php'; ?>
            
            <!-- Flash Messages -->
            <?php require VIEWS_PATH . '/partials/alerts.php'; ?>
            
            <!-- Breadcrumbs -->
            <?php if (!empty($GLOBALS['breadcrumbs'])): ?>
                <?php require VIEWS_PATH . '/partials/breadcrumb.php'; ?>
            <?php endif; ?>
            
            <!-- Page Content -->
            <main class="page-content">
                <?= $content ?>
            </main>
            
            <!-- Footer -->
            <?php require VIEWS_PATH . '/partials/footer.php'; ?>
        </div>
    </div>
    
    <!-- Confirm Modal -->
    <?php require VIEWS_PATH . '/partials/modal-confirm.php'; ?>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- GSAP for Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <!-- App JavaScript -->
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/animations.js') ?>"></script>
</body>
</html>
