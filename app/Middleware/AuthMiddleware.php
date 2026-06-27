<?php
namespace App\Middleware;

use App\Core\Session;

/**
 * Auth Middleware - Pastikan user sudah login
 * Sesuai materi §2.8 Middleware Autentikasi
 */
class AuthMiddleware
{
    public function handle(array $params = []): void
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Silakan login terlebih dahulu.');
            header('Location: ' . url('/login'));
            exit;
        }

        // Check if user is still active
        $user = Session::user();
        if (isset($user['is_active']) && !$user['is_active']) {
            Session::destroy();
            session_start();
            Session::flash('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
            header('Location: ' . url('/login'));
            exit;
        }
    }
}
