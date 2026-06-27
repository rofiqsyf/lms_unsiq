<?php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/app/Controllers');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/\.php$/', RegexIterator::GET_MATCH);

$errors = [];

foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Find all render calls
    if (preg_match_all('/\$this->render\(\s*[\'"]([a-zA-Z0-9_\-\/]+)[\'"]/', $content, $matches)) {
        foreach ($matches[1] as $view) {
            $viewPath = __DIR__ . '/app/Views/' . $view . '.php';
            if (!file_exists($viewPath)) {
                $errors[] = "View missing: $view.php (Called in " . basename($path) . ")";
            }
        }
    }
}

if (empty($errors)) {
    echo "View Audit: All rendered views exist.\n";
} else {
    echo "View Audit Errors:\n";
    echo implode("\n", $errors) . "\n";
}
