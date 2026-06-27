<?php
namespace App\Models;

class QuizAttempt extends BaseModel
{
    protected string $table = 'quiz_attempts';
    protected array $fillable = ['quiz_id', 'user_id', 'score', 'total_points', 'started_at', 'completed_at', 'status'];

    public function getActiveAttempt(int $quizId, int $userId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE quiz_id = ? AND user_id = ? AND status = 'in_progress' LIMIT 1";
        $result = $this->db->query($sql, [$quizId, $userId])->fetch();
        return $result ?: null;
    }

    public function getAttemptsByQuizAndUser(int $quizId, int $userId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE quiz_id = ? AND user_id = ? ORDER BY started_at DESC";
        return $this->db->query($sql, [$quizId, $userId])->fetchAll();
    }

    public function countAttempts(int $quizId, int $userId): int
    {
        return $this->count('quiz_id = ? AND user_id = ?', [$quizId, $userId]);
    }

    public function getAttemptWithAnswers(int $attemptId): ?array
    {
        $attempt = $this->findById($attemptId);
        if (!$attempt) return null;

        $sql = "SELECT a.*, q.question_text, q.type, q.options, q.correct_answer, q.points, q.explanation
                FROM answers a
                JOIN questions q ON a.question_id = q.id
                WHERE a.attempt_id = ?
                ORDER BY q.sort_order";
        $attempt['answers'] = $this->db->query($sql, [$attemptId])->fetchAll();
        return $attempt;
    }
}
