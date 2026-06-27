<?php
namespace App\Middleware;

/**
 * Middleware untuk autentikasi REST API
 * Menggunakan Header: Authorization: Bearer <API_KEY>
 */
class ApiAuthMiddleware implements MiddlewareInterface
{
    public function handle(): void
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        $apiKey = env('API_KEY', 'unsiq-lms-secret-key-2026');

        if (strpos($authHeader, 'Bearer ') !== 0) {
            $this->unauthorized('Token tidak ditemukan atau format salah.');
        }

        $token = substr($authHeader, 7);

        if ($token !== $apiKey) {
            $this->unauthorized('Token tidak valid.');
        }
    }

    private function unauthorized(string $message): void
    {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Unauthorized: ' . $message
        ]);
        exit;
    }
}
