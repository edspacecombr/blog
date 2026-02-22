<?php

namespace App\Cache;

use Redis;
use Exception;

class CacheService
{
    private static ?Redis $redis = null;
    private string $prefix;

    public function __construct(string $prefix = 'blog:')
    {
        $this->prefix = $prefix;
        $this->connect();
    }

    private function connect(): void
    {
        try {
            self::$redis = new Redis();
            $host = $_ENV['REDIS_HOST'] ?? 'localhost';
            $port = (int)($_ENV['REDIS_PORT'] ?? 6379);
            $password = $_ENV['REDIS_PASSWORD'] ?? null;
            $db = (int)($_ENV['REDIS_DB'] ?? 0);

            self::$redis->connect($host, $port);
            
            if ($password) {
                self::$redis->auth($password);
            }
            
            self::$redis->select($db);
        } catch (Exception $e) {
            throw new Exception('Redis connection failed: ' . $e->getMessage());
        }
    }

    public function get(string $key): mixed
    {
        if (!self::$redis) {
            return null;
        }

        $value = self::$redis->get($this->prefix . $key);
        
        if ($value === false) {
            return null;
        }

        return json_decode($value, true);
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        if (!self::$redis) {
            return false;
        }

        return self::$redis->setex(
            $this->prefix . $key,
            $ttl,
            json_encode($value)
        );
    }

    public function delete(string $key): bool
    {
        if (!self::$redis) {
            return false;
        }

        return self::$redis->del($this->prefix . $key) > 0;
    }

    public function exists(string $key): bool
    {
        if (!self::$redis) {
            return false;
        }

        return self::$redis->exists($this->prefix . $key) > 0;
    }

    public function flush(): bool
    {
        if (!self::$redis) {
            return false;
        }

        return self::$redis->flushDB();
    }

    public function increment(string $key, int $value = 1): int
    {
        if (!self::$redis) {
            return 0;
        }

        return self::$redis->incrBy($this->prefix . $key, $value);
    }

    public function decrement(string $key, int $value = 1): int
    {
        if (!self::$redis) {
            return 0;
        }

        return self::$redis->decrBy($this->prefix . $key, $value);
    }

    public function ttl(string $key): int
    {
        if (!self::$redis) {
            return -2;
        }

        return self::$redis->ttl($this->prefix . $key);
    }

    public static function getInstance(): Redis
    {
        if (self::$redis === null) {
            throw new Exception('Redis not initialized. Create a CacheService instance first.');
        }
        return self::$redis;
    }
}
