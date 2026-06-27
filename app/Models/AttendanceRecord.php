<?php
namespace App\Models;

class AttendanceRecord extends BaseModel
{
    protected string $table = 'attendance_records';
    protected array $fillable = ['attendance_id', 'user_id', 'status', 'notes'];

    /**
     * Get records by attendance with student info
     */
    public function getByAttendance(int $attendanceId): array
    {
        $sql = "SELECT ar.*, u.name as student_name, u.nim_nidn, u.avatar
                FROM {$this->table} ar
                JOIN users u ON ar.user_id = u.id
                WHERE ar.attendance_id = ?
                ORDER BY u.name ASC";
        return $this->db->query($sql, [$attendanceId])->fetchAll();
    }

    /**
     * Insert or update attendance record
     */
    public function upsert(int $attendanceId, int $userId, string $status, ?string $notes = null): void
    {
        $existing = $this->db->query(
            "SELECT id FROM {$this->table} WHERE attendance_id = ? AND user_id = ?",
            [$attendanceId, $userId]
        )->fetch();

        if ($existing) {
            $this->update($existing['id'], [
                'status' => $status,
                'notes'  => $notes,
            ]);
        } else {
            $this->create([
                'attendance_id' => $attendanceId,
                'user_id'       => $userId,
                'status'        => $status,
                'notes'         => $notes,
            ]);
        }
    }

    /**
     * Get attendance recap for all students in a course
     * Returns: array of [student_name, nim, hadir, izin, sakit, alpa, total, percentage]
     */
    public function getRecapByCourse(int $courseId): array
    {
        $sql = "SELECT u.id, u.name as student_name, u.nim_nidn,
                SUM(CASE WHEN ar.status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN ar.status = 'izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN ar.status = 'sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN ar.status = 'alpa' THEN 1 ELSE 0 END) as alpa,
                COUNT(ar.id) as total
                FROM enrollments e
                JOIN users u ON e.user_id = u.id
                LEFT JOIN attendance_records ar ON ar.user_id = u.id
                    AND ar.attendance_id IN (SELECT id FROM attendances WHERE course_id = ?)
                WHERE e.course_id = ? AND e.status = 'active'
                GROUP BY u.id, u.name, u.nim_nidn
                ORDER BY u.name ASC";
        return $this->db->query($sql, [$courseId, $courseId])->fetchAll();
    }

    /**
     * Get student's own attendance for a course
     */
    public function getStudentAttendance(int $courseId, int $userId): array
    {
        $sql = "SELECT a.meeting_number, a.meeting_date, a.topic,
                       COALESCE(ar.status, 'alpa') as status, ar.notes
                FROM attendances a
                LEFT JOIN attendance_records ar ON ar.attendance_id = a.id AND ar.user_id = ?
                WHERE a.course_id = ?
                ORDER BY a.meeting_number ASC";
        return $this->db->query($sql, [$userId, $courseId])->fetchAll();
    }
}
