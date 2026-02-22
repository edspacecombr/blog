#!/usr/bin/env php
<?php

/**
 * Connection Validator for PostgreSQL + Redis
 * Validates NEXT_STEPS.md environment configuration
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "\n";
echo str_repeat("═", 80) . "\n";
echo "  🔗 PostgreSQL + Redis Connection Validator\n";
echo str_repeat("═", 80) . "\n\n";

// Define colors
class Colors {
    const GREEN = "\033[0;32m";
    const RED = "\033[0;31m";
    const YELLOW = "\033[1;33m";
    const BLUE = "\033[0;34m";
    const NC = "\033[0m";
}

// Connection credentials (will be replaced interactively)
$config = [
    'postgresql' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '5432',
        'database' => getenv('DB_DATABASE') ?: 'blog_platform',
        'user' => getenv('DB_USER') ?: 'blog_user',
        'password' => getenv('DB_PASSWORD') ?: '',
    ],
    'redis' => [
        'host' => getenv('REDIS_HOST') ?: 'localhost',
        'port' => getenv('REDIS_PORT') ?: '6379',
        'password' => getenv('REDIS_PASSWORD') ?: '',
        'db' => getenv('REDIS_DB') ?: '0',
    ]
];

// Display current configuration
echo "\n📋 Current Configuration:\n\n";

echo Colors::BLUE . "PostgreSQL:" . Colors::NC . "\n";
echo "  • Host: {$config['postgresql']['host']}\n";
echo "  • Port: {$config['postgresql']['port']}\n";
echo "  • Database: {$config['postgresql']['database']}\n";
echo "  • User: {$config['postgresql']['user']}\n";
echo "  • Password: " . (strlen($config['postgresql']['password']) > 0 ? "***" : "(empty)") . "\n\n";

echo Colors::BLUE . "Redis:" . Colors::NC . "\n";
echo "  • Host: {$config['redis']['host']}\n";
echo "  • Port: {$config['redis']['port']}\n";
echo "  • Password: " . (strlen($config['redis']['password']) > 0 ? "***" : "(none)") . "\n";
echo "  • Database: {$config['redis']['db']}\n\n";

// Prompt for changes
echo Colors::YELLOW . "❓ Use these settings? (yes/no/customize): " . Colors::NC;
$response = trim(fgets(STDIN));

if (strtolower($response) === 'no') {
    exit(0);
} elseif (strtolower($response) === 'customize') {
    echo "\n" . Colors::BLUE . "📝 Customizing PostgreSQL Connection:" . Colors::NC . "\n";
    
    echo "Host (default: {$config['postgresql']['host']}): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['postgresql']['host'] = $input;
    
    echo "Port (default: {$config['postgresql']['port']}): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['postgresql']['port'] = $input;
    
    echo "Database (default: {$config['postgresql']['database']}): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['postgresql']['database'] = $input;
    
    echo "Username (default: {$config['postgresql']['user']}): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['postgresql']['user'] = $input;
    
    echo "Password (default: empty): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['postgresql']['password'] = $input;
    
    echo "\n" . Colors::BLUE . "📝 Customizing Redis Connection:" . Colors::NC . "\n";
    
    echo "Host (default: {$config['redis']['host']}): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['redis']['host'] = $input;
    
    echo "Port (default: {$config['redis']['port']}): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['redis']['port'] = $input;
    
    echo "Password (default: empty): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['redis']['password'] = $input;
    
    echo "Database (default: {$config['redis']['db']}): ";
    $input = trim(fgets(STDIN));
    if (!empty($input)) $config['redis']['db'] = $input;
}

echo "\n";
echo str_repeat("═", 80) . "\n";
echo "  🧪 Testing Connections\n";
echo str_repeat("═", 80) . "\n\n";

// Test PostgreSQL Connection
echo Colors::BLUE . "🔗 Testing PostgreSQL Connection..." . Colors::NC . "\n";

$pgDsn = sprintf(
    "pgsql:host=%s;port=%s;dbname=%s",
    $config['postgresql']['host'],
    $config['postgresql']['port'],
    $config['postgresql']['database']
);

try {
    $pdo = new PDO(
        $pgDsn,
        $config['postgresql']['user'],
        $config['postgresql']['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]
    );
    
    // Test query
    $result = $pdo->query('SELECT NOW() as current_time, version() as pg_version;');
    $data = $result->fetch(PDO::FETCH_ASSOC);
    
    echo Colors::GREEN . "✓ PostgreSQL Connection Successful!" . Colors::NC . "\n";
    echo "  • Timestamp: {$data['current_time']}\n";
    echo "  • Version: " . substr($data['pg_version'], 0, 50) . "...\n\n";
    
} catch (Exception $e) {
    echo Colors::RED . "✗ PostgreSQL Connection Failed!" . Colors::NC . "\n";
    echo "  Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test Redis Connection
echo Colors::BLUE . "🔗 Testing Redis Connection..." . Colors::NC . "\n";

try {
    $redis = new Redis();
    $redis->connect(
        $config['redis']['host'],
        $config['redis']['port'],
        5  // timeout
    );
    
    if (!empty($config['redis']['password'])) {
        $redis->auth($config['redis']['password']);
    }
    
    // Test ping
    $ping = $redis->ping();
    
    if ($ping === true || $ping === '+PONG') {
        echo Colors::GREEN . "✓ Redis Connection Successful!" . Colors::NC . "\n";
        echo "  • Ping: PONG\n";
        
        // Get Redis info
        $info = $redis->info('server');
        echo "  • Version: " . $info['redis_version'] . "\n";
        echo "  • Database: {$config['redis']['db']}\n\n";
        
    } else {
        throw new Exception('Unexpected ping response: ' . $ping);
    }
    
} catch (Exception $e) {
    echo Colors::RED . "✗ Redis Connection Failed!" . Colors::NC . "\n";
    echo "  Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test PostgreSQL Database Schema
echo Colors::BLUE . "🔗 Checking PostgreSQL Schema..." . Colors::NC . "\n";

try {
    $tables = [
        'users',
        'blog_settings',
        'posts',
        'categories',
        'post_category',
        'pages',
        'menus',
        'menu_items',
        'media_library',
        'languages',
        'language_settings'
    ];
    
    $missingTables = [];
    foreach ($tables as $table) {
        $result = $pdo->query(
            "SELECT EXISTS (
                SELECT 1 FROM information_schema.tables 
                WHERE table_schema = 'public' AND table_name = '{$table}'
            ) as exists"
        );
        $exists = $result->fetch(PDO::FETCH_ASSOC)['exists'];
        
        if ($exists) {
            echo "  " . Colors::GREEN . "✓" . Colors::NC . " Table: {$table}\n";
        } else {
            $missingTables[] = $table;
            echo "  " . Colors::YELLOW . "⚠" . Colors::NC . " Table: {$table} (missing)\n";
        }
    }
    
    echo "\n";
    
    if (empty($missingTables)) {
        echo Colors::GREEN . "✓ All expected tables found!" . Colors::NC . "\n\n";
    } else {
        echo Colors::YELLOW . "⚠ Missing tables: " . implode(', ', $missingTables) . Colors::NC . "\n";
        echo "  Run migrations to create: php -r \"require 'vendor/autoload.php'; \$m = new \App\Database\Migration(); \$m->run();\"\n\n";
    }
    
} catch (Exception $e) {
    echo Colors::YELLOW . "⚠ Schema check warning: " . $e->getMessage() . Colors::NC . "\n\n";
}

// Test Redis Cache Operations
echo Colors::BLUE . "🔗 Testing Redis Cache Operations..." . Colors::NC . "\n";

try {
    // Test SET/GET
    $redis->select($config['redis']['db']);
    $redis->set('test:key', 'test:value', 60);
    $value = $redis->get('test:key');
    
    if ($value === 'test:value') {
        echo Colors::GREEN . "✓ Cache operations working!" . Colors::NC . "\n";
        echo "  • SET: OK\n";
        echo "  • GET: OK\n";
        echo "  • TTL: 60 seconds\n\n";
    } else {
        throw new Exception('Unexpected cache value: ' . $value);
    }
    
} catch (Exception $e) {
    echo Colors::RED . "✗ Cache operations failed: " . $e->getMessage() . Colors::NC . "\n\n";
    exit(1);
}

// Generate .env configuration
echo str_repeat("═", 80) . "\n";
echo "  ✅ All Connections Validated Successfully!\n";
echo str_repeat("═", 80) . "\n\n";

echo Colors::BLUE . "📝 Generated .env Configuration:" . Colors::NC . "\n\n";

$envContent = <<<ENV
# PostgreSQL Database Configuration
DB_HOST={$config['postgresql']['host']}
DB_PORT={$config['postgresql']['port']}
DB_DATABASE={$config['postgresql']['database']}
DB_USER={$config['postgresql']['user']}
DB_PASSWORD={$config['postgresql']['password']}
DB_CHARSET=utf8mb4

# Redis Cache Configuration
REDIS_HOST={$config['redis']['host']}
REDIS_PORT={$config['redis']['port']}
REDIS_PASSWORD={$config['redis']['password']}
REDIS_DB={$config['redis']['db']}
REDIS_CACHE_DB=1

# Application Configuration
APP_NAME=Blog Platform
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
JWT_SECRET=your_jwt_secret_key_change_this
JWT_EXPIRATION=86400

# Email Configuration (optional)
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_FROM=noreply@blog.local

ENV;

echo $envContent;

// Save .env
$envPath = dirname(__DIR__) . '/backend/.env';

if (file_exists($envPath)) {
    echo "\n" . Colors::YELLOW . "❓ backend/.env already exists. Overwrite? (yes/no): " . Colors::NC;
    $response = trim(fgets(STDIN));
    if (strtolower($response) !== 'yes') {
        echo "  Skipped.\n\n";
    } else {
        file_put_contents($envPath, $envContent);
        echo Colors::GREEN . "✓ .env file updated!" . Colors::NC . "\n\n";
    }
} else {
    file_put_contents($envPath, $envContent);
    echo Colors::GREEN . "✓ .env file created!" . Colors::NC . "\n\n";
}

echo "\n" . Colors::BLUE . "🚀 Next Steps:" . Colors::NC . "\n";
echo "1. Review backend/.env configuration\n";
echo "2. Run migrations: php -r \"require 'vendor/autoload.php'; \$m = new \App\Database\Migration(); \$m->run();\"\n";
echo "3. Start backend: php -S localhost:8000 -t public\n";
echo "4. Start frontend: npm run dev\n\n";

echo Colors::GREEN . "✅ Environment ready for PHASE 1 validation!" . Colors::NC . "\n\n";
