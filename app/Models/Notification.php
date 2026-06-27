<?php
namespace App\Models;

class Notification extends BaseModel
{
    protected string $table = 'notifications';
    protected array $fillable = ['user_id', 'title', 'message', 'link', 'type', 'is_read'];

    public function getByUser(int $userId, int $limit = 20): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        return $this->db->query($sql, [$userId, $limit])->fetchAll();
    }

    public function countUnread(int $userId): int
    {
        return $this->count('user_id = ? AND is_read = 0', [$userId]);
    }

    public function markAsRead(int $id): bool
    {
        return $this->update($id, ['is_read' => 1]);
    }

    public function markAllAsRead(int $userId): void
    {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = ? AND is_read = 0";
        $this->db->query($sql, [$userId]);
    }

    public static function send(int $userId, string $title, string $message, string $link = '', string $type = 'system'): void
    {
        $model = new self();
        $model->create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'type'    => $type,
        ]);
    }
}
