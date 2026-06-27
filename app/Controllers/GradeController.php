<?php
namespace App\Controllers;

use App\Models\Grade;
use App\Core\Session;

class GradeController extends BaseController
{
    /** GET /grades */
    public function index(): void
    {
        $this->setTitle('Nilai');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Nilai']]);
        $gradeModel = new Grade();

        if (has_role('mahasiswa')) {
            $grades = $gradeModel->getStudentAllGrades(Session::userId());
            $this->render('grades/my-grades', ['pageTitle' => 'Nilai Saya', 'grades' => $grades]);
        } else {
            // Dosen/Admin: show courses to select
            $this->render('grades/course-grades', ['pageTitle' => 'Rekap Nilai']);
        }
    }

    /** GET /grades/course/{courseId} */
    public function courseGrades(int $courseId): void
    {
        $gradeModel = new Grade();
        $grades = $gradeModel->getCourseGrades($courseId);
        $this->setTitle('Rekap Nilai');
        $this->render('grades/course-grades', ['pageTitle' => 'Rekap Nilai', 'grades' => $grades, 'courseId' => $courseId]);
    }
    /** GET /courses/{courseId}/grades/export */
    public function exportCsv(int $courseId): void
    {
        $gradeModel = new Grade();
        $grades = $gradeModel->getCourseGrades($courseId);
        
        $courseModel = new \App\Models\Course();
        $course = $courseModel->findById($courseId);

        if (!$course || (has_role('dosen') && $course['dosen_id'] !== Session::userId())) {
            $this->redirect(url('/courses'));
            return;
        }

        $filename = "Nilai_" . preg_replace('/[^A-Za-z0-9\-]/', '_', $course['code']) . "_" . date('Ymd') . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF");

        // Headers
        $headers = ['NIM', 'Nama Mahasiswa', 'Total Nilai'];
        fputcsv($output, $headers);

        // Data rows (assuming $grades structure has 'total')
        foreach ($grades as $studentId => $studentData) {
            $row = [
                $studentData['nim_nidn'] ?? '-',
                $studentData['name'] ?? 'Mahasiswa',
                $studentData['total'] ?? 0
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
