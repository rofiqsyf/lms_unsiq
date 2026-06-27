<?php
namespace App\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\Course;
use App\Models\Enrollment;
use App\Core\Session;

class AttendanceController extends BaseController
{
    private Attendance $attendanceModel;
    private AttendanceRecord $recordModel;
    private Course $courseModel;

    public function __construct()
    {
        $this->attendanceModel = new Attendance();
        $this->recordModel = new AttendanceRecord();
        $this->courseModel = new Course();
    }

    /** GET /courses/{courseId}/attendance */
    public function index(int $courseId): void
    {
        $course = $this->courseModel->findById($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }

        $this->setTitle('Presensi - ' . $course['name']);
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $course['name'], 'url' => "/courses/{$courseId}"],
            ['label' => 'Presensi']
        ]);

        if (has_role('mahasiswa')) {
            $studentAttendance = $this->recordModel->getStudentAttendance($courseId, Session::userId());
            $this->render('attendance/student_index', [
                'pageTitle'         => 'Presensi Saya',
                'course'            => $course,
                'studentAttendance' => $studentAttendance
            ]);
            return;
        }

        // Admin / Dosen View
        $attendances = $this->attendanceModel->getByCourse($courseId);
        $enrollmentModel = new Enrollment();
        $students = $enrollmentModel->getCourseStudents($courseId);
        $studentCount = count(array_filter($students, fn($s) => $s['status'] === 'active'));

        $this->render('attendance/index', [
            'pageTitle'    => 'Kelola Presensi',
            'course'       => $course,
            'attendances'  => $attendances,
            'studentCount' => $studentCount
        ]);
    }

    /** GET /courses/{courseId}/attendance/create */
    public function create(int $courseId): void
    {
        $course = $this->courseModel->findById($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }

        if (!has_role('admin') && $course['dosen_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->redirect(url('/courses'));
            return;
        }

        $nextMeeting = $this->attendanceModel->getNextMeetingNumber($courseId);

        $this->setTitle('Buat Pertemuan Baru');
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $course['name'], 'url' => "/courses/{$courseId}"],
            ['label' => 'Presensi', 'url' => "/courses/{$courseId}/attendance"],
            ['label' => 'Tambah']
        ]);

        $this->render('attendance/create', [
            'pageTitle'   => 'Buat Pertemuan',
            'course'      => $course,
            'nextMeeting' => $nextMeeting
        ]);
    }

    /** POST /courses/{courseId}/attendance */
    public function store(int $courseId): void
    {
        $this->validateCSRF();
        
        $course = $this->courseModel->findById($courseId);
        if (!$course || (!has_role('admin') && $course['dosen_id'] != Session::userId())) {
            flash_error('Anda tidak memiliki akses.');
            $this->redirect(url('/courses'));
            return;
        }

        $data = $this->allInput();
        $this->validate($data, ['meeting_number' => 'required|numeric', 'meeting_date' => 'required']);

        $attendanceId = $this->attendanceModel->create([
            'course_id'      => $courseId,
            'meeting_number' => (int) $data['meeting_number'],
            'meeting_date'   => $data['meeting_date'],
            'topic'          => $data['topic'] ?? '',
            'created_by'     => Session::userId()
        ]);

        // Auto-initialize records for all enrolled students as 'alpa'
        $enrollmentModel = new Enrollment();
        $students = $enrollmentModel->getCourseStudents($courseId);
        
        foreach ($students as $student) {
            if ($student['status'] === 'active') {
                $this->recordModel->upsert($attendanceId, $student['user_id'], 'alpa');
            }
        }

        flash_success('Pertemuan berhasil dibuat. Silakan isi presensi mahasiswa.');
        $this->redirect(url("/attendance/{$attendanceId}"));
    }

    /** GET /attendance/{id} */
    public function show(int $id): void
    {
        $attendance = $this->attendanceModel->findWithCourse($id);
        if (!$attendance) { $this->redirect(url('/courses')); return; }

        $records = $this->recordModel->getByAttendance($id);

        $this->setTitle('Isi Presensi');
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $attendance['course_name'], 'url' => "/courses/{$attendance['course_id']}"],
            ['label' => 'Presensi', 'url' => "/courses/{$attendance['course_id']}/attendance"],
            ['label' => 'Pertemuan ' . $attendance['meeting_number']]
        ]);

        $this->render('attendance/show', [
            'pageTitle'  => 'Isi Presensi',
            'attendance' => $attendance,
            'records'    => $records
        ]);
    }

    /** POST /attendance/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();
        $attendance = $this->attendanceModel->findById($id);
        if (!$attendance) { $this->redirect(url('/courses')); return; }

        $course = $this->courseModel->findById($attendance['course_id']);
        if (!has_role('admin') && $course['dosen_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->redirect(url('/courses'));
            return;
        }

        $data = $this->allInput();
        $statuses = $data['status'] ?? [];
        $notes = $data['notes'] ?? [];

        foreach ($statuses as $userId => $status) {
            $note = $notes[$userId] ?? '';
            $this->recordModel->upsert($id, (int)$userId, $status, $note);
        }

        flash_success('Presensi berhasil disimpan.');
        $this->redirect(url("/courses/{$attendance['course_id']}/attendance"));
    }

    /** GET /courses/{courseId}/attendance/recap */
    public function recap(int $courseId): void
    {
        $course = $this->courseModel->findById($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }

        $attendances = $this->attendanceModel->getByCourse($courseId);
        $recap = $this->recordModel->getRecapByCourse($courseId);

        $this->setTitle('Rekap Presensi - ' . $course['name']);
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $course['name'], 'url' => "/courses/{$courseId}"],
            ['label' => 'Presensi', 'url' => "/courses/{$courseId}/attendance"],
            ['label' => 'Rekap']
        ]);

        $this->render('attendance/recap', [
            'pageTitle'   => 'Rekap Presensi',
            'course'      => $course,
            'attendances' => $attendances,
            'recap'       => $recap
        ]);
    }

    /** GET /courses/{courseId}/attendance/export */
    public function exportCsv(int $courseId): void
    {
        $course = $this->courseModel->findById($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }

        $attendances = $this->attendanceModel->getByCourse($courseId);
        $recap = $this->recordModel->getRecapByCourse($courseId);

        $filename = "Presensi_" . preg_replace('/[^A-Za-z0-9\-]/', '_', $course['code']) . "_" . date('Ymd') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF");

        // Headers
        $headers = ['No', 'NIM', 'Nama Mahasiswa'];
        foreach ($attendances as $a) {
            $headers[] = 'Pertemuan ' . $a['meeting_number'] . ' (' . $a['meeting_date'] . ')';
        }
        $headers = array_merge($headers, ['Hadir', 'Izin', 'Sakit', 'Alpa', 'Persentase (%)']);
        fputcsv($output, $headers);

        // Data
        $no = 1;
        foreach ($recap as $r) {
            $totalMeetings = count($attendances);
            $percentage = $totalMeetings > 0 ? round(($r['hadir'] / $totalMeetings) * 100) : 0;
            
            $row = [
                $no++,
                $r['nim_nidn'],
                $r['student_name']
            ];

            $studentRecords = $this->recordModel->getStudentAttendance($course['id'], $r['id']);
            $sMap = [];
            foreach ($studentRecords as $sr) {
                $sMap[$sr['meeting_number']] = $sr['status'];
            }

            foreach ($attendances as $a) {
                $st = $sMap[$a['meeting_number']] ?? 'alpa';
                $row[] = strtoupper(substr($st, 0, 1)); // H, I, S, A
            }

            $row[] = $r['hadir'];
            $row[] = $r['izin'];
            $row[] = $r['sakit'];
            $row[] = $r['alpa'];
            $row[] = $percentage;

            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
