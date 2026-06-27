<?php
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
// No vendor autoload
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/app/Core/Router.php';

// Try to auto-load controllers and base classes to avoid class not found errors
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

// Mock Router to capture routes
class AuditRouter {
    public $routes = [];
    public function get($route, $action, $middleware = []) { $this->routes[] = ['GET', $route, $action]; }
    public function post($route, $action, $middleware = []) { $this->routes[] = ['POST', $route, $action]; }
    public function put($route, $action, $middleware = []) { $this->routes[] = ['PUT', $route, $action]; }
    public function delete($route, $action, $middleware = []) { $this->routes[] = ['DELETE', $route, $action]; }
}

$router = new AuditRouter();
require __DIR__ . '/routes/web.php';

$errors = [];
foreach ($router->routes as $r) {
    $action = $r[2];
    if (is_array($action)) {
        $controller = $action[0];
        $method = $action[1];
        if (!class_exists($controller)) {
            $errors[] = "Class not found: $controller (Route: {$r[0]} {$r[1]})";
        } elseif (!method_exists($controller, $method)) {
            $errors[] = "Method not found: $controller::$method (Route: {$r[0]} {$r[1]})";
        }
    }
}

if (empty($errors)) {
    echo "Routing Audit: All routes are valid.\n";
} else {
    echo "Routing Audit Errors:\n";
    echo implode("\n", $errors) . "\n";
}
