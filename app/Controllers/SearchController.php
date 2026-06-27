<?php
namespace App\Controllers;

use App\Models\Course;
use App\Models\User;

class SearchController extends BaseController
{
    /** GET /search */
    public function index(): void
    {
        $keyword = $this->query('q', '');
        
        $this->setTitle('Pencarian');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Pencarian']]);

        $results = [
            'courses' => [],
            'users'   => []
        ];

        if (trim($keyword) !== '') {
            $courseModel = new Course();
            $userModel = new User();

            // Search courses by name, code or description
            $sqlCourses = "SELECT c.*, u.name as dosen_name FROM courses c 
                           JOIN users u ON c.dosen_id = u.id 
                           WHERE (c.name LIKE ? OR c.code LIKE ? OR c.description LIKE ?) AND c.status = 'published'";
            $results['courses'] = $courseModel->db->query($sqlCourses, ["%$keyword%", "%$keyword%", "%$keyword%"])->fetchAll();

            // Search users by name or nim_nidn
            $sqlUsers = "SELECT id, name, nim_nidn, role, avatar FROM users 
                         WHERE (name LIKE ? OR nim_nidn LIKE ?) AND is_active = 1";
            $results['users'] = $userModel->db->query($sqlUsers, ["%$keyword%", "%$keyword%"])->fetchAll();
        }

        $this->render('search/index', [
            'pageTitle' => 'Hasil Pencarian',
            'keyword'   => $keyword,
            'results'   => $results
        ]);
    }

    /** GET /search/ajax */
    public function ajax(): void
    {
        $keyword = $this->query('q', '');
        
        $results = [
            'courses' => [],
            'users'   => []
        ];

        if (trim($keyword) !== '') {
            $courseModel = new Course();
            $userModel = new User();

            $sqlCourses = "SELECT c.id, c.name, c.code, u.name as dosen_name FROM courses c 
                           JOIN users u ON c.dosen_id = u.id 
                           WHERE (c.name LIKE ? OR c.code LIKE ? OR c.description LIKE ?) AND c.status = 'published' LIMIT 5";
            $results['courses'] = $courseModel->db->query($sqlCourses, ["%$keyword%", "%$keyword%", "%$keyword%"])->fetchAll();

            $sqlUsers = "SELECT id, name, nim_nidn, role, avatar FROM users 
                         WHERE (name LIKE ? OR nim_nidn LIKE ?) AND is_active = 1 LIMIT 5";
            $results['users'] = $userModel->db->query($sqlUsers, ["%$keyword%", "%$keyword%"])->fetchAll();
            
            // Format avatars to use upload_url if exists
            foreach ($results['users'] as &$u) {
                if ($u['avatar']) {
                    $u['avatar'] = upload_url($u['avatar']);
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
}
