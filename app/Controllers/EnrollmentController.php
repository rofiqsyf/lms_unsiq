<?php
namespace App\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Core\Session;

class EnrollmentController extends BaseController
{
    /** POST /courses/{id}/enroll */
    public function enroll(int $courseId): void
    {
        $this->validateCSRF();
        $userId = Session::userId();
        $enrollmentModel = new Enrollment();

        if ($enrollmentModel->isEnrolled($userId, $courseId)) {
            flash_warning('Anda sudah terdaftar di mata kuliah ini.');
        } else {
            $enrollmentModel->enroll($userId, $courseId);
            flash_success('Berhasil mendaftar mata kuliah!');
        }

        $this->redirect(url('/courses/' . $courseId));
    }

    /** POST /courses/{id}/unenroll */
    public function unenroll(int $courseId): void
    {
        $this->validateCSRF();
        $enrollmentModel = new Enrollment();
        $enrollmentModel->unenroll(Session::userId(), $courseId);
        flash_success('Berhasil keluar dari mata kuliah.');
        $this->redirect(url('/courses'));
    }

    /** GET /my-courses */
    public function myCourses(): void
    {
        $this->setTitle('Kursus Saya');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Kursus Saya']]);
        $enrollmentModel = new Enrollment();
        $courses = $enrollmentModel->getEnrolledCourses(Session::userId());
        $this->render('courses/enrolled', ['pageTitle' => 'Kursus Saya', 'courses' => $courses]);
    }
}
