<?php
/**
 * ===========================================
 * LMS UNSIQ - Route Definitions
 * ===========================================
 * Semua URL aplikasi didefinisikan di sini.
 * Sesuai materi §2.4 Router PHP.
 * 
 * Format: $router->method(uri, [Controller::class, 'method'], [middleware])
 */

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\UserController;
use App\Controllers\CourseController;
use App\Controllers\EnrollmentController;
use App\Controllers\MaterialController;
use App\Controllers\AssignmentController;
use App\Controllers\QuizController;
use App\Controllers\GradeController;
use App\Controllers\AnnouncementController;
use App\Controllers\NotificationController;
use App\Controllers\ProfileController;
use App\Controllers\CategoryController;
use App\Controllers\ForumController;
use App\Controllers\AttendanceController;
use App\Controllers\SearchController;
use App\Controllers\MessageController;
use App\Controllers\LogController;
use App\Controllers\CalendarController;
use App\Controllers\ApiController;

// ===========================================
// GUEST ROUTES (belum login)
// ===========================================
$router->get('/', [AuthController::class, 'showLogin'], ['guest']);
$router->get('/login', [AuthController::class, 'showLogin'], ['guest']);
$router->post('/login', [AuthController::class, 'login'], ['guest']);
$router->get('/register', [AuthController::class, 'showRegister'], ['guest']);
$router->post('/register', [AuthController::class, 'register'], ['guest']);
$router->get('/forgot-password', [AuthController::class, 'showForgotPassword'], ['guest']);
$router->post('/forgot-password', [AuthController::class, 'forgotPassword'], ['guest']);
$router->get('/reset-password/{token}', [AuthController::class, 'showResetPassword'], ['guest']);
$router->post('/reset-password/{token}', [AuthController::class, 'resetPassword'], ['guest']);

// ===========================================
// AUTHENTICATED ROUTES
// ===========================================
$router->get('/logout', [AuthController::class, 'logout'], ['auth']);
$router->get('/dashboard', [DashboardController::class, 'index'], ['auth']);

// ===========================================
// USER MANAGEMENT (Admin only)
// ===========================================
$router->get('/users', [UserController::class, 'index'], ['auth', 'role:admin']);
$router->get('/users/create', [UserController::class, 'create'], ['auth', 'role:admin']);
$router->post('/users', [UserController::class, 'store'], ['auth', 'role:admin']);
$router->get('/users/{id}', [UserController::class, 'show'], ['auth', 'role:admin']);
$router->get('/users/{id}/edit', [UserController::class, 'edit'], ['auth', 'role:admin']);
$router->post('/users/{id}/update', [UserController::class, 'update'], ['auth', 'role:admin']);
$router->post('/users/{id}/delete', [UserController::class, 'destroy'], ['auth', 'role:admin']);

// ===========================================
// CATEGORIES (Admin only)
// ===========================================
$router->get('/categories', [CategoryController::class, 'index'], ['auth', 'role:admin']);
$router->post('/categories', [CategoryController::class, 'store'], ['auth', 'role:admin']);
$router->post('/categories/{id}/update', [CategoryController::class, 'update'], ['auth', 'role:admin']);
$router->post('/categories/{id}/delete', [CategoryController::class, 'destroy'], ['auth', 'role:admin']);

// ===========================================
// ADMIN LOGS
// ===========================================
$router->get('/admin/logs', [LogController::class, 'index'], ['auth', 'role:admin']);

// ===========================================
// CALENDAR
// ===========================================
$router->get('/calendar', [CalendarController::class, 'index'], ['auth']);
$router->get('/calendar/events', [CalendarController::class, 'events'], ['auth']);
$router->post('/calendar/events', [CalendarController::class, 'store'], ['auth', 'role:admin']);
$router->post('/calendar/events/{id}/delete', [CalendarController::class, 'destroy'], ['auth', 'role:admin']);
$router->post('/calendar/events/{id}/update', [CalendarController::class, 'update'], ['auth', 'role:admin']);

// ===========================================
// REST API (SIAKAD Integration)
// ===========================================
$router->get('/api/v1/courses', [ApiController::class, 'courses'], ['api_auth']);
$router->get('/api/v1/users', [ApiController::class, 'users'], ['api_auth']);

// ===========================================
// COURSES
// ===========================================
$router->get('/courses', [CourseController::class, 'index'], ['auth']);
$router->get('/courses/create', [CourseController::class, 'create'], ['auth', 'role:admin,dosen']);
$router->post('/courses', [CourseController::class, 'store'], ['auth', 'role:admin,dosen']);
$router->get('/courses/{id}', [CourseController::class, 'show'], ['auth']);
$router->get('/courses/{id}/edit', [CourseController::class, 'edit'], ['auth', 'role:admin,dosen']);
$router->post('/courses/{id}/update', [CourseController::class, 'update'], ['auth', 'role:admin,dosen']);
$router->post('/courses/{id}/delete', [CourseController::class, 'destroy'], ['auth', 'role:admin,dosen']);
$router->post('/courses/{id}/enroll', [CourseController::class, 'enroll'], ['auth', 'role:mahasiswa']);
$router->post('/courses/{courseId}/unenroll/{userId}', [CourseController::class, 'unenroll'], ['auth', 'role:admin,dosen']);

// SCHEDULES
$router->post('/courses/{id}/schedules', [CourseController::class, 'storeSchedule'], ['auth', 'role:admin,dosen']);
$router->post('/schedules/{id}/delete', [CourseController::class, 'destroySchedule'], ['auth', 'role:admin,dosen']);

// MEETINGS
$router->post('/courses/{id}/meetings', [CourseController::class, 'storeMeeting'], ['auth', 'role:admin,dosen']);
$router->post('/meetings/{id}/delete', [CourseController::class, 'destroyMeeting'], ['auth', 'role:admin,dosen']);

// ===========================================
// ENROLLMENTS (Mahasiswa)
// ===========================================
$router->post('/courses/{id}/enroll', [EnrollmentController::class, 'enroll'], ['auth', 'role:mahasiswa']);
$router->post('/courses/{id}/unenroll', [EnrollmentController::class, 'unenroll'], ['auth', 'role:mahasiswa']);
$router->get('/my-courses', [EnrollmentController::class, 'myCourses'], ['auth', 'role:mahasiswa']);

// ===========================================
// MATERIALS
// ===========================================
$router->get('/courses/{courseId}/materials/create', [MaterialController::class, 'create'], ['auth', 'role:admin,dosen']);
$router->post('/courses/{courseId}/materials', [MaterialController::class, 'store'], ['auth', 'role:admin,dosen']);
$router->get('/materials/{id}', [MaterialController::class, 'show'], ['auth']);
$router->get('/materials/{id}/edit', [MaterialController::class, 'edit'], ['auth', 'role:admin,dosen']);
$router->post('/materials/{id}/update', [MaterialController::class, 'update'], ['auth', 'role:admin,dosen']);
$router->post('/materials/{id}/delete', [MaterialController::class, 'destroy'], ['auth', 'role:admin,dosen']);
$router->get('/materials/{id}/download', [MaterialController::class, 'download'], ['auth']);

// ===========================================
// ASSIGNMENTS
// ===========================================
$router->get('/courses/{courseId}/assignments/create', [AssignmentController::class, 'create'], ['auth', 'role:admin,dosen']);
$router->post('/courses/{courseId}/assignments', [AssignmentController::class, 'store'], ['auth', 'role:admin,dosen']);
$router->get('/assignments/{id}', [AssignmentController::class, 'show'], ['auth']);
$router->get('/assignments/{id}/edit', [AssignmentController::class, 'edit'], ['auth', 'role:admin,dosen']);
$router->post('/assignments/{id}/update', [AssignmentController::class, 'update'], ['auth', 'role:admin,dosen']);
$router->post('/assignments/{id}/submit', [AssignmentController::class, 'submit'], ['auth', 'role:mahasiswa']);
$router->post('/submissions/{id}/grade', [AssignmentController::class, 'grade'], ['auth', 'role:admin,dosen']);
$router->post('/assignments/{id}/delete', [AssignmentController::class, 'destroy'], ['auth', 'role:admin,dosen']);

// ===========================================
// QUIZZES
// ===========================================
$router->get('/courses/{courseId}/quizzes/create', [QuizController::class, 'create'], ['auth', 'role:admin,dosen']);
$router->post('/courses/{courseId}/quizzes', [QuizController::class, 'store'], ['auth', 'role:admin,dosen']);
$router->get('/quizzes/{id}', [QuizController::class, 'show'], ['auth']);
$router->get('/quizzes/{id}/edit', [QuizController::class, 'edit'], ['auth', 'role:admin,dosen']);
$router->post('/quizzes/{id}/update', [QuizController::class, 'update'], ['auth', 'role:admin,dosen']);
$router->post('/quizzes/{quizId}/questions', [QuizController::class, 'storeQuestion'], ['auth', 'role:admin,dosen']);
$router->post('/questions/{id}/update', [QuizController::class, 'updateQuestion'], ['auth', 'role:admin,dosen']);
$router->post('/questions/{id}/delete', [QuizController::class, 'deleteQuestion'], ['auth', 'role:admin,dosen']);
$router->post('/quizzes/{id}/start', [QuizController::class, 'start'], ['auth', 'role:mahasiswa']);
$router->get('/quizzes/{quizId}/attempt/{attemptId}', [QuizController::class, 'attempt'], ['auth', 'role:mahasiswa']);
$router->post('/quizzes/{quizId}/attempt/{attemptId}/submit', [QuizController::class, 'submitAttempt'], ['auth', 'role:mahasiswa']);
$router->get('/quizzes/{quizId}/result/{attemptId}', [QuizController::class, 'result'], ['auth']);
$router->post('/quizzes/{id}/delete', [QuizController::class, 'destroy'], ['auth', 'role:admin,dosen']);

// FORUM
$router->get('/courses/{courseId}/forum', [ForumController::class, 'index'], ['auth']);
$router->get('/courses/{courseId}/forum/create', [ForumController::class, 'create'], ['auth']);
$router->post('/courses/{courseId}/forum', [ForumController::class, 'store'], ['auth']);
$router->get('/forum/thread/{id}', [ForumController::class, 'show'], ['auth']);
$router->post('/forum/thread/{id}/reply', [ForumController::class, 'reply'], ['auth']);
$router->post('/forum/thread/{id}/delete', [ForumController::class, 'destroy'], ['auth', 'role:admin,dosen']);
$router->post('/forum/reply/{id}/delete', [ForumController::class, 'deleteReply'], ['auth', 'role:admin,dosen']);

// ATTENDANCE
$router->get('/courses/{courseId}/attendance', [AttendanceController::class, 'index'], ['auth']);
$router->get('/courses/{courseId}/attendance/create', [AttendanceController::class, 'create'], ['auth', 'role:admin,dosen']);
$router->post('/courses/{courseId}/attendance', [AttendanceController::class, 'store'], ['auth', 'role:admin,dosen']);
$router->get('/attendance/{id}', [AttendanceController::class, 'show'], ['auth']);
$router->post('/attendance/{id}/update', [AttendanceController::class, 'update'], ['auth', 'role:admin,dosen']);
$router->get('/courses/{courseId}/attendance/recap', [AttendanceController::class, 'recap'], ['auth']);
$router->get('/courses/{courseId}/attendance/export', [AttendanceController::class, 'exportCsv'], ['auth', 'role:admin,dosen']);

// ===========================================
// GRADES & EXPORT
// ===========================================
$router->get('/grades', [GradeController::class, 'index'], ['auth']);
$router->get('/courses/{courseId}/grades', [GradeController::class, 'courseGrades'], ['auth', 'role:admin,dosen']);
$router->get('/courses/{courseId}/grades/export', [GradeController::class, 'exportCsv'], ['auth', 'role:admin,dosen']);

// ===========================================
// GLOBAL SEARCH
// ===========================================
$router->get('/search/ajax', [\App\Controllers\SearchController::class, 'ajax'], ['auth']);
$router->get('/search', [\App\Controllers\SearchController::class, 'index'], ['auth']);

// ===========================================
// GAMIFICATION
// ===========================================
$router->get('/gamification/streak', [\App\Controllers\GamificationController::class, 'streak'], ['auth', 'role:mahasiswa']);
$router->get('/gamification/rewards', [\App\Controllers\GamificationController::class, 'rewards'], ['auth', 'role:mahasiswa']);

// ===========================================
// MESSAGES (Direct Messaging)
// ===========================================
$router->get('/messages', [MessageController::class, 'index'], ['auth']);
$router->get('/messages/new', [MessageController::class, 'create'], ['auth']);
$router->get('/messages/{userId}', [MessageController::class, 'show'], ['auth']);
$router->post('/messages/{userId}', [MessageController::class, 'store'], ['auth']);

// ===========================================
// ANNOUNCEMENTS
// ===========================================
$router->get('/announcements', [AnnouncementController::class, 'index'], ['auth']);
$router->get('/announcements/create', [AnnouncementController::class, 'create'], ['auth', 'role:admin,dosen']);
$router->post('/announcements', [AnnouncementController::class, 'store'], ['auth', 'role:admin,dosen']);
$router->get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'], ['auth', 'role:admin,dosen']);
$router->post('/announcements/{id}/update', [AnnouncementController::class, 'update'], ['auth', 'role:admin,dosen']);
$router->post('/announcements/{id}/delete', [AnnouncementController::class, 'destroy'], ['auth', 'role:admin,dosen']);

// ===========================================
// NOTIFICATIONS
// ===========================================
$router->get('/notifications', [NotificationController::class, 'index'], ['auth']);
$router->get('/notifications/read-all', [NotificationController::class, 'readAll'], ['auth']);
$router->get('/notifications/{id}/read', [NotificationController::class, 'read'], ['auth']);
$router->get('/notifications/json', [NotificationController::class, 'getJson'], ['auth']);

// ===========================================
// EXTRA FEATURES (Calendar, Meetings)
// ===========================================
$router->get('/calendar', [\App\Controllers\FeatureController::class, 'calendar'], ['auth']);
$router->get('/meetings', [\App\Controllers\FeatureController::class, 'meetings'], ['auth']);

// ===========================================
// PROFILE
// ===========================================
$router->get('/profile', [ProfileController::class, 'edit'], ['auth']);
$router->post('/profile/update', [ProfileController::class, 'update'], ['auth']);

// ===========================================
// SETTINGS
// ===========================================
$router->get('/settings', [\App\Controllers\SettingController::class, 'index'], ['auth', 'role:admin']);
$router->post('/settings/update', [\App\Controllers\SettingController::class, 'update'], ['auth', 'role:admin']);
