<?php
namespace App\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Course;
use App\Models\Notification;
use App\Core\Session;
use App\Core\FileUploader;

class AssignmentController extends BaseController
{
    private Assignment $assignmentModel;

    public function __construct() { $this->assignmentModel = new Assignment(); }

    /** GET /courses/{courseId}/assignments/create */
    public function create(int $courseId): void
    {
        $course = (new Course())->findById($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }
        $this->setTitle('Tambah Tugas');
        $this->setBreadcrumbs([['label' => 'Mata Kuliah', 'url' => '/courses'], ['label' => $course['name'], 'url' => "/courses/{$courseId}"], ['label' => 'Tambah Tugas']]);
        $this->render('assignments/create', ['pageTitle' => 'Tambah Tugas', 'course' => $course]);
    }

    /** POST /courses/{courseId}/assignments */
    public function store(int $courseId): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $this->validate($data, ['title' => 'required|min:3', 'deadline' => 'required', 'max_score' => 'required|numeric']);

        $assignmentData = [
            'course_id'     => $courseId,
            'title'         => $data['title'],
            'description'   => $data['description'] ?? '',
            'max_score'     => (int) $data['max_score'],
            'deadline'      => $data['deadline'],
            'allow_late'    => isset($data['allow_late']) ? 1 : 0,
            'late_penalty'  => $data['late_penalty'] ?? 0,
            'file_required' => isset($data['file_required']) ? 1 : 0,
            'is_published'  => isset($data['is_published']) ? 1 : 0,
        ];

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploader = new \App\Core\FileUploader('assignments');
            $path = $uploader->upload('file');
            if ($path) {
                $assignmentData['file_path'] = $path;
                $assignmentData['file_name'] = $_FILES['file']['name'];
            }
        }

        $this->assignmentModel->create($assignmentData);

        flash_success('Tugas berhasil ditambahkan.');
        $this->redirect(url('/courses/' . $courseId));
    }

    /** GET /assignments/{id}/edit */
    public function edit(int $id): void
    {
        $assignment = $this->assignmentModel->findWithCourse($id);
        if (!$assignment) { $this->redirect(url('/courses')); return; }

        // Authorization: only owner dosen or admin
        if (!has_role('admin') && $assignment['dosen_id'] != \App\Core\Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->redirect(url('/assignments/' . $id));
            return;
        }

        $this->setTitle('Edit Tugas');
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $assignment['course_name'], 'url' => '/courses/' . $assignment['course_id']],
            ['label' => $assignment['title'], 'url' => '/assignments/' . $id],
            ['label' => 'Edit']
        ]);
        $this->render('assignments/edit', ['pageTitle' => 'Edit Tugas', 'assignment' => $assignment]);
    }

    /** POST /assignments/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $assignment = $this->assignmentModel->findWithCourse($id);
        if (!$assignment) { $this->redirect(url('/courses')); return; }

        // Authorization: only owner dosen or admin
        if (!has_role('admin') && $assignment['dosen_id'] != \App\Core\Session::userId()) {
            flash_error('Anda tidak memiliki akses untuk mengubah tugas ini.');
            $this->redirect(url('/assignments/' . $id));
            return;
        }

        $this->validate($data, ['title' => 'required|min:3', 'deadline' => 'required', 'max_score' => 'required|numeric']);

        $assignmentData = [
            'title'         => $data['title'],
            'description'   => $data['description'] ?? '',
            'max_score'     => (int) $data['max_score'],
            'deadline'      => $data['deadline'],
            'allow_late'    => isset($data['allow_late']) ? 1 : 0,
            'late_penalty'  => $data['late_penalty'] ?? 0,
            'file_required' => isset($data['file_required']) ? 1 : 0,
            'is_published'  => isset($data['is_published']) ? 1 : 0,
        ];

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploader = new \App\Core\FileUploader('assignments');
            $path = $uploader->upload('file');
            if ($path) {
                $assignmentData['file_path'] = $path;
                $assignmentData['file_name'] = $_FILES['file']['name'];
            }
        } elseif (isset($data['remove_file']) && $data['remove_file'] === '1') {
            $assignmentData['file_path'] = null;
            $assignmentData['file_name'] = null;
        }

        $this->assignmentModel->update($id, $assignmentData);

        flash_success('Tugas berhasil diperbarui.');
        $this->redirect(url('/assignments/' . $id));
    }

    /** GET /assignments/{id} */
    public function show(int $id): void
    {
        $assignment = $this->assignmentModel->findWithCourse($id);
        if (!$assignment) { $this->redirect(url('/courses')); return; }

        $submissions = [];
        $mySubmission = null;

        if (has_role('dosen', 'admin')) {
            $submissions = (new Submission())->getByAssignment($id);
        } elseif (has_role('mahasiswa')) {
            $mySubmission = (new Submission())->findByAssignmentAndUser($id, Session::userId());
        }

        $this->setTitle($assignment['title']);
        $this->setBreadcrumbs([['label' => 'Mata Kuliah', 'url' => '/courses'], ['label' => $assignment['course_name'], 'url' => "/courses/{$assignment['course_id']}"], ['label' => $assignment['title']]]);
        $this->render('assignments/show', [
            'pageTitle'    => $assignment['title'],
            'assignment'   => $assignment,
            'submissions'  => $submissions,
            'mySubmission' => $mySubmission,
        ]);
    }

    /** POST /assignments/{id}/submit */
    public function submit(int $id): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $assignment = $this->assignmentModel->findById($id);
        if (!$assignment) { $this->redirect(url('/courses')); return; }

        $submissionModel = new Submission();
        $existing = $submissionModel->findByAssignmentAndUser($id, Session::userId());

        $status = 'submitted';
        if (strtotime($assignment['deadline']) < time() && !$assignment['allow_late']) {
            flash_error('Deadline sudah lewat.');
            $this->back();
            return;
        }
        if (strtotime($assignment['deadline']) < time()) {
            $status = 'late';
        }

        $submissionData = [
            'assignment_id' => $id,
            'user_id'       => Session::userId(),
            'content'       => $data['content'] ?? '',
            'status'        => $existing ? 'resubmitted' : $status,
            'submitted_at'  => date('Y-m-d H:i:s'),
        ];

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader('submissions');
            $path = $uploader->upload('file');
            if ($path) {
                $submissionData['file_path'] = $path;
                $submissionData['file_name'] = $_FILES['file']['name'];
            }
        }

        if ($existing) {
            $submissionModel->update($existing['id'], $submissionData);
        } else {
            $submissionModel->create($submissionData);
        }

        flash_success('Tugas berhasil dikumpulkan!');
        $this->redirect(url('/assignments/' . $id));
    }

    /** POST /submissions/{id}/grade */
    public function grade(int $submissionId): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $submissionModel = new Submission();
        $submissionModel->grade($submissionId, (int)$data['score'], $data['feedback'] ?? '', Session::userId());
        flash_success('Nilai berhasil disimpan.');
        $this->back();
    }

    /** POST /assignments/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();
        $assignment = $this->assignmentModel->findWithCourse($id);
        if ($assignment) {
            // Authorization: only owner dosen or admin
            if (!has_role('admin') && $assignment['dosen_id'] != \App\Core\Session::userId()) {
                flash_error('Anda tidak memiliki akses untuk menghapus tugas ini.');
                $this->redirect(url('/courses/' . $assignment['course_id']));
                return;
            }

            $this->assignmentModel->delete($id);
            flash_success('Tugas berhasil dihapus.');
            $this->redirect(url('/courses/' . $assignment['course_id']));
        }
    }
}
