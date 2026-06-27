<?php
/**
 * ===========================================
 * LMS UNSIQ - Global Helper Functions
 * ===========================================
 * Fungsi-fungsi utility yang tersedia di seluruh aplikasi.
 */

use App\Core\Session;
use App\Core\CSRF;

/**
 * Generate full URL
 */
function url(string $path = ''): string
{
    $base = rtrim(APP_URL, '/');
    $path = ltrim($path, '/');
    return $base . '/' . $path;
}

/**
 * Generate asset URL (CSS, JS, images)
 */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Generate upload file URL
 */
function upload_url(string $path): string
{
    if (empty($path)) {
        return asset('images/default-avatar.png');
    }
    return url('uploads/' . ltrim($path, '/'));
}

/**
 * Redirect to URL
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Redirect back to previous page
 */
function back(): void
{
    $referer = $_SERVER['HTTP_REFERER'] ?? url('/dashboard');
    header('Location: ' . $referer);
    exit;
}

/**
 * Get old input value (after validation failure)
 */
function old(string $key, mixed $default = ''): mixed
{
    return Session::getOldInput($key, $default);
}

/**
 * Generate CSRF hidden field
 */
function csrf_field(): string
{
    return CSRF::field();
}

/**
 * Get CSRF token
 */
function csrf_token(): string
{
    return CSRF::getToken();
}

/**
 * Escape HTML output
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Dump and die (debug helper)
 */
function dd(mixed ...$vars): void
{
    echo '<pre style="background:#1a1d2e;color:#f1f5f9;padding:20px;border-radius:8px;font-family:monospace;">';
    foreach ($vars as $var) {
        var_dump($var);
        echo "\n---\n";
    }
    echo '</pre>';
    die();
}

/**
 * Format date to Indonesian format
 */
function format_date(?string $date, string $format = 'd M Y'): string
{
    if (empty($date)) return '-';
    
    $months = [
        'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr',
        'May' => 'Mei', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Agu',
        'Sep' => 'Sep', 'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des',
    ];

    $formatted = date($format, strtotime($date));
    return strtr($formatted, $months);
}

/**
 * Format datetime to relative time (e.g., "2 jam lalu")
 */
function time_ago(?string $datetime): string
{
    if (empty($datetime)) return '-';

    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) return 'Baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
    if ($diff < 604800) return floor($diff / 86400) . ' hari lalu';
    if ($diff < 2592000) return floor($diff / 604800) . ' minggu lalu';
    
    return format_date($datetime);
}

/**
 * Format file size to human readable
 */
function format_filesize(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 1) . ' ' . $units[$i];
}

/**
 * Truncate text
 */
function str_limit(?string $text, int $limit = 100, string $end = '...'): string
{
    if (empty($text)) return '';
    if (mb_strlen($text) <= $limit) return $text;
    return mb_substr($text, 0, $limit) . $end;
}

/**
 * Generate slug from text
 */
function slugify(string $text): string
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Check if current URL matches
 */
function is_active(string $path): string
{
    $currentUri = $_SERVER['REQUEST_URI'] ?? '';
    $basePath = '/project_lms/public';
    $currentPath = str_replace($basePath, '', parse_url($currentUri, PHP_URL_PATH));
    
    if ($path === '/') {
        return ($currentPath === '/' || $currentPath === '') ? 'active' : '';
    }
    
    return str_starts_with($currentPath, $path) ? 'active' : '';
}

/**
 * Get current user data
 */
function auth(): ?array
{
    return Session::user();
}

/**
 * Check if user has role
 */
function has_role(string ...$roles): bool
{
    $userRole = Session::userRole();
    return in_array($userRole, $roles);
}

/**
 * Flash message shortcuts
 */
function flash_success(string $message): void
{
    Session::flash('success', $message);
}

function flash_error(string $message): void
{
    Session::flash('error', $message);
}

function flash_warning(string $message): void
{
    Session::flash('warning', $message);
}

function flash_info(string $message): void
{
    Session::flash('info', $message);
}

/**
 * Get status badge HTML
 */
function status_badge(string $status): string
{
    $map = [
        'active'      => ['Aktif', 'success'],
        'inactive'    => ['Nonaktif', 'danger'],
        'draft'       => ['Draft', 'warning'],
        'published'   => ['Published', 'success'],
        'archived'    => ['Archived', 'secondary'],
        'submitted'   => ['Submitted', 'info'],
        'graded'      => ['Dinilai', 'success'],
        'late'        => ['Terlambat', 'danger'],
        'completed'   => ['Selesai', 'success'],
        'resubmitted' => ['Dikumpulkan Ulang', 'info'],
        'in_progress' => ['Berlangsung', 'warning'],
        'dropped'     => ['Keluar', 'danger'],
        'timed_out'   => ['Waktu Habis', 'danger'],
    ];

    $info = $map[$status] ?? [ucfirst($status), 'secondary'];
    return '<span class="badge badge-' . $info[1] . '">' . e($info[0]) . '</span>';
}
