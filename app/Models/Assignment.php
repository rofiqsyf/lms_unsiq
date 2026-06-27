<?php
namespace App\Models;

/**
 * Assignment Model
 */
class Assignment extends BaseModel
{
    protected string $table = 'assignments';
    protected array $fillable = [
        'course_id', 'title', 'description', 'max_score',
        'deadline', 'allow_late', 'late_penalty', 'file_required', 'is_published',
        'file_path', 'file_name'
    ];

    /**
     * Get assignments by course
     */
    public function getByCourse(int $courseId): array
    {
        return $this->findBy('course_id', $courseId);
    }

    /**
     * Get assignment with course info
     */
    public function findWithCourse(int $id): ?array
    {
        $sql = "SELECT a.*, c.name as course_name, c.code as course_code, c.dosen_id
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                WHERE a.id = ?";
        $result = $this->db->query($sql, [$id])->fetch();
        return $result ?: null;
    }

    /**
     * Get upcoming deadlines for a student
     */
    public function getUpcomingDeadlines(int $userId, int $limit = 5): array
    {
        $sql = "SELECT a.*, c.name as course_name, c.code as course_code,
                (SELECT COUNT(*) FROM submissions s WHERE s.assignment_id = a.id AND s.user_id = ?) as has_submitted
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                JOIN enrollments e ON e.course_id = c.id AND e.user_id = ?
                WHERE a.is_published = 1
                AND a.deadline >= NOW()
                AND e.status = 'active'
                ORDER BY a.deadline ASC
                LIMIT ?";
        return $this->db->query($sql, [$userId, $userId, $limit])->fetchAll();
    }

    /**
     * Get assignments by course with submission status for a student
     */
    public function getByCourseForStudent(int $courseId, int $userId): array
    {
        $sql = "SELECT a.*,
                s.id as submission_id, s.status as submission_status, s.score as submission_score,
                s.submitted_at
                FROM assignments a
                LEFT JOIN submissions s ON s.assignment_id = a.id AND s.user_id = ?
                WHERE a.course_id = ? AND a.is_published = 1
                ORDER BY a.deadline ASC";
        return $this->db->query($sql, [$userId, $courseId])->fetchAll();
    }

    /**
     * Get assignment deadlines for calendar
     */
    public function getForCalendar(int $userId, string $role): array
    {
        if ($role === 'mahasiswa') {
            $sql = "SELECT a.id, a.title, a.deadline as start_date, a.deadline as end_date, 'tugas' as event_type, c.name as course_name 
                    FROM assignments a
                    JOIN enrollments e ON e.course_id = a.course_id
                    JOIN courses c ON c.id = a.course_id
                    WHERE e.user_id = ? AND e.status = 'active' AND a.is_published = 1";
            return $this->db->query($sql, [$userId])->fetchAll();
        } elseif ($role === 'dosen') {
            $sql = "SELECT a.id, a.title, a.deadline as start_date, a.deadline as end_date, 'tugas' as event_type, c.name as course_name
                    FROM assignments a
                    JOIN courses c ON c.id = a.course_id
                    WHERE c.dosen_id = ?";
            return $this->db->query($sql, [$userId])->fetchAll();
        }
        return [];
    }
}
