<?php
namespace App\Models;

/**
 * Material Model
 */
class Material extends BaseModel
{
    protected string $table = 'materials';
    protected array $fillable = [
        'course_id', 'title', 'content', 'file_path', 'file_name',
        'file_type', 'file_size', 'video_url', 'section', 'sort_order',
        'is_published', 'download_count'
    ];

    /**
     * Get materials by course, grouped by section
     */
    public function getByCourse(int $courseId, bool $publishedOnly = false): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE course_id = ?";
        $params = [$courseId];

        if ($publishedOnly) {
            $sql .= " AND is_published = 1";
        }
        $sql .= " ORDER BY sort_order ASC, id ASC";

        $materials = $this->db->query($sql, $params)->fetchAll();

        // Group by section
        $grouped = [];
        foreach ($materials as $m) {
            $section = $m['section'] ?: 'Umum';
            $grouped[$section][] = $m;
        }
        return $grouped;
    }

    /**
     * Get flat list for a course
     */
    public function getListByCourse(int $courseId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE course_id = ? ORDER BY sort_order ASC";
        return $this->db->query($sql, [$courseId])->fetchAll();
    }

    /**
     * Increment download count
     */
    public function incrementDownload(int $id): void
    {
        $sql = "UPDATE {$this->table} SET download_count = download_count + 1 WHERE id = ?";
        $this->db->query($sql, [$id]);
    }

    /**
     * Get next sort order for a course
     */
    public function getNextSortOrder(int $courseId): int
    {
        $sql = "SELECT MAX(sort_order) FROM {$this->table} WHERE course_id = ?";
        $max = $this->db->query($sql, [$courseId])->fetchColumn();
        return ($max ?? 0) + 1;
    }
}
