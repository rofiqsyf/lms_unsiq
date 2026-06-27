<?php
namespace App\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\CourseSchedule;
use App\Models\LiveMeeting;
use App\Core\Session;
use App\Core\FileUploader;
use App\Core\Logger;

/**
 * Course Management Controller
 */
class CourseController extends BaseController
{
    private Course $courseModel;
    private Enrollment $enrollmentModel;
    private CourseSchedule $scheduleModel;
    private LiveMeeting $meetingModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
        $this->scheduleModel = new CourseSchedule();
        $this->meetingModel = new LiveMeeting();
    }

    /** GET /courses */
    public function index(): void
    {
        $this->setTitle('Mata Kuliah');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Mata Kuliah']]);

        $page   = $this->getPage();
        $search = $this->query('search', '');
        $status = $this->query('status', '');
        $dosenId = has_role('dosen') ? Session::userId() : 0;

        $result = $this->courseModel->paginateCourses($page, 12, $search, $status, $dosenId, url('/courses'));
        $categories = (new Category())->findAll('name', 'ASC');

        $this->render('courses/index', [
            'pageTitle'  => 'Mata Kuliah',
            'courses'    => $result['data'],
            'pagination' => $result['pagination'],
            'categories' => $categories,
            'search'     => $search,
            'status'     => $status,
        ]);
    }

    /** GET /courses/create */
    public function create(): void
    {
        $this->setTitle('Tambah Mata Kuliah');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Mata Kuliah', 'url' => '/courses'], ['label' => 'Tambah']]);

        $categories = (new Category())->findAll('name', 'ASC');
        $dosens = (new User())->getAllDosen();

        $this->render('courses/create', [
            'pageTitle'  => 'Tambah Mata Kuliah',
            'categories' => $categories,
            'dosens'     => $dosens,
        ]);
    }

    /** POST /courses */
    public function store(): void
    {
        $this->validateCSRF();
        $data = $this->allInput();

        $this->validate($data, [
            'code'   => 'required|unique:courses,code',
            'name'   => 'required|min:3|max:200',
            'sks'    => 'required|numeric',
        ]);

        $courseData = [
            'code'          => $data['code'],
            'name'          => $data['name'],
            'description'   => $data['description'] ?? '',
            'dosen_id'      => has_role('admin') ? ($data['dosen_id'] ?? Session::userId()) : Session::userId(),
            'category_id'   => $data['category_id'] ?: null,
            'sks'           => (int) $data['sks'],
            'semester'      => $data['semester'] ?? '',
            'academic_year' => $data['academic_year'] ?? '',
            'status'        => $data['status'] ?? 'draft',
            'max_students'  => $data['max_students'] ?? 40,
        ];

        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader('courses');
            $uploader->setAllowedTypes(['jpg', 'jpeg', 'png']);
            $path = $uploader->upload('thumbnail');
            if ($path) $courseData['thumbnail'] = $path;
        }

        $courseId = $this->courseModel->create($courseData);
        Logger::log('create_course', 'course', $courseId, 'Mata kuliah baru dibuat: ' . $courseData['code']);
        
        flash_success('Mata kuliah berhasil ditambahkan.');
        $this->redirect(url('/courses'));
    }

    /** GET /courses/{id} */
    public function show(int $id): void
    {
        $course = $this->courseModel->findWithDosen($id);
        if (!$course) {
            $this->redirect(url('/courses'));
            return;
        }

        // Handle Draft restriction
        if ($course['status'] === 'draft' && !has_role('admin', 'dosen')) {
            $this->redirect(url('/courses'));
            return;
        }

        // Fetch related data
        $materialModel = new Material();
        $assignmentModel = new Assignment();
        $quizModel = new Quiz();

        $materials = $materialModel->getByCourse($id);
        $assignments = $assignmentModel->getByCourse($id);
        $quizzes = $quizModel->getByCourse($id);

        $schedules = $this->scheduleModel->getByCourse($id);
        $liveMeetings = $this->meetingModel->getActiveByCourse($id);

        // Check if student is enrolled
        $isEnrolled = false;
        if (has_role('mahasiswa')) {
            $isEnrolled = $this->enrollmentModel->isEnrolled(Session::userId(), $id);
        }

        $this->setTitle($course['name']);
        $this->setBreadcrumbs([
            ['label' => 'Katalog', 'url' => '/courses'],
            ['label' => $course['code']]
        ]);

        $this->render('courses/show', [
            'pageTitle'   => $course['name'],
            'course'      => $course,
            'materials'   => $materials,
            'assignments' => $assignments,
            'quizzes'     => $quizzes,
            'schedules'   => $schedules,
            'liveMeetings'=> $liveMeetings,
            'isEnrolled'  => $isEnrolled,
        ]);
    }

    /** GET /courses/{id}/edit */
    public function edit(int $id): void
    {
        $course = $this->courseModel->findById($id);
        if (!$course) { $this->redirect(url('/courses')); return; }

        // Authorization: only owner dosen or admin
        if (!has_role('admin') && $course['dosen_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->redirect(url('/courses'));
            return;
        }

        $this->setTitle('Edit Mata Kuliah');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Mata Kuliah', 'url' => '/courses'], ['label' => 'Edit']]);

        $categories = (new Category())->findAll('name', 'ASC');
        $dosens = (new User())->getAllDosen();

        $this->render('courses/edit', [
            'pageTitle'  => 'Edit Mata Kuliah',
            'course'     => $course,
            'categories' => $categories,
            'dosens'     => $dosens,
        ]);
    }

    /** POST /courses/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();
        $data = $this->allInput();

        $this->validate($data, [
            'code' => "required|unique:courses,code,{$id}",
            'name' => 'required|min:3|max:200',
            'sks'  => 'required|numeric',
        ]);

        $updateData = [
            'code'          => $data['code'],
            'name'          => $data['name'],
            'description'   => $data['description'] ?? '',
            'category_id'   => $data['category_id'] ?: null,
            'sks'           => (int) $data['sks'],
            'semester'      => $data['semester'] ?? '',
            'academic_year' => $data['academic_year'] ?? '',
            'status'        => $data['status'] ?? 'draft',
            'max_students'  => $data['max_students'] ?? 40,
        ];

        if (has_role('admin') && !empty($data['dosen_id'])) {
            $updateData['dosen_id'] = $data['dosen_id'];
        }

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader('courses');
            $uploader->setAllowedTypes(['jpg', 'jpeg', 'png']);
            $path = $uploader->upload('thumbnail');
            if ($path) $updateData['thumbnail'] = $path;
        }

        $this->courseModel->update($id, $updateData);
        flash_success('Mata kuliah berhasil diperbarui.');
        $this->redirect(url('/courses/' . $id));
    }

    /** POST /courses/{id}/enroll */
    public function enroll(int $id): void
    {
        $this->validateCSRF();
        if (!has_role('mahasiswa')) { $this->back(); return; }

        $this->enrollmentModel->enroll(Session::userId(), $id);
        flash_success('Berhasil mendaftar ke mata kuliah ini.');
        $this->redirect(url('/courses/' . $id));
    }

    /** POST /courses/{courseId}/unenroll/{userId} */
    public function unenroll(int $courseId, int $userId): void
    {
        $this->validateCSRF();
        
        $course = $this->courseModel->findById($courseId);
        if (has_role('dosen') && $course['dosen_id'] !== Session::userId()) {
            $this->back(); return;
        }

        $this->enrollmentModel->unenroll($userId, $courseId);
        flash_success('Mahasiswa berhasil dikeluarkan dari mata kuliah.');
        $this->redirect(url('/users/' . $userId));
    }

    /**
     * POST /courses/{id}/schedules
     */
    public function storeSchedule(int $id): void
    {
        $this->validateCSRF();
        
        $course = $this->courseModel->findById($id);
        if (!$course || (has_role('dosen') && $course['dosen_id'] !== Session::userId())) {
            $this->back();
            return;
        }

        $data = $this->allInput();
        $this->validate($data, [
            'day_of_week' => 'required',
            'start_time'  => 'required',
            'end_time'    => 'required',
        ]);

        $this->scheduleModel->create([
            'course_id'   => $id,
            'day_of_week' => $data['day_of_week'],
            'start_time'  => $data['start_time'],
            'end_time'    => $data['end_time'],
            'room'        => $data['room'] ?? '',
        ]);

        flash_success('Jadwal perkuliahan berhasil ditambahkan.');
        $this->redirect(url('/courses/' . $id));
    }

    /**
     * POST /schedules/{id}/delete
     */
    public function destroySchedule(int $id): void
    {
        $this->validateCSRF();
        
        $schedule = $this->scheduleModel->findById($id);
        if (!$schedule) {
            $this->back();
            return;
        }

        $courseId = $schedule['course_id'];
        $course = $this->courseModel->findById($courseId);
        
        if (has_role('dosen') && $course['dosen_id'] !== Session::userId()) {
            $this->back();
            return;
        }

        $this->scheduleModel->delete($id);
        flash_success('Jadwal berhasil dihapus.');
        $this->redirect(url('/courses/' . $courseId));
    }

    /**
     * POST /courses/{id}/meetings
     */
    public function storeMeeting(int $id): void
    {
        $this->validateCSRF();
        
        $course = $this->courseModel->findById($id);
        if (!$course || (has_role('dosen') && $course['dosen_id'] !== Session::userId())) {
            $this->back();
            return;
        }

        $data = $this->allInput();
        $this->validate($data, [
            'title'       => 'required',
            'meeting_url' => 'required',
            'start_time'  => 'required',
        ]);

        $this->meetingModel->create([
            'course_id'        => $id,
            'title'            => $data['title'],
            'meeting_url'      => $data['meeting_url'],
            'start_time'       => $data['start_time'],
            'duration_minutes' => $data['duration_minutes'] ?: 60,
        ]);

        Logger::log('create_meeting', 'course', $id, 'Membuat live meeting: ' . $data['title']);
        flash_success('Live meeting berhasil dijadwalkan.');
        $this->redirect(url('/courses/' . $id));
    }

    /**
     * POST /meetings/{id}/delete
     */
    public function destroyMeeting(int $id): void
    {
        $this->validateCSRF();
        
        $meeting = $this->meetingModel->findById($id);
        if (!$meeting) {
            $this->back();
            return;
        }

        $courseId = $meeting['course_id'];
        $course = $this->courseModel->findById($courseId);
        
        if (has_role('dosen') && $course['dosen_id'] !== Session::userId()) {
            $this->back();
            return;
        }

        $this->meetingModel->delete($id);
        Logger::log('delete_meeting', 'course', $courseId, 'Menghapus live meeting');
        flash_success('Live meeting berhasil dibatalkan.');
        $this->redirect(url('/courses/' . $courseId));
    }

    /** POST /courses/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();
        $this->courseModel->delete($id);
        flash_success('Mata kuliah berhasil dihapus.');
        $this->redirect(url('/courses'));
    }
}
