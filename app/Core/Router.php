<?php
namespace App\Core;

/**
 * ===========================================
 * Router - URL Routing Engine
 * ===========================================
 * Sesuai materi §2.4 Router PHP.
 * Memetakan URL + HTTP Method ke Controller + Method.
 * Mendukung URL parameters {id}, middleware, dan grouped routes.
 */
class Router
{
    /**
     * Registered routes grouped by HTTP method
     * @var array<string, array>
     */
    private array $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'DELETE' => [],
    ];

    /**
     * Register a GET route
     */
    public function get(string $uri, array $action, array $middleware = []): self
    {
        return $this->addRoute('GET', $uri, $action, $middleware);
    }

    /**
     * Register a POST route
     */
    public function post(string $uri, array $action, array $middleware = []): self
    {
        return $this->addRoute('POST', $uri, $action, $middleware);
    }

    /**
     * Register a PUT route
     */
    public function put(string $uri, array $action, array $middleware = []): self
    {
        return $this->addRoute('PUT', $uri, $action, $middleware);
    }

    /**
     * Register a DELETE route
     */
    public function delete(string $uri, array $action, array $middleware = []): self
    {
        return $this->addRoute('DELETE', $uri, $action, $middleware);
    }

    /**
     * Add route to the route table
     */
    private function addRoute(string $method, string $uri, array $action, array $middleware = []): self
    {
        $this->routes[$method][] = [
            'uri'        => $uri,
            'controller' => $action[0],
            'method'     => $action[1],
            'middleware'  => $middleware,
        ];
        return $this;
    }

    /**
     * Dispatch the current request to matching route
     * 
     * @param string $requestMethod HTTP method (GET, POST, etc.)
     * @param string $requestUri    Request URI path
     * @throws Exceptions\NotFoundException If no matching route found
     */
    public function dispatch(string $requestMethod, string $requestUri): void
    {
        // Support method override via hidden input (_method)
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

        // Search for matching route
        $routes = $this->routes[$requestMethod] ?? [];

        foreach ($routes as $route) {
            $params = $this->matchRoute($route['uri'], $requestUri);

            if ($params !== false) {
                // Run middleware pipeline
                $this->runMiddleware($route['middleware']);

                // Instantiate controller and call method
                $controllerClass = $route['controller'];
                $methodName      = $route['method'];

                if (!class_exists($controllerClass)) {
                    throw new \RuntimeException("Controller [{$controllerClass}] not found.");
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $methodName)) {
                    throw new \RuntimeException("Method [{$methodName}] not found in [{$controllerClass}].");
                }

                // Call controller method with extracted params
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }

        // No matching route found
        throw new Exceptions\NotFoundException("Route [{$requestMethod} {$requestUri}] not found.");
    }

    /**
     * Match a route pattern against a URI
     * 
     * @param string $routeUri    Route pattern (e.g., /products/{id}/edit)
     * @param string $requestUri  Actual request URI
     * @return array|false        Extracted parameters or false if no match
     */
    private function matchRoute(string $routeUri, string $requestUri): array|false
    {
        // Convert route pattern to regex
        // {id} -> ([a-zA-Z0-9_-]+)
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([a-zA-Z0-9_-]+)', $routeUri);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            // Remove the full match, keep only captured groups
            array_shift($matches);
            return $matches;
        }

        return false;
    }

    /**
     * Run middleware pipeline
     * 
     * @param array $middlewares List of middleware identifiers
     */
    private function runMiddleware(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            // Parse middleware:param format (e.g., 'role:admin,dosen')
            $params = [];
            if (str_contains($middleware, ':')) {
                [$middleware, $paramString] = explode(':', $middleware, 2);
                $params = explode(',', $paramString);
            }

            // Map middleware name to class
            $middlewareMap = [
                'auth'     => \App\Middleware\AuthMiddleware::class,
                'guest'    => \App\Middleware\GuestMiddleware::class,
                'role'     => \App\Middleware\RoleMiddleware::class,
                'api_auth' => \App\Middleware\ApiAuthMiddleware::class,
                'csrf'     => \App\Middleware\CSRFMiddleware::class,
            ];

            if (!isset($middlewareMap[$middleware])) {
                throw new \RuntimeException("Middleware [{$middleware}] not registered.");
            }

            $middlewareClass = $middlewareMap[$middleware];
            $instance = new $middlewareClass();
            $instance->handle($params);
        }
    }

    /**
     * Generate URL for a route pattern (helper)
     */
    public static function url(string $path = ''): string
    {
        $baseUrl = rtrim(APP_URL, '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}
