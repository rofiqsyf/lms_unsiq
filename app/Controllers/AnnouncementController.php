<?php
namespace App\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Core\Session;

class AnnouncementController extends BaseController
{
    private Announcement $model;
    public function __construct() { $this->model = new Announcement(); }

    /** GET /announcements */
    public function index(): void
    {
        $this->setTitle('Pengumuman');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Pengumuman']]);
        $result = $this->model->paginateAnnouncements($this->getPage(), 10, url('/announcements'));
        $this->render('announcements/index', ['pageTitle' => 'Pengumuman', 'announcements' => $result['data'], 'pagination' => $result['pagination']]);
    }

    /** GET /announcements/create */
    public function create(): void
    {
        $this->setTitle('Buat Pengumuman');
        $courses = [];
        if (has_role('admin')) {
            $courses = (new Course())->findAll('name', 'ASC');
        } elseif (has_role('dosen')) {
            $courses = (new Course())->getByDosen(Session::userId());
        }
        $this->render('announcements/create', ['pageTitle' => 'Buat Pengumuman', 'courses' => $courses]);
    }

    /** POST /announcements */
    public function store(): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $this->validate($data, ['title' => 'required|min:3', 'content' => 'required']);
        $this->model->create([
            'course_id' => $data['course_id'] ?: null,
            'user_id'   => Session::userId(),
            'title'     => $data['title'],
            'content'   => $data['content'],
            'is_pinned' => isset($data['is_pinned']) ? 1 : 0,
        ]);
        flash_success('Pengumuman berhasil dibuat.');
        $this->redirect(url('/announcements'));
    }

    /** GET /announcements/{id}/edit */
    public function edit(int $id): void
    {
        $announcement = $this->model->findById($id);
        if (!$announcement) { $this->redirect(url('/announcements')); return; }

        // Authorization: only creator or admin
        if (!has_role('admin') && $announcement['user_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->redirect(url('/announcements'));
            return;
        }

        $this->setTitle('Edit Pengumuman');
        $this->setBreadcrumbs([
            ['label' => 'Dashboard', 'url' => '/dashboard'],
            ['label' => 'Pengumuman', 'url' => '/announcements'],
            ['label' => 'Edit']
        ]);

        $courses = [];
        if (has_role('admin')) {
            $courses = (new Course())->findAll('name', 'ASC');
        } elseif (has_role('dosen')) {
            $courses = (new Course())->getByDosen(Session::userId());
        }

        $this->render('announcements/edit', [
            'pageTitle'    => 'Edit Pengumuman',
            'announcement' => $announcement,
            'courses'      => $courses,
        ]);
    }

    /** POST /announcements/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $this->validate($data, ['title' => 'required|min:3', 'content' => 'required']);

        $this->model->update($id, [
            'course_id' => $data['course_id'] ?: null,
            'title'     => $data['title'],
            'content'   => $data['content'],
            'is_pinned' => isset($data['is_pinned']) ? 1 : 0,
        ]);

        flash_success('Pengumuman berhasil diperbarui.');
        $this->redirect(url('/announcements'));
    }

    /** POST /announcements/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();
        $this->model->delete($id);
        flash_success('Pengumuman berhasil dihapus.');
        $this->redirect(url('/announcements'));
    }
}
