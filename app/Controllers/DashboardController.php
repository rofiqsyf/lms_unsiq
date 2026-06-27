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

        $data = [
            'pageTitle'     => 'Admin Dashboard',
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

        $data = [
            'pageTitle'       => 'Dosen Dashboard',
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

        $data = [
            'pageTitle'         => 'Mahasiswa Dashboard',
            'enrolledCourses'   => $enrollmentModel->getEnrolledCourses($userId),
            'totalEnrolled'     => $enrollmentModel->count('user_id = ?', [$userId]),
            'upcomingDeadlines' => $assignmentModel->getUpcomingDeadlines($userId, 5),
            'recentGrades'      => $enrollmentModel->getRecentGrades($userId, 5),
            'recentAnnouncements' => $announcementModel->getForStudent($userId, 5),
            'unreadNotifications' => $notificationModel->countUnread($userId),
        ];

        $this->render('dashboard/mahasiswa', $data);
    }
}
