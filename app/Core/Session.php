<?php
namespace App\Core;

/**
 * ===========================================
 * Session Manager
 * ===========================================
 * Mengelola session data, flash messages, dan CSRF tokens.
 */
class Session
{
    /**
     * Set a session value
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session key
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the entire session
     */
    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * Regenerate session ID (security measure pada login)
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    // ===========================================
    // Flash Messages
    // ===========================================

    /**
     * Set flash message (hanya tersedia di NEXT request)
     * 
     * @param string $type    Message type: success, error, warning, info
     * @param string $message The message content
     */
    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type][] = $message;
    }

    /**
     * Get and clear flash messages
     */
    public static function getFlash(string $type = null): array
    {
        if ($type !== null) {
            $messages = $_SESSION['_flash'][$type] ?? [];
            unset($_SESSION['_flash'][$type]);
            return $messages;
        }

        $messages = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $messages;
    }

    /**
     * Check if flash messages exist
     */
    public static function hasFlash(string $type = null): bool
    {
        if ($type !== null) {
            return !empty($_SESSION['_flash'][$type]);
        }
        return !empty($_SESSION['_flash']);
    }

    // ===========================================
    // Old Input (preserve form data after validation failure)
    // ===========================================

    /**
     * Store old input data
     */
    public static function setOldInput(array $data): void
    {
        $_SESSION['_old_input'] = $data;
    }

    /**
     * Get old input value
     */
    public static function getOldInput(string $key, mixed $default = ''): mixed
    {
        $value = $_SESSION['_old_input'][$key] ?? $default;
        return $value;
    }

    /**
     * Clear old input
     */
    public static function clearOldInput(): void
    {
        unset($_SESSION['_old_input']);
    }

    // ===========================================
    // Validation Errors
    // ===========================================

    /**
     * Store validation errors
     */
    public static function setErrors(array $errors): void
    {
        $_SESSION['_errors'] = $errors;
    }

    /**
     * Get validation errors
     */
    public static function getErrors(): array
    {
        $errors = $_SESSION['_errors'] ?? [];
        unset($_SESSION['_errors']);
        return $errors;
    }

    /**
     * Get a specific field error
     */
    public static function getError(string $field): string
    {
        $errors = $_SESSION['_errors'] ?? [];
        return $errors[$field] ?? '';
    }

    /**
     * Check if there are validation errors
     */
    public static function hasErrors(): bool
    {
        return !empty($_SESSION['_errors']);
    }

    // ===========================================
    // Auth Helpers
    // ===========================================

    /**
     * Get logged-in user data
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    /**
     * Get logged-in user's role
     */
    public static function userRole(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    /**
     * Get logged-in user's ID
     */
    public static function userId(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }
}
