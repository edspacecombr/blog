<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\App;
use App\Database\Connection;
use App\Controllers\AuthController;

$app = App::getInstance();

// Auth routes
$app->post('/api/v1/auth/register', [new AuthController(), 'register']);
$app->post('/api/v1/auth/login', [new AuthController(), 'login']);
$app->get('/api/v1/auth/validate', [new AuthController(), 'validate']);

// Health check
$app->get('/api/v1/health', function () {
    echo json_encode(['status' => 'ok', 'timestamp' => date('c')]);
});

$app->run();
