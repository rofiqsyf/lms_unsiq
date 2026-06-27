<?php
namespace App\Models;

/**
 * Submission Model
 */
class Submission extends BaseModel
{
    protected string $table = 'submissions';
    protected array $fillable = [
        'assignment_id', 'user_id', 'content', 'file_path', 'file_name',
        'score', 'feedback', 'status', 'submitted_at', 'graded_at', 'graded_by'
    ];

    /**
     * Get submission by assignment and user
     */
    public function findByAssignmentAndUser(int $assignmentId, int $userId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE assignment_id = ? AND user_id = ? LIMIT 1";
        $result = $this->db->query($sql, [$assignmentId, $userId])->fetch();
        return $result ?: null;
    }

    /**
     * Get all submissions for an assignment
     */
    public function getByAssignment(int $assignmentId): array
    {
        $sql = "SELECT s.*, u.name as student_name, u.nim_nidn, u.avatar
                FROM submissions s
                JOIN users u ON s.user_id = u.id
                WHERE s.assignment_id = ?
                ORDER BY s.submitted_at DESC";
        return $this->db->query($sql, [$assignmentId])->fetchAll();
    }

    /**
     * Count pending (ungraded) submissions for a dosen
     */
    public function countPendingByDosen(int $dosenId): int
    {
        $sql = "SELECT COUNT(*) FROM submissions s
                JOIN assignments a ON s.assignment_id = a.id
                JOIN courses c ON a.course_id = c.id
                WHERE c.dosen_id = ? AND s.status = 'submitted'";
        return (int) $this->db->query($sql, [$dosenId])->fetchColumn();
    }

    /**
     * Get recent submissions for a dosen's courses
     */
    public function getRecentByDosen(int $dosenId, int $limit = 5): array
    {
        $sql = "SELECT s.*, u.name as student_name, a.title as assignment_title, c.name as course_name
                FROM submissions s
                JOIN users u ON s.user_id = u.id
                JOIN assignments a ON s.assignment_id = a.id
                JOIN courses c ON a.course_id = c.id
                WHERE c.dosen_id = ?
                ORDER BY s.submitted_at DESC LIMIT ?";
        return $this->db->query($sql, [$dosenId, $limit])->fetchAll();
    }

    /**
     * Grade a submission
     */
    public function grade(int $id, int $score, string $feedback, int $gradedBy): bool
    {
        return $this->update($id, [
            'score'     => $score,
            'feedback'  => $feedback,
            'status'    => 'graded',
            'graded_at' => date('Y-m-d H:i:s'),
            'graded_by' => $gradedBy,
        ]);
    }
}
