<?php
namespace App\Core;

/**
 * ===========================================
 * CSRF Token Protection
 * ===========================================
 * Generate dan validate CSRF tokens untuk mencegah
 * Cross-Site Request Forgery attacks.
 */
class CSRF
{
    private const TOKEN_KEY = '_csrf_token';

    /**
     * Generate CSRF token (atau return existing jika sudah ada)
     */
    public static function generateToken(): string
    {
        if (!isset($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::TOKEN_KEY];
    }

    /**
     * Get current CSRF token
     */
    public static function getToken(): string
    {
        return self::generateToken();
    }

    /**
     * Validate submitted CSRF token
     */
    public static function validateToken(?string $token): bool
    {
        if (empty($token) || !isset($_SESSION[self::TOKEN_KEY])) {
            return false;
        }
        return hash_equals($_SESSION[self::TOKEN_KEY], $token);
    }

    /**
     * Generate hidden input field for forms
     */
    public static function field(): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Regenerate token (call after successful form submission)
     */
    public static function regenerate(): void
    {
        $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
    }
}
