<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Session;
use App\Core\CSRF;
use App\Core\Logger;

/**
 * Authentication Controller
 * Handles login, logout, session management
 */
class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Show login form
     * GET /login
     */
    public function showLogin(): void
    {
        $this->render('auth/login', [
            'pageTitle' => 'Login'
        ], null); // No layout for login page
    }

    /**
     * Process login
     * POST /login
     */
    public function login(): void
    {
        $this->validateCSRF();

        $email    = trim($this->input('email', ''));
        $password = $this->input('password', '');

        // Validate input
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Email dan password wajib diisi.');
            Session::setOldInput(['email' => $email]);
            $this->redirect(url('/login'));
            return;
        }

        // Attempt authentication
        $user = $this->userModel->authenticate($email, $password);

        if (!$user) {
            Session::flash('error', 'Email atau password salah.');
            Session::setOldInput(['email' => $email]);
            $this->redirect(url('/login'));
            return;
        }

        // Regenerate session ID (security)
        Session::regenerate();

        // Store user in session
        Session::set('user', $user);

        // Flash success message
        Logger::log('login', 'user', $user['id'], 'User logged in successfully');
        Session::flash('success', 'Selamat datang kembali, ' . $user['name'] . '!');

        // Redirect to dashboard
        $this->redirect(url('/dashboard'));
    }

    /** GET /register */
    public function showRegister(): void
    {
        $this->render('auth/register', ['pageTitle' => 'Registrasi'], null);
    }

    /** POST /register */
    public function register(): void
    {
        $this->validateCSRF();
        $data = $this->allInput();

        $this->validate($data, [
            'name'     => 'required|min:3|max:100',
            'email'    => 'required|email|unique:users,email',
            'nim_nidn' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $userModel = new \App\Models\User();
        $userId = $userModel->createUser([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'nim_nidn'  => $data['nim_nidn'],
            'password'  => $data['password'],
            'role'      => 'mahasiswa',
            'is_active' => 1,
        ]);

        // Auto login
        $user = $userModel->findById($userId);
        Session::regenerate();
        Session::set('user', $user);

        flash_success('Registrasi berhasil! Selamat datang di ' . APP_NAME);
        $this->redirect(url('/dashboard'));
    }

    /** GET /forgot-password */
    public function showForgotPassword(): void
    {
        $this->render('auth/forgot-password', ['pageTitle' => 'Lupa Password'], null);
    }

    /** POST /forgot-password */
    public function forgotPassword(): void
    {
        $this->validateCSRF();
        $email = $this->input('email', '');
        
        $this->validate(['email' => $email], ['email' => 'required|email']);

        $userModel = new \App\Models\User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            $resetModel = new \App\Models\PasswordReset();
            $token = $resetModel->createToken($email);
            $resetLink = url("/reset-password/{$token}");
            
            // Menggunakan Mailer
            $mailer = new \App\Core\Mailer();
            $subject = 'Permintaan Reset Password - ' . APP_NAME;
            $body = "Halo {$user['name']},\n\n";
            $body .= "Kami menerima permintaan reset password untuk akun Anda.\n";
            $body .= "Silakan klik link berikut untuk mengatur password baru Anda:\n";
            $body .= "{$resetLink}\n\n";
            $body .= "Link ini akan kedaluwarsa dalam 1 jam.\n";
            $body .= "Jika Anda tidak meminta reset password, abaikan email ini.\n\n";
            $body .= "Terima kasih,\nTim " . APP_NAME;

            $mailer->send($email, $subject, $body);

            Session::flash('success', 'Instruksi reset password telah dikirim ke email Anda. (Development Mode: Cek storage/logs/emails.log)');
        } else {
            // Do not reveal if email exists or not
            Session::flash('success', 'Jika email terdaftar, instruksi reset telah dikirim.');
        }

        $this->redirect(url('/forgot-password'));
    }

    /** GET /reset-password/{token} */
    public function showResetPassword(string $token): void
    {
        $resetModel = new \App\Models\PasswordReset();
        $resetModel->cleanExpired();
        
        $validToken = $resetModel->findValidToken($token);

        if (!$validToken) {
            flash_error('Token reset password tidak valid atau sudah kedaluwarsa.');
            $this->redirect(url('/login'));
            return;
        }

        $this->render('auth/reset-password', [
            'pageTitle' => 'Reset Password',
            'token'     => $token,
            'email'     => $validToken['email']
        ], null);
    }

    /** POST /reset-password/{token} */
    public function resetPassword(string $token): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        
        $resetModel = new \App\Models\PasswordReset();
        $validToken = $resetModel->findValidToken($token);

        if (!$validToken) {
            flash_error('Token reset password tidak valid atau sudah kedaluwarsa.');
            $this->redirect(url('/login'));
            return;
        }

        $this->validate($data, [
            'password' => 'required|min:6|confirmed'
        ]);

        $userModel = new \App\Models\User();
        $user = $userModel->findByEmail($validToken['email']);

        if ($user) {
            $userModel->updatePassword($user['id'], $data['password']);
            $resetModel->markUsed($token);
            flash_success('Password berhasil diubah! Silakan login dengan password baru.');
            $this->redirect(url('/login'));
        } else {
            flash_error('User tidak ditemukan.');
            $this->redirect(url('/forgot-password'));
        }
    }

    /**
     * Logout
     * GET /logout
     */
    public function logout(): void
    {
        Session::destroy();
        session_start();
        Session::flash('success', 'Anda berhasil logout.');
        $this->redirect(url('/login'));
    }
}
