<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Session;
use App\Core\FileUploader;

class ProfileController extends BaseController
{
    /** GET /profile */
    public function edit(): void
    {
        $user = (new User())->findById(Session::userId());
        $this->setTitle('Profil Saya');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Profil']]);
        $this->render('profile/edit', ['pageTitle' => 'Profil Saya', 'user' => $user]);
    }

    /** POST /profile/update */
    public function update(): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $userId = Session::userId();

        $this->validate($data, [
            'name'  => 'required|min:3|max:100',
            'email' => "required|email|unique:users,email,{$userId}",
        ]);

        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'bio'   => $data['bio'] ?? '',
        ];

        if (!empty($data['password'])) {
            if (strlen($data['password']) < 6) {
                flash_error('Password minimal 6 karakter.');
                $this->back();
                return;
            }
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploader = new FileUploader('avatars');
            $uploader->setAllowedTypes(['jpg', 'jpeg', 'png']);
            $uploader->setMaxSize(2 * 1024 * 1024);
            $path = $uploader->upload('avatar');
            if ($path) $updateData['avatar'] = $path;
        }

        $userModel = new User();
        $userModel->update($userId, $updateData);

        // Update session data
        $updatedUser = $userModel->findById($userId);
        unset($updatedUser['password']);
        Session::set('user', $updatedUser);

        flash_success('Profil berhasil diperbarui.');
        $this->redirect(url('/profile'));
    }
}
