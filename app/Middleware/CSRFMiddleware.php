<?php
namespace App\Middleware;

use App\Core\CSRF;
use App\Core\Session;

/**
 * CSRF Middleware - Validasi CSRF token pada POST requests
 */
class CSRFMiddleware
{
    public function handle(array $params = []): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                Session::flash('error', 'Sesi telah berakhir. Silakan muat ulang halaman.');
                $referer = $_SERVER['HTTP_REFERER'] ?? url('/dashboard');
                header('Location: ' . $referer);
                exit;
            }
        }
    }
}
