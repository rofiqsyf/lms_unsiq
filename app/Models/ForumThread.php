<?php
namespace App\Models;

class ForumThread extends BaseModel
{
    protected string $table = 'forum_threads';
    protected array $fillable = [
        'course_id', 'user_id', 'title', 'body',
        'is_pinned', 'is_locked', 'reply_count', 'last_reply_at'
    ];

    /**
     * Get threads by course (paginated)
     */
    public function getByCourse(int $courseId, int $page = 1, int $perPage = 15, string $baseUrl = ''): array
    {
        $countSql = "SELECT COUNT(*) FROM {$this->table} WHERE course_id = ?";
        $total = (int) $this->db->query($countSql, [$courseId])->fetchColumn();

        $pagination = new \App\Core\Pagination($total, $perPage, $page, $baseUrl);

        $sql = "SELECT t.*, u.name as author_name, u.avatar as author_avatar, u.role as author_role
                FROM {$this->table} t
                JOIN users u ON t.user_id = u.id
                WHERE t.course_id = ?
                ORDER BY t.is_pinned DESC, COALESCE(t.last_reply_at, t.created_at) DESC
                LIMIT {$pagination->getLimit()} OFFSET {$pagination->getOffset()}";
        $data = $this->db->query($sql, [$courseId])->fetchAll();

        return ['data' => $data, 'pagination' => $pagination];
    }

    /**
     * Get thread with author info
     */
    public function findWithUser(int $id): ?array
    {
        $sql = "SELECT t.*, u.name as author_name, u.avatar as author_avatar, u.role as author_role,
                       c.name as course_name, c.code as course_code, c.id as course_id
                FROM {$this->table} t
                JOIN users u ON t.user_id = u.id
                JOIN courses c ON t.course_id = c.id
                WHERE t.id = ?";
        $result = $this->db->query($sql, [$id])->fetch();
        return $result ?: null;
    }

    /**
     * Increment reply count and update last_reply_at
     */
    public function incrementReplyCount(int $id): void
    {
        $sql = "UPDATE {$this->table}
                SET reply_count = reply_count + 1, last_reply_at = NOW()
                WHERE id = ?";
        $this->db->query($sql, [$id]);
    }

    /**
     * Decrement reply count
     */
    public function decrementReplyCount(int $id): void
    {
        $sql = "UPDATE {$this->table}
                SET reply_count = GREATEST(reply_count - 1, 0)
                WHERE id = ?";
        $this->db->query($sql, [$id]);
    }

    /**
     * Count threads by course
     */
    public function countByCourse(int $courseId): int
    {
        return $this->count('course_id = ?', [$courseId]);
    }
}
