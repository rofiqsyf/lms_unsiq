<?php
namespace App\Models;

class Grade extends BaseModel
{
    protected string $table = 'grades';
    protected array $fillable = ['course_id', 'user_id', 'grade_type', 'reference_id', 'score', 'max_score', 'notes'];

    public function getCourseGrades(int $courseId): array
    {
        $sql = "SELECT g.*, u.name as student_name, u.nim_nidn
                FROM grades g
                JOIN users u ON g.user_id = u.id
                WHERE g.course_id = ?
                ORDER BY u.name, g.grade_type";
        return $this->db->query($sql, [$courseId])->fetchAll();
    }

    public function getStudentGrades(int $userId, int $courseId): array
    {
        $sql = "SELECT g.*, c.name as course_name
                FROM grades g
                JOIN courses c ON g.course_id = c.id
                WHERE g.user_id = ? AND g.course_id = ?
                ORDER BY g.grade_type, g.created_at";
        return $this->db->query($sql, [$userId, $courseId])->fetchAll();
    }

    public function getStudentAllGrades(int $userId): array
    {
        $sql = "SELECT g.*, c.name as course_name, c.code as course_code
                FROM grades g
                JOIN courses c ON g.course_id = c.id
                WHERE g.user_id = ?
                ORDER BY c.name, g.grade_type";
        return $this->db->query($sql, [$userId])->fetchAll();
    }

    public function getAverageScore(int $userId, int $courseId): float
    {
        $sql = "SELECT AVG(score / max_score * 100) FROM grades WHERE user_id = ? AND course_id = ?";
        return (float) ($this->db->query($sql, [$userId, $courseId])->fetchColumn() ?: 0);
    }
}
