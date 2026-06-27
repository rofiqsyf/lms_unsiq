<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Session;
use App\Core\FileUploader;

/**
 * User Management Controller (Admin only)
 */
class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /** GET /users */
    public function index(): void
    {
        $this->setTitle('Kelola Users');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Users']]);

        $page   = $this->getPage();
        $search = $this->query('search', '');
        $role   = $this->query('role', '');

        $result = $this->userModel->paginateUsers($page, 10, $search, $role, url('/users'));

        $this->render('users/index', [
            'pageTitle'  => 'Kelola Users',
            'users'      => $result['data'],
            'pagination' => $result['pagination'],
            'search'     => $search,
            'role'       => $role,
        ]);
    }

    /** GET /users/create */
    public function create(): void
    {
        $this->setTitle('Tambah User');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Users', 'url' => '/users'], ['label' => 'Tambah']]);
        $this->render('users/create', ['pageTitle' => 'Tambah User']);
    }

    /** POST /users */
    public function store(): void
    {
        $this->validateCSRF();
        $data = $this->allInput();

        $this->validate($data, [
            'name'     => 'required|min:3|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,dosen,mahasiswa',
        ]);

        $this->userModel->createUser([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => $data['password'],
            'role'      => $data['role'],
            'nim_nidn'  => $data['nim_nidn'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'is_active' => 1,
        ]);

        flash_success('User berhasil ditambahkan.');
        $this->redirect(url('/users'));
    }

    /** GET /users/{id} */
    public function show(int $id): void
    {
        $user = $this->userModel->findById($id);
        if (!$user) { $this->redirect(url('/users')); return; }

        $this->setTitle('Detail User');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Users', 'url' => '/users'], ['label' => $user['name']]]);
        $this->render('users/show', ['pageTitle' => 'Detail User', 'user' => $user]);
    }

    /** GET /users/{id}/edit */
    public function edit(int $id): void
    {
        $user = $this->userModel->findById($id);
        if (!$user) { $this->redirect(url('/users')); return; }

        $this->setTitle('Edit User');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Users', 'url' => '/users'], ['label' => 'Edit']]);
        $this->render('users/edit', ['pageTitle' => 'Edit User', 'user' => $user]);
    }

    /** POST /users/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();
        $data = $this->allInput();

        $this->validate($data, [
            'name'  => 'required|min:3|max:100',
            'email' => "required|email|unique:users,email,{$id}",
            'role'  => 'required|in:admin,dosen,mahasiswa',
        ]);

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'nim_nidn'  => $data['nim_nidn'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ];

        // Update password only if provided
        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        // Handle avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader('avatars');
            $uploader->setAllowedTypes(['jpg', 'jpeg', 'png', 'gif']);
            $uploader->setMaxSize(2 * 1024 * 1024); // 2MB
            $path = $uploader->upload('avatar');
            if ($path) {
                $updateData['avatar'] = $path;
            }
        }

        $this->userModel->update($id, $updateData);
        flash_success('User berhasil diperbarui.');
        $this->redirect(url('/users'));
    }

    /** POST /users/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();

        // Prevent self-deletion
        if ($id == Session::userId()) {
            flash_error('Anda tidak dapat menghapus akun sendiri.');
            $this->redirect(url('/users'));
            return;
        }

        $this->userModel->delete($id);
        flash_success('User berhasil dihapus.');
        $this->redirect(url('/users'));
    }
}
