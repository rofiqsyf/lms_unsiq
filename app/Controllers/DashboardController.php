<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Quiz;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\AcademicEvent;
use App\Core\Session;

/**
 * Dashboard Controller
 * Routes to the appropriate dashboard based on user role
 */
class DashboardController extends BaseController
{
    /**
     * Main dashboard entry point
     * GET /dashboard
     */
    public function index(): void
    {
        $user = Session::user();
        $role = $user['role'];

        $this->setTitle('Dashboard');
        $this->setBreadcrumbs([
            ['label' => 'Dashboard']
        ]);

        match ($role) {
            'admin'     => $this->adminDashboard(),
            'dosen'     => $this->dosenDashboard(),
            'mahasiswa' => $this->mahasiswaDashboard(),
            default     => $this->redirect(url('/login'))
        };
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard(): void
    {
        $userModel = new User();
        $courseModel = new Course();
        $enrollmentModel = new Enrollment();
        $announcementModel = new Announcement();
        $academicEventModel = new AcademicEvent();

        $data = [
            'pageTitle'     => 'Admin Dashboard',
            'upcomingEvents'=> $academicEventModel->getUpcomingEvents(3),
            'totalUsers'    => $userModel->count(),
            'totalDosen'    => $userModel->countByRole('dosen'),
            'totalMahasiswa'=> $userModel->countByRole('mahasiswa'),
            'totalCourses'  => $courseModel->count(),
            'activeCourses' => $courseModel->count("status = 'published'"),
            'totalEnrollments' => $enrollmentModel->count(),
            'recentUsers'   => $userModel->getRecent(5),
            'recentAnnouncements' => $announcementModel->getRecent(5),
            // Data for charts
            'enrollmentsByMonth' => $enrollmentModel->getEnrollmentsByMonth(),
            'usersByRole'   => [
                'admin'     => $userModel->countByRole('admin'),
                'dosen'     => $userModel->countByRole('dosen'),
                'mahasiswa' => $userModel->countByRole('mahasiswa'),
            ],
        ];

        $this->render('dashboard/admin', $data);
    }

    /**
     * Dosen Dashboard
     */
    private function dosenDashboard(): void
    {
        $userId = Session::userId();
        $courseModel = new Course();
        $submissionModel = new Submission();
        $announcementModel = new Announcement();
        $academicEventModel = new AcademicEvent();

        $data = [
            'pageTitle'       => 'Dosen Dashboard',
            'upcomingEvents'  => $academicEventModel->getUpcomingEvents(3),
            'myCourses'       => $courseModel->getByDosen($userId),
            'totalMyCourses'  => $courseModel->count('dosen_id = ?', [$userId]),
            'totalStudents'   => $courseModel->getTotalStudentsByDosen($userId),
            'pendingGrading'  => $submissionModel->countPendingByDosen($userId),
            'recentSubmissions' => $submissionModel->getRecentByDosen($userId, 5),
        ];

        $this->render('dashboard/dosen', $data);
    }

    /**
     * Mahasiswa Dashboard
     */
    private function mahasiswaDashboard(): void
    {
        $userId = Session::userId();
        $enrollmentModel = new Enrollment();
        $assignmentModel = new Assignment();
        $announcementModel = new Announcement();
        $notificationModel = new Notification();
        $academicEventModel = new AcademicEvent();
        $submissionModel = new Submission();

        $totalSubmissions = $submissionModel->count('user_id = ?', [$userId]);
        $xpPoints = $totalSubmissions * 10;

        $data = [
            'pageTitle'         => 'Mahasiswa Dashboard',
            'upcomingEvents'    => $academicEventModel->getUpcomingEvents(3),
            'enrolledCourses'   => $enrollmentModel->getEnrolledCourses($userId),
            'totalEnrolled'     => $enrollmentModel->count('user_id = ?', [$userId]),
            'upcomingDeadlines' => $assignmentModel->getUpcomingDeadlines($userId, 5),
            'recentGrades'      => $enrollmentModel->getRecentGrades($userId, 5),
            'recentAnnouncements' => $announcementModel->getForStudent($userId, 5),
            'unreadNotifications' => $notificationModel->countUnread($userId),
            'xpPoints'          => $xpPoints,
        ];

        $this->render('dashboard/mahasiswa', $data);
    }
}
