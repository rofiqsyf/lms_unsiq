<?php
namespace App\Controllers;

use App\Models\Course;
use App\Models\User;

class ApiController extends BaseController
{
    /** GET /api/v1/courses */
    public function courses(): void
    {
        $courseModel = new Course();
        // Ambil data aktif
        $sql = "SELECT id, code, name, sks, status FROM courses WHERE status = 'published'";
        $courses = $courseModel->db->query($sql)->fetchAll();

        $this->jsonResponse(true, 'Data mata kuliah', $courses);
    }

    /** GET /api/v1/users */
    public function users(): void
    {
        $userModel = new User();
        $sql = "SELECT id, name, email, nim_nidn, role, is_active FROM users";
        $users = $userModel->db->query($sql)->fetchAll();

        $this->jsonResponse(true, 'Data pengguna', $users);
    }

    private function jsonResponse(bool $success, string $message, array $data = []): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }
}
