<?php
namespace App\Models;

/**
 * Course Model
 */
class Course extends BaseModel
{
    protected string $table = 'courses';
    protected array $fillable = [
        'dosen_id', 'category_id', 'code', 'name', 'description',
        'thumbnail', 'sks', 'semester', 'academic_year', 'status', 'max_students'
    ];

    /**
     * Get courses by dosen
     */
    public function getByDosen(int $dosenId): array
    {
        $sql = "SELECT c.*, cat.name as category_name,
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id AND e.status = 'active') as student_count
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.dosen_id = ?
                ORDER BY c.created_at DESC";
        return $this->db->query($sql, [$dosenId])->fetchAll();
    }

    /**
     * Get course with dosen info
     */
    public function findWithDosen(int $id): ?array
    {
        $sql = "SELECT c.*, u.name as dosen_name, u.email as dosen_email, u.avatar as dosen_avatar,
                cat.name as category_name,
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id AND e.status = 'active') as student_count
                FROM courses c
                LEFT JOIN users u ON c.dosen_id = u.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.id = ?";
        $result = $this->db->query($sql, [$id])->fetch();
        return $result ?: null;
    }

    /**
     * Get published courses with pagination
     */
    public function paginateCourses(int $page = 1, int $perPage = 10, string $search = '', string $status = '', int $dosenId = 0, string $baseUrl = ''): array
    {
        $where = '1=1';
        $params = [];

        if ($search) {
            $where .= ' AND (c.name LIKE ? OR c.code LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($status) {
            $where .= ' AND c.status = ?';
            $params[] = $status;
        }
        if ($dosenId) {
            $where .= ' AND c.dosen_id = ?';
            $params[] = $dosenId;
        }

        // Count total
        $countSql = "SELECT COUNT(*) FROM courses c WHERE {$where}";
        $total = (int) $this->db->query($countSql, $params)->fetchColumn();

        $pagination = new \App\Core\Pagination($total, $perPage, $page, $baseUrl);

        $sql = "SELECT c.*, u.name as dosen_name, cat.name as category_name,
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id AND e.status = 'active') as student_count
                FROM courses c
                LEFT JOIN users u ON c.dosen_id = u.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE {$where}
                ORDER BY c.created_at DESC
                LIMIT {$pagination->getLimit()} OFFSET {$pagination->getOffset()}";

        $data = $this->db->query($sql, $params)->fetchAll();

        return ['data' => $data, 'pagination' => $pagination];
    }

    /**
     * Get total students taught by a dosen
     */
    public function getTotalStudentsByDosen(int $dosenId): int
    {
        $sql = "SELECT COUNT(DISTINCT e.user_id)
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE c.dosen_id = ? AND e.status = 'active'";
        return (int) $this->db->query($sql, [$dosenId])->fetchColumn();
    }

    /**
     * Get published courses a student can enroll in
     */
    public function getAvailableForStudent(int $userId): array
    {
        $sql = "SELECT c.*, u.name as dosen_name, cat.name as category_name,
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id AND e.status = 'active') as student_count
                FROM courses c
                LEFT JOIN users u ON c.dosen_id = u.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.status = 'published'
                AND c.id NOT IN (SELECT course_id FROM enrollments WHERE user_id = ?)
                ORDER BY c.name";
        return $this->db->query($sql, [$userId])->fetchAll();
    }
}
