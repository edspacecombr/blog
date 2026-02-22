<?php

namespace App\Auth;

use PDO;
use Exception;

class AuthService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function register(string $email, string $name, string $password, string $role = 'author'): array
    {
        try {
            // Check if user exists
            $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                throw new Exception('User already exists');
            }

            // Hash password
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // Insert user
            $stmt = $this->db->prepare('
                INSERT INTO users (email, password_hash, name, role, status) 
                VALUES (?, ?, ?, ?, ?)
            ');
            
            $stmt->execute([
                $email,
                $passwordHash,
                $name,
                $role,
                'active'
            ]);

            $userId = (int)$this->db->lastInsertId();

            return [
                'success' => true,
                'message' => 'User registered successfully',
                'user_id' => $userId
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function login(string $email, string $password): array
    {
        try {
            $stmt = $this->db->prepare('
                SELECT id, email, password_hash, name, role, status 
                FROM users 
                WHERE email = ?
            ');
            
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user) {
                throw new Exception('User not found');
            }

            if ($user['status'] !== 'active') {
                throw new Exception('User account is not active');
            }

            if (!password_verify($password, $user['password_hash'])) {
                throw new Exception('Invalid password');
            }

            // Update last login
            $updateStmt = $this->db->prepare('
                UPDATE users SET last_login_at = CURRENT_TIMESTAMP WHERE id = ?
            ');
            $updateStmt->execute([$user['id']]);

            $jwt = new JWTAuth();
            $token = $jwt->generateToken((int)$user['id'], $user['email'], $user['role']);

            return [
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => (int)$user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function validateToken(string $token): array
    {
        $jwt = new JWTAuth();
        $payload = $jwt->verifyToken($token);

        if (!$payload) {
            return [
                'valid' => false,
                'message' => 'Invalid or expired token'
            ];
        }

        return [
            'valid' => true,
            'user' => $payload
        ];
    }
}
