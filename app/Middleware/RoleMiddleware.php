<?php
namespace App\Middleware;

use App\Core\Session;

/**
 * Role Middleware - Pastikan role user sesuai
 * Usage di route: 'role:admin' atau 'role:admin,dosen'
 */
class RoleMiddleware
{
    public function handle(array $allowedRoles = []): void
    {
        $userRole = Session::userRole();

        if (empty($allowedRoles) || !in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            if (file_exists(VIEWS_PATH . '/errors/403.php')) {
                require VIEWS_PATH . '/errors/403.php';
            } else {
                echo '<h1>403 - Akses Ditolak</h1>';
                echo '<p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>';
            }
            exit;
        }
    }
}
