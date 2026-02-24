<?php

header('Content-Type: application/json');

// Load .env
$env_file = __DIR__ . '/../.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// Simple autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// CORS headers
$allowed_origins = getenv('CORS_ALLOWED_ORIGINS') ?: 'http://localhost:3000';
$origins = array_map('trim', explode(',', $allowed_origins));

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
}
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Parse request
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Get body
$input = file_get_contents('php://input');
$data = !empty($input) ? json_decode($input, true) : $_GET;

// Database connection
try {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '5432';
    $db = getenv('DB_DATABASE') ?: 'blog_platform';
    $user = getenv('DB_USER') ?: 'blog_admin';
    $password = getenv('DB_PASSWORD') ?: '';

    $dsn = "pgsql:host={$host};port={$port};dbname={$db}";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Simple routing
if (preg_match('/^\/api\/v1\/health$/', $request_uri)) {
    echo json_encode([
        'status' => 'ok',
        'timestamp' => date('c')
    ]);
    exit;
}

// Posts endpoints
if (preg_match('/^\/api\/v1\/posts$/', $request_uri)) {
    $controller = new App\Controllers\PostController();

    if ($request_method === 'GET') {
        $result = $controller->index($_GET);
        echo json_encode($result);
    } elseif ($request_method === 'POST') {
        $result = $controller->store($data);
        echo json_encode($result);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
    exit;
}

if (preg_match('/^\/api\/v1\/posts\/(\d+)$/', $request_uri, $matches)) {
    $controller = new App\Controllers\PostController();
    $id = $matches[1];

    if ($request_method === 'GET') {
        $language = $_GET['language'] ?? 'pt';
        $result = $controller->show($id, $language);
        echo json_encode($result);
    } elseif ($request_method === 'PATCH' || $request_method === 'PUT') {
        $result = $controller->update($id, $data);
        echo json_encode($result);
    } elseif ($request_method === 'DELETE') {
        $result = $controller->destroy($id);
        echo json_encode($result);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
    exit;
}

// Media endpoints
if (preg_match('/^\/api\/v1\/media$/', $request_uri)) {
    $controller = new App\Controllers\MediaController($pdo);

    if ($request_method === 'GET') {
        $controller->index($pdo);
    } elseif ($request_method === 'POST') {
        $controller->store($pdo);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
    exit;
}

if (preg_match('/^\/api\/v1\/media\/(\d+)$/', $request_uri, $matches)) {
    $controller = new App\Controllers\MediaController($pdo);
    $id = $matches[1];

    if ($request_method === 'GET') {
        $controller->show($id, $pdo);
    } elseif ($request_method === 'DELETE') {
        $controller->destroy($id, $pdo);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
    exit;
}

if (preg_match('/^\/api\/v1\/media\/(\d+)\/alt-text$/', $request_uri, $matches)) {
    $controller = new App\Controllers\MediaController($pdo);
    $id = $matches[1];

    if ($request_method === 'PATCH' || $request_method === 'PUT') {
        $controller->updateAltText($id, $pdo);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
    exit;
}

// 404
http_response_code(404);
echo json_encode(['error' => 'Not found']);
