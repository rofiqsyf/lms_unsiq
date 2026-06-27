<?php
namespace App\Core;

/**
 * ===========================================
 * PSR-4 Autoloader
 * ===========================================
 * Otomatis memuat class berdasarkan namespace.
 * Sesuai materi §2.5 Autoloading PSR-4.
 * 
 * Mapping: App\Controllers\AuthController -> app/Controllers/AuthController.php
 */
class Autoloader
{
    /**
     * Namespace prefix => base directory mappings
     */
    private static array $prefixes = [];

    /**
     * Register the autoloader with SPL
     */
    public static function register(): void
    {
        // Register default App namespace
        self::addNamespace('App', APP_PATH);

        // Register SPL autoload
        spl_autoload_register([self::class, 'loadClass']);
    }

    /**
     * Add a namespace-to-directory mapping
     */
    public static function addNamespace(string $prefix, string $baseDir): void
    {
        // Normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        // Normalize base directory with trailing separator
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // Store mapping
        self::$prefixes[$prefix] = $baseDir;
    }

    /**
     * Load class file based on fully-qualified class name
     * 
     * @param string $class Fully-qualified class name (e.g., App\Controllers\AuthController)
     * @return bool True if file was loaded, false otherwise
     */
    public static function loadClass(string $class): bool
    {
        // Iterate through registered namespace prefixes
        foreach (self::$prefixes as $prefix => $baseDir) {
            // Check if class uses this namespace prefix
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                continue;
            }

            // Get relative class name
            $relativeClass = substr($class, $len);

            // Convert namespace separators to directory separators
            $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            // If file exists, require it
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }

        return false;
    }
}
