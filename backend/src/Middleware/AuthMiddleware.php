<?php

namespace App\Middleware;

use App\Auth\JWTAuth;

class AuthMiddleware
{
    private JWTAuth $jwt;

    public function __construct()
    {
        $this->jwt = new JWTAuth();
    }

    public function authenticate(): array
    {
        $token = $this->jwt->getTokenFromRequest();

        if (!$token) {
            return [
                'authenticated' => false,
                'message' => 'Token not found'
            ];
        }

        $payload = $this->jwt->verifyToken($token);

        if (!$payload) {
            return [
                'authenticated' => false,
                'message' => 'Invalid or expired token'
            ];
        }

        return [
            'authenticated' => true,
            'user' => $payload
        ];
    }

    public function checkRole(string $requiredRole, string $userRole): bool
    {
        $roles = ['admin' => 3, 'editor' => 2, 'author' => 1];
        
        return ($roles[$userRole] ?? 0) >= ($roles[$requiredRole] ?? 0);
    }
}
