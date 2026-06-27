<?php
namespace App\Middleware;

use App\Core\Session;

/**
 * Guest Middleware - Redirect ke dashboard jika sudah login
 * Digunakan untuk halaman login/register
 */
class GuestMiddleware
{
    public function handle(array $params = []): void
    {
        if (Session::isLoggedIn()) {
            header('Location: ' . url('/dashboard'));
            exit;
        }
    }
}
