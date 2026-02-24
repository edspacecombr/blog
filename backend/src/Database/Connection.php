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
            $host = getenv('DB_HOST') ?: 'localhost';
            $port = getenv('DB_PORT') ?: 5432;
            $database = getenv('DB_DATABASE') ?: 'blog_platform';
            $user = getenv('DB_USER') ?: 'blog_user';
            $password = getenv('DB_PASSWORD') ?: '';
            $sslmode = getenv('DB_SSLMODE') ?: 'prefer';

            // PostgreSQL DSN
            $dsn = "pgsql:host=$host;port=$port;dbname=$database;sslmode=$sslmode";
            
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

