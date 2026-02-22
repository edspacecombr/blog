<?php

namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use PDO;

class JWTAuth
{
    private string $secret;
    private string $algorithm;
    private int $expiration;

    public function __construct()
    {
        $this->secret = $_ENV['JWT_SECRET'] ?? 'default_secret_change_me';
        $this->algorithm = $_ENV['JWT_ALGORITHM'] ?? 'HS256';
        $this->expiration = (int)($_ENV['JWT_EXPIRATION'] ?? 86400);
    }

    public function generateToken(int $userId, string $email, string $role): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->expiration;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'userId' => $userId,
            'email' => $email,
            'role' => $role,
        ];

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    public function verifyToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            return (array)$decoded;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getTokenFromRequest(): ?string
    {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
                return $matches[1];
            }
        }
        
        return $_GET['token'] ?? null;
    }
}
