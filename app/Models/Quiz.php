<?php
namespace App\Models;

/**
 * Quiz Model
 */
class Quiz extends BaseModel
{
    protected string $table = 'quizzes';
    protected array $fillable = [
        'course_id', 'title', 'description', 'duration_minutes',
        'max_attempts', 'shuffle_questions', 'show_result', 'passing_score',
        'is_published', 'start_time', 'end_time'
    ];

    /**
     * Get quizzes by course
     */
    public function getByCourse(int $courseId): array
    {
        $sql = "SELECT q.*, (SELECT COUNT(*) FROM questions WHERE quiz_id = q.id) as question_count
                FROM quizzes q WHERE q.course_id = ? ORDER BY q.created_at DESC";
        return $this->db->query($sql, [$courseId])->fetchAll();
    }

    /**
     * Get quiz with course info
     */
    public function findWithCourse(int $id): ?array
    {
        $sql = "SELECT q.*, c.name as course_name, c.code as course_code, c.dosen_id,
                (SELECT COUNT(*) FROM questions WHERE quiz_id = q.id) as question_count
                FROM quizzes q
                JOIN courses c ON q.course_id = c.id
                WHERE q.id = ?";
        $result = $this->db->query($sql, [$id])->fetch();
        return $result ?: null;
    }

    /**
     * Get quizzes for student with attempt info
     */
    public function getByCourseForStudent(int $courseId, int $userId): array
    {
        $sql = "SELECT q.*,
                (SELECT COUNT(*) FROM questions WHERE quiz_id = q.id) as question_count,
                (SELECT COUNT(*) FROM quiz_attempts WHERE quiz_id = q.id AND user_id = ?) as attempt_count,
                (SELECT MAX(score) FROM quiz_attempts WHERE quiz_id = q.id AND user_id = ? AND status = 'completed') as best_score
                FROM quizzes q
                WHERE q.course_id = ? AND q.is_published = 1
                ORDER BY q.created_at DESC";
        return $this->db->query($sql, [$userId, $userId, $courseId])->fetchAll();
    }
}
