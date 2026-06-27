<?php
namespace App\Controllers;

use App\Core\Session;
use App\Core\Validator;
use App\Core\CSRF;

/**
 * ===========================================
 * BaseController - Abstract Controller
 * ===========================================
 * Sesuai materi §2.7: Menyediakan method umum
 * untuk render view, redirect, JSON response, dll.
 */
abstract class BaseController
{
    /**
     * Render a view with optional layout
     * 
     * @param string $view   View path relative to Views/ (e.g., 'users/index')
     * @param array  $data   Data to pass to the view
     * @param string $layout Layout file (null for no layout)
     */
    protected function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        // Extract data to variables
        extract($data);

        // Add common data
        $currentUser = Session::user();
        $errors = Session::getErrors();
        $flashMessages = Session::getFlash();


        // Build view file path
        $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View [{$view}] not found at [{$viewFile}].");
        }

        if ($layout) {
            // Capture view content for layout
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            // Render layout with content
            $layoutFile = VIEWS_PATH . '/' . str_replace('.', '/', $layout) . '.php';
            if (!file_exists($layoutFile)) {
                throw new \RuntimeException("Layout [{$layout}] not found.");
            }
            require $layoutFile;
        } else {
            // Render view without layout
            require $viewFile;
        }

        // Clear old input after rendering
        Session::clearOldInput();
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Redirect back to previous page
     */
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/dashboard');
        header('Location: ' . $referer);
        exit;
    }

    /**
     * Return JSON response
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Validate input data
     * If validation fails, redirects back with errors and old input.
     * 
     * @param array $data  Input data
     * @param array $rules Validation rules
     * @return array Validated data
     */
    protected function validate(array $data, array $rules): array
    {
        $validator = new Validator($data);

        if (!$validator->validate($rules)) {
            Session::setErrors($validator->errors());
            Session::setOldInput($data);
            $this->back();
        }

        return $data;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCSRF(): void
    {
        $token = $_POST['_csrf_token'] ?? '';
        if (!CSRF::validateToken($token)) {
            Session::flash('error', 'Token keamanan tidak valid. Silakan coba lagi.');
            $this->back();
        }
    }

    /**
     * Get POST input
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get all POST input
     */
    protected function allInput(): array
    {
        return $_POST;
    }

    /**
     * Get GET query parameter
     */
    protected function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get current page number from query string
     */
    protected function getPage(): int
    {
        return max(1, (int) ($this->query('page', 1)));
    }

    /**
     * Set page title (for layouts)
     */
    protected function setTitle(string $title): void
    {
        $GLOBALS['pageTitle'] = $title;
    }

    /**
     * Set breadcrumbs
     * @param array $items [['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Users']]
     */
    protected function setBreadcrumbs(array $items): void
    {
        $GLOBALS['breadcrumbs'] = $items;
    }
}
