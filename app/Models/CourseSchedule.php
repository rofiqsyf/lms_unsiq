<?php
namespace App\Models;

class CourseSchedule extends BaseModel
{
    protected string $table = 'course_schedules';
    protected array $fillable = ['course_id', 'day_of_week', 'start_time', 'end_time', 'room'];

    /**
     * Get schedules for a course
     */
    public function getByCourse(int $courseId): array
    {
        // Order by day of week then start time
        $sql = "SELECT * FROM {$this->table}
                WHERE course_id = ?
                ORDER BY FIELD(day_of_week, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), start_time ASC";
        return $this->db->query($sql, [$courseId])->fetchAll();
    }
}
