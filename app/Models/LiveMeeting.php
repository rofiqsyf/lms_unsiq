<?php
namespace App\Models;

class LiveMeeting extends BaseModel
{
    protected string $table = 'live_meetings';
    protected array $fillable = ['course_id', 'title', 'meeting_url', 'start_time', 'duration_minutes'];

    /**
     * Dapatkan daftar meeting aktif untuk course
     */
    public function getActiveByCourse(int $courseId): array
    {
        // Ambil yang belum kadaluwarsa (start_time + duration >= now)
        $sql = "SELECT * FROM {$this->table} 
                WHERE course_id = ? 
                AND DATE_ADD(start_time, INTERVAL duration_minutes MINUTE) >= NOW()
                ORDER BY start_time ASC";
        return $this->db->query($sql, [$courseId])->fetchAll();
    }
}
