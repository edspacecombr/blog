<?php

namespace App\Database;

use PDO;
use PDOException;
use Exception;

class Connection
{
    private static ?PDO $connection = null;

    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            self::connect();
        }
        return self::$connection;
    }

    private static function connect(): void
    {
        try {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $database = $_ENV['DB_DATABASE'] ?? 'blog_platform';
            $user = $_ENV['DB_USER'] ?? 'blog_user';
            $password = $_ENV['DB_PASSWORD'] ?? '';
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
            
            self::$connection = new PDO(
                $dsn,
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function close(): void
    {
        self::$connection = null;
    }
}
