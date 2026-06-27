<?php
/**
 * ===========================================
 * LMS UNSIQ - Application Configuration
 * ===========================================
 * Load environment variables dan define constants
 */

// Load .env file
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remove surrounding quotes
            $value = trim($value, '"\'');
            // Set as environment variable
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}

/**
 * Helper function to get environment variable with default
 */
function env(string $key, $default = null)
{
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    // Convert string booleans
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'null':
        case '(null)':
            return null;
        case 'empty':
        case '(empty)':
            return '';
    }
    return $value;
}

// ===========================================
// Application Constants
// ===========================================
define('APP_NAME', env('APP_NAME', 'LMS UNSIQ'));
define('APP_ENV', env('APP_ENV', 'development'));
define('APP_DEBUG', env('APP_DEBUG', true));
define('APP_URL', env('APP_URL', 'http://localhost/project_lms'));
define('APP_TIMEZONE', env('APP_TIMEZONE', 'Asia/Jakarta'));

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// ===========================================
// Path Constants
// ===========================================
define('CONFIG_PATH', BASE_PATH . '/config');
define('ROUTES_PATH', BASE_PATH . '/routes');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('VIEWS_PATH', APP_PATH . '/Views');
define('UPLOAD_PATH', STORAGE_PATH . '/uploads');

// ===========================================
// Upload Constants
// ===========================================
define('UPLOAD_MAX_SIZE', (int) env('UPLOAD_MAX_SIZE', 10485760)); // 10MB
define('ALLOWED_FILE_TYPES', env('ALLOWED_FILE_TYPES', 'pdf,doc,docx,ppt,pptx,jpg,jpeg,png'));

// ===========================================
// Session Constants
// ===========================================
define('SESSION_LIFETIME', (int) env('SESSION_LIFETIME', 120)); // minutes
