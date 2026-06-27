<?php
namespace App\Models;

class Question extends BaseModel
{
    protected string $table = 'questions';
    protected array $fillable = [
        'quiz_id', 'question_text', 'type', 'options',
        'correct_answer', 'points', 'sort_order', 'explanation'
    ];

    public function getByQuiz(int $quizId, bool $shuffle = false): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE quiz_id = ? ORDER BY sort_order ASC";
        $questions = $this->db->query($sql, [$quizId])->fetchAll();
        if ($shuffle) shuffle($questions);
        return $questions;
    }

    public function getTotalPoints(int $quizId): int
    {
        $sql = "SELECT COALESCE(SUM(points), 0) FROM {$this->table} WHERE quiz_id = ?";
        return (int) $this->db->query($sql, [$quizId])->fetchColumn();
    }
}
