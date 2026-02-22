<?php

namespace App;

use Dotenv\Dotenv;

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
        if (file_exists(__DIR__ . '/../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
            $dotenv->load();
        }
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

    public function run(): void
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: ' . ($_ENV['FRONTEND_URL'] ?? 'http://localhost:3000'));
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace(dirname($_SERVER['PHP_SELF']), '', $path) ?: '/';

        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
        }
    }
}
