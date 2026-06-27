<?php
namespace App\Models;

class Attendance extends BaseModel
{
    protected string $table = 'attendances';
    protected array $fillable = ['course_id', 'meeting_number', 'meeting_date', 'topic', 'created_by'];

    /**
     * Get all meetings for a course
     */
    public function getByCourse(int $courseId): array
    {
        $sql = "SELECT a.*,
                (SELECT COUNT(*) FROM attendance_records ar WHERE ar.attendance_id = a.id AND ar.status = 'hadir') as hadir_count,
                (SELECT COUNT(*) FROM attendance_records ar WHERE ar.attendance_id = a.id) as total_count
                FROM {$this->table} a
                WHERE a.course_id = ?
                ORDER BY a.meeting_number ASC";
        return $this->db->query($sql, [$courseId])->fetchAll();
    }

    /**
     * Get next meeting number for a course
     */
    public function getNextMeetingNumber(int $courseId): int
    {
        $sql = "SELECT COALESCE(MAX(meeting_number), 0) + 1 FROM {$this->table} WHERE course_id = ?";
        return (int) $this->db->query($sql, [$courseId])->fetchColumn();
    }

    /**
     * Find meeting with course info
     */
    public function findWithCourse(int $id): ?array
    {
        $sql = "SELECT a.*, c.name as course_name, c.code as course_code, c.id as course_id
                FROM {$this->table} a
                JOIN courses c ON a.course_id = c.id
                WHERE a.id = ?";
        $result = $this->db->query($sql, [$id])->fetch();
        return $result ?: null;
    }

    /**
     * Count meetings for a course
     */
    public function countByCourse(int $courseId): int
    {
        return $this->count('course_id = ?', [$courseId]);
    }
}
