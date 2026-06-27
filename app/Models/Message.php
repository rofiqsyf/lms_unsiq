<?php
namespace App\Models;

class Message extends BaseModel
{
    protected string $table = 'messages';
    protected array $fillable = ['sender_id', 'receiver_id', 'body', 'is_read'];

    /**
     * Get list of conversations for a user
     */
    public function getConversations(int $userId): array
    {
        $sql = "SELECT u.id, u.name, u.avatar, u.role,
                       m.body as last_message, m.created_at as last_time, m.is_read,
                       (m.sender_id = ?) as is_mine
                FROM users u
                JOIN (
                    SELECT MAX(id) as max_id
                    FROM {$this->table}
                    WHERE sender_id = ? OR receiver_id = ?
                    GROUP BY IF(sender_id = ?, receiver_id, sender_id)
                ) as latest ON 1=1
                JOIN {$this->table} m ON m.id = latest.max_id
                WHERE (m.sender_id = u.id OR m.receiver_id = u.id) AND u.id != ?
                ORDER BY m.created_at DESC";
                
        return $this->db->query($sql, [$userId, $userId, $userId, $userId, $userId])->fetchAll();
    }

    /**
     * Get chat history with a specific user
     */
    public function getHistoryWithUser(int $currentUserId, int $otherUserId): array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE (sender_id = ? AND receiver_id = ?)
                   OR (sender_id = ? AND receiver_id = ?)
                ORDER BY created_at ASC";
        return $this->db->query($sql, [$currentUserId, $otherUserId, $otherUserId, $currentUserId])->fetchAll();
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(int $receiverId, int $senderId): void
    {
        $sql = "UPDATE {$this->table} SET is_read = 1
                WHERE receiver_id = ? AND sender_id = ? AND is_read = 0";
        $this->db->query($sql, [$receiverId, $senderId]);
    }

    /**
     * Count unread messages for a user
     */
    public function countUnread(int $userId): int
    {
        return $this->count('receiver_id = ? AND is_read = 0', [$userId]);
    }
}
