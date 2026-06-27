<?php
namespace App\Controllers;

use App\Models\Material;
use App\Models\Course;
use App\Core\Session;
use App\Core\FileUploader;

class MaterialController extends BaseController
{
    private Material $materialModel;

    public function __construct() { $this->materialModel = new Material(); }

    /** GET /courses/{courseId}/materials/create */
    public function create(int $courseId): void
    {
        $course = (new Course())->findById($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }
        $this->setTitle('Tambah Materi');
        $this->setBreadcrumbs([['label' => 'Mata Kuliah', 'url' => '/courses'], ['label' => $course['name'], 'url' => "/courses/{$courseId}"], ['label' => 'Tambah Materi']]);
        $this->render('materials/create', ['pageTitle' => 'Tambah Materi', 'course' => $course]);
    }

    /** POST /courses/{courseId}/materials */
    public function store(int $courseId): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $this->validate($data, ['title' => 'required|min:3|max:200']);

        $materialData = [
            'course_id'    => $courseId,
            'title'        => $data['title'],
            'content'      => $data['content'] ?? '',
            'section'      => $data['section'] ?? '',
            'video_url'    => $data['video_url'] ?? '',
            'sort_order'   => $this->materialModel->getNextSortOrder($courseId),
            'is_published' => isset($data['is_published']) ? 1 : 0,
        ];

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader('materials');
            $path = $uploader->upload('file');
            if ($path) {
                $materialData['file_path'] = $path;
                $materialData['file_name'] = $_FILES['file']['name'];
                $materialData['file_type'] = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $materialData['file_size'] = $_FILES['file']['size'];
            }
        }

        $this->materialModel->create($materialData);
        flash_success('Materi berhasil ditambahkan.');
        $this->redirect(url('/courses/' . $courseId));
    }

    /** GET /materials/{id} */
    public function show(int $id): void
    {
        $material = $this->materialModel->findById($id);
        if (!$material) { $this->redirect(url('/courses')); return; }
        $course = (new Course())->findById($material['course_id']);
        $this->setTitle($material['title']);
        $this->setBreadcrumbs([['label' => 'Mata Kuliah', 'url' => '/courses'], ['label' => $course['name'], 'url' => "/courses/{$course['id']}"], ['label' => $material['title']]]);
        $this->render('materials/show', ['pageTitle' => $material['title'], 'material' => $material, 'course' => $course]);
    }

    /** GET /materials/{id}/edit */
    public function edit(int $id): void
    {
        $material = $this->materialModel->findById($id);
        if (!$material) { $this->redirect(url('/courses')); return; }
        $course = (new Course())->findById($material['course_id']);
        $this->setTitle('Edit Materi');
        $this->render('materials/edit', ['pageTitle' => 'Edit Materi', 'material' => $material, 'course' => $course]);
    }

    /** POST /materials/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $material = $this->materialModel->findById($id);
        $this->validate($data, ['title' => 'required|min:3|max:200']);

        $updateData = [
            'title'        => $data['title'],
            'content'      => $data['content'] ?? '',
            'section'      => $data['section'] ?? '',
            'video_url'    => $data['video_url'] ?? '',
            'sort_order'   => $data['sort_order'] ?? $material['sort_order'],
            'is_published' => isset($data['is_published']) ? 1 : 0,
        ];

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader('materials');
            $path = $uploader->upload('file');
            if ($path) {
                FileUploader::delete($material['file_path']);
                $updateData['file_path'] = $path;
                $updateData['file_name'] = $_FILES['file']['name'];
                $updateData['file_type'] = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $updateData['file_size'] = $_FILES['file']['size'];
            }
        }

        $this->materialModel->update($id, $updateData);
        flash_success('Materi berhasil diperbarui.');
        $this->redirect(url('/courses/' . $material['course_id']));
    }

    /** POST /materials/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();
        $material = $this->materialModel->findById($id);
        if ($material) {
            FileUploader::delete($material['file_path']);
            $this->materialModel->delete($id);
            flash_success('Materi berhasil dihapus.');
            $this->redirect(url('/courses/' . $material['course_id']));
        } else {
            $this->redirect(url('/courses'));
        }
    }

    /** GET /materials/{id}/download */
    public function download(int $id): void
    {
        $material = $this->materialModel->findById($id);
        if (!$material || empty($material['file_path'])) {
            flash_error('File tidak ditemukan.');
            $this->back();
            return;
        }
        $this->materialModel->incrementDownload($id);
        $fullPath = UPLOAD_PATH . '/' . $material['file_path'];
        if (file_exists($fullPath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $material['file_name'] . '"');
            header('Content-Length: ' . filesize($fullPath));
            readfile($fullPath);
            exit;
        }
        flash_error('File tidak ditemukan di server.');
        $this->back();
    }
}
