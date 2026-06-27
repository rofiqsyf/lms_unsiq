<?php
define('BASE_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');
require __DIR__ . '/config/app.php';
require __DIR__ . '/app/Core/Database.php';

try {
    $db = \App\Core\Database::getInstance()->getConnection();
    
    $sql = file_get_contents(__DIR__ . '/database/migrations/004_phase4_tables.sql');
    
    // Remove comments
    $sql = preg_replace('/--.*$/m', '', $sql);
    
    $queries = explode(';', $sql);
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $db->exec($query);
        }
    }
    
    echo "Migration Phase 3 successful!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
