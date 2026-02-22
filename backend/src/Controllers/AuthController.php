<?php

namespace App\Controllers;

use App\Database\Connection;

class AuthController
{
    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['email'], $data['password'], $data['name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $authService = new \App\Auth\AuthService(Connection::getInstance());
        $result = $authService->register(
            $data['email'],
            $data['name'],
            $data['password'],
            $data['role'] ?? 'author'
        );

        http_response_code($result['success'] ? 201 : 400);
        echo json_encode($result);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing email or password']);
            return;
        }

        $authService = new \App\Auth\AuthService(Connection::getInstance());
        $result = $authService->login($data['email'], $data['password']);

        http_response_code($result['success'] ? 200 : 401);
        echo json_encode($result);
    }

    public function validate(): void
    {
        $authService = new \App\Auth\AuthService(Connection::getInstance());
        $jwt = new \App\Auth\JWTAuth();
        $token = $jwt->getTokenFromRequest();

        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Token not found']);
            return;
        }

        $result = $authService->validateToken($token);
        http_response_code($result['valid'] ? 200 : 401);
        echo json_encode($result);
    }
}
