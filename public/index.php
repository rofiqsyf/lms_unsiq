<?php
/**
 * ===========================================
 * LMS UNSIQ - Front Controller
 * ===========================================
 * Entry point SEMUA HTTP Request.
 * Sesuai materi §2.3 Front Controller Pattern.
 */

// 1. Definisikan konstanta path
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// 2. Error reporting (berdasarkan environment)
error_reporting(E_ALL);
ini_set('display_errors', '1'); // Akan di-override oleh config

// 3. Load konfigurasi aplikasi (ini juga load .env)
require_once BASE_PATH . '/config/app.php';

// Override error display berdasarkan APP_DEBUG
ini_set('display_errors', APP_DEBUG ? '1' : '0');

// 4. Load autoloader (PSR-4)
require_once APP_PATH . '/Core/Autoloader.php';
\App\Core\Autoloader::register();

// 5. Load helper functions
require_once APP_PATH . '/Helpers/functions.php';

// 6. Konfigurasi session aman (dari materi Pertemuan 9)
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', '1');
ini_set('session.gc_maxlifetime', SESSION_LIFETIME * 60);
session_set_cookie_params([
    'lifetime' => SESSION_LIFETIME * 60,
    'path'     => '/',
    'secure'   => env('SESSION_SECURE', false),
    'httponly'  => true,
    'samesite'  => 'Strict',
]);
session_start();

// 7. Instansiasi router & load route definitions
$router = new \App\Core\Router();
require_once BASE_PATH . '/routes/web.php';

// 8. Ambil request URI dan method
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri    = $_SERVER['REQUEST_URI'];

// Parse URI: hapus base path dan query string
$appUrlPath = parse_url(env('APP_URL', 'http://localhost/project_lms'), PHP_URL_PATH) ?? '';
$uri = parse_url($requestUri, PHP_URL_PATH);

// Remove base path prefix dari env
if ($appUrlPath !== '' && str_starts_with($uri, $appUrlPath)) {
    $uri = substr($uri, strlen($appUrlPath));
}

// Hapus /public jika ada (terjadi jika diakses manual)
if (str_starts_with($uri, '/public')) {
    $uri = substr($uri, 7);
}

// Pastikan URI dimulai dengan /
if (empty($uri) || $uri === false) {
    $uri = '/';
}
if ($uri !== '/' && !str_starts_with($uri, '/')) {
    $uri = '/' . $uri;
}

// Remove trailing slash kecuali root
if ($uri !== '/' && str_ends_with($uri, '/')) {
    $uri = rtrim($uri, '/');
}

// 9. Dispatch request
try {
    $router->dispatch($requestMethod, $uri);
} catch (\App\Core\Exceptions\NotFoundException $e) {
    // 404
    http_response_code(404);
    if (file_exists(VIEWS_PATH . '/errors/404.php')) {
        require VIEWS_PATH . '/errors/404.php';
    } else {
        echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
    }
} catch (\App\Core\Exceptions\ForbiddenException $e) {
    // 403
    http_response_code(403);
    if (file_exists(VIEWS_PATH . '/errors/403.php')) {
        require VIEWS_PATH . '/errors/403.php';
    } else {
        echo '<h1>403 - Akses Ditolak</h1>';
    }
} catch (\Throwable $e) {
    // 500 - Error handler global
    http_response_code(500);
    if (APP_DEBUG) {
        echo '<div style="font-family:monospace;padding:20px;background:#1a1d2e;color:#f1f5f9;">';
        echo '<h1 style="color:#ef4444;">⚠ Error 500</h1>';
        echo '<p style="color:#f59e0b;font-size:18px;">' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p style="color:#94a3b8;">File: ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '<pre style="background:#0f1117;padding:15px;border-radius:8px;overflow-x:auto;color:#94a3b8;">';
        echo htmlspecialchars($e->getTraceAsString());
        echo '</pre></div>';
    } else {
        if (file_exists(VIEWS_PATH . '/errors/500.php')) {
            require VIEWS_PATH . '/errors/500.php';
        } else {
            echo '<h1>500 - Internal Server Error</h1>';
        }
    }
}
