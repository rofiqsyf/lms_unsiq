<?php
namespace App\Models;

class ActivityLog extends BaseModel
{
    protected string $table = 'activity_logs';
    protected array $fillable = ['user_id', 'action', 'entity_type', 'entity_id', 'details', 'ip_address'];

    /**
     * Mendapatkan log terbaru dengan informasi user
     */
    public function getRecentLogs(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT l.*, u.name as user_name, u.role as user_role 
                FROM {$this->table} l
                LEFT JOIN users u ON l.user_id = u.id
                ORDER BY l.created_at DESC
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function countLogs(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int) $this->db->query($sql)->fetchColumn();
    }
}
