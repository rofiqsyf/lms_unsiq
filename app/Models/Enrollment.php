<?php
namespace App\Models;

/**
 * Enrollment Model
 */
class Enrollment extends BaseModel
{
    protected string $table = 'enrollments';
    protected array $fillable = ['user_id', 'course_id', 'status', 'progress', 'completed_at'];

    /**
     * Check if user is enrolled in a course
     */
    public function isEnrolled(int $userId, int $courseId): bool
    {
        return $this->count('user_id = ? AND course_id = ?', [$userId, $courseId]) > 0;
    }

    /**
     * Get enrolled courses for a student
     */
    public function getEnrolledCourses(int $userId): array
    {
        $sql = "SELECT e.*, c.code, c.name as course_name, c.thumbnail, c.sks,
                u.name as dosen_name, cat.name as category_name
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                LEFT JOIN users u ON c.dosen_id = u.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE e.user_id = ? AND e.status = 'active'
                ORDER BY e.enrolled_at DESC";
        return $this->db->query($sql, [$userId])->fetchAll();
    }

    /**
     * Get students enrolled in a course
     */
    public function getCourseStudents(int $courseId): array
    {
        $sql = "SELECT e.*, u.name, u.email, u.nim_nidn, u.avatar
                FROM enrollments e
                JOIN users u ON e.user_id = u.id
                WHERE e.course_id = ? AND e.status = 'active'
                ORDER BY u.name";
        return $this->db->query($sql, [$courseId])->fetchAll();
    }

    /**
     * Get monthly enrollment data for charts
     */
    public function getEnrollmentsByMonth(): array
    {
        $sql = "SELECT DATE_FORMAT(enrolled_at, '%Y-%m') as month,
                COUNT(*) as total
                FROM enrollments
                WHERE enrolled_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(enrolled_at, '%Y-%m')
                ORDER BY month";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Get recent grades for a student
     */
    public function getRecentGrades(int $userId, int $limit = 5): array
    {
        $sql = "SELECT g.*, c.name as course_name
                FROM grades g
                JOIN courses c ON g.course_id = c.id
                WHERE g.user_id = ?
                ORDER BY g.created_at DESC LIMIT ?";
        return $this->db->query($sql, [$userId, $limit])->fetchAll();
    }

    /**
     * Enroll student
     */
    public function enroll(int $userId, int $courseId): int
    {
        return $this->create([
            'user_id'   => $userId,
            'course_id' => $courseId,
            'status'    => 'active',
        ]);
    }

    /**
     * Unenroll student
     */
    public function unenroll(int $userId, int $courseId): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND course_id = ?";
        $this->db->query($sql, [$userId, $courseId]);
        return true;
    }
}
