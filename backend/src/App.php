<?php

namespace App;

use Dotenv\Dotenv as VlucasDotenv;

class App
{
    private static ?self $instance = null;
    private array $routes = [];
    private string $basePath = '';

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->loadEnv();
        $this->registerErrorHandlers();
    }

    private function loadEnv(): void
    {
        // .env is already loaded in public/index.php before App initialization
    }

    private function registerErrorHandlers(): void
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            error_log("[$errno] $errstr in $errfile:$errline");
            return true;
        });

        set_exception_handler(function ($exception) {
            error_log('Exception: ' . $exception->getMessage());
            http_response_code(500);
            echo json_encode([
                'error' => 'Internal server error',
                'message' => $_ENV['APP_DEBUG'] ? $exception->getMessage() : ''
            ]);
        });
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function put(string $path, callable $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function patch(string $path, callable $handler): void
    {
        $this->routes['PATCH'][$path] = $handler;
    }

    private function matchRoute(string $method, string $path): array
    {
        if (isset($this->routes[$method][$path])) {
            return ['handler' => $this->routes[$method][$path], 'params' => []];
        }

        // Try pattern matching for routes with parameters
        foreach ($this->routes[$method] ?? [] as $pattern => $handler) {
            $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
            $regex = "#^{$regex}$#";
            
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, fn($k) => is_string($k), ARRAY_FILTER_USE_KEY);
                return ['handler' => $handler, 'params' => $params];
            }
        }

        return ['handler' => null, 'params' => []];
    }

    public function run(): void
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: ' . ($_ENV['FRONTEND_URL'] ?? 'http://localhost:3000'));
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace(dirname($_SERVER['PHP_SELF']), '', $path) ?: '/';

        $route = $this->matchRoute($method, $path);
        
        if ($route['handler']) {
            if (is_array($route['handler']) && count($route['handler']) === 2) {
                [$controller, $action] = $route['handler'];
                $params = array_values($route['params']);
                call_user_func_array([$controller, $action], $params);
            } else {
                call_user_func($route['handler']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
        }
    }
}
