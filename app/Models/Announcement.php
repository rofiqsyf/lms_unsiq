<?php
namespace App\Models;

class Announcement extends BaseModel
{
    protected string $table = 'announcements';
    protected array $fillable = ['course_id', 'user_id', 'title', 'content', 'is_pinned'];

    public function getRecent(int $limit = 5): array
    {
        $sql = "SELECT a.*, u.name as author_name, c.name as course_name
                FROM announcements a
                JOIN users u ON a.user_id = u.id
                LEFT JOIN courses c ON a.course_id = c.id
                ORDER BY a.is_pinned DESC, a.created_at DESC LIMIT ?";
        return $this->db->query($sql, [$limit])->fetchAll();
    }

    public function getByCourse(int $courseId): array
    {
        $sql = "SELECT a.*, u.name as author_name
                FROM announcements a
                JOIN users u ON a.user_id = u.id
                WHERE a.course_id = ? OR a.course_id IS NULL
                ORDER BY a.is_pinned DESC, a.created_at DESC";
        return $this->db->query($sql, [$courseId])->fetchAll();
    }

    public function getForStudent(int $userId, int $limit = 5): array
    {
        $sql = "SELECT a.*, u.name as author_name, c.name as course_name
                FROM announcements a
                JOIN users u ON a.user_id = u.id
                LEFT JOIN courses c ON a.course_id = c.id
                WHERE a.course_id IS NULL
                OR a.course_id IN (SELECT course_id FROM enrollments WHERE user_id = ? AND status = 'active')
                ORDER BY a.is_pinned DESC, a.created_at DESC LIMIT ?";
        return $this->db->query($sql, [$userId, $limit])->fetchAll();
    }

    public function paginateAnnouncements(int $page = 1, int $perPage = 10, string $baseUrl = ''): array
    {
        $total = $this->count();
        $pagination = new \App\Core\Pagination($total, $perPage, $page, $baseUrl);

        $sql = "SELECT a.*, u.name as author_name, c.name as course_name
                FROM announcements a
                JOIN users u ON a.user_id = u.id
                LEFT JOIN courses c ON a.course_id = c.id
                ORDER BY a.is_pinned DESC, a.created_at DESC
                LIMIT {$pagination->getLimit()} OFFSET {$pagination->getOffset()}";
        $data = $this->db->query($sql)->fetchAll();

        return ['data' => $data, 'pagination' => $pagination];
    }
}
