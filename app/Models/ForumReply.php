<?php
namespace App\Models;

class ForumReply extends BaseModel
{
    protected string $table = 'forum_replies';
    protected array $fillable = ['thread_id', 'user_id', 'body'];

    /**
     * Get replies by thread with author info
     */
    public function getByThread(int $threadId): array
    {
        $sql = "SELECT r.*, u.name as author_name, u.avatar as author_avatar, u.role as author_role
                FROM {$this->table} r
                JOIN users u ON r.user_id = u.id
                WHERE r.thread_id = ?
                ORDER BY r.created_at ASC";
        return $this->db->query($sql, [$threadId])->fetchAll();
    }

    /**
     * Count replies by thread
     */
    public function countByThread(int $threadId): int
    {
        return $this->count('thread_id = ?', [$threadId]);
    }
}
