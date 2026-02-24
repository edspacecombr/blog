<?php
require 'backend/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/backend');
$dotenv->load();

echo "\n🔍 Informações de Conexão PostgreSQL:\n";
echo "  Host: " . $_ENV['DB_HOST'] . "\n";
echo "  Port: " . $_ENV['DB_PORT'] . "\n";
echo "  Database: " . $_ENV['DB_DATABASE'] . "\n";
echo "  User: " . $_ENV['DB_USER'] . "\n";
echo "  Password: " . (strlen($_ENV['DB_PASSWORD']) > 0 ? "✓ Set" : "✗ Not set") . "\n";
echo "  SSL Mode: " . $_ENV['DB_SSLMODE'] . "\n\n";

echo "🔍 Informações de Conexão Redis:\n";
echo "  Host: " . $_ENV['REDIS_HOST'] . "\n";
echo "  Port: " . $_ENV['REDIS_PORT'] . "\n";
echo "  Password: " . (strlen($_ENV['REDIS_PASSWORD']) > 0 ? "✓ Set" : "✗ Not set") . "\n";
echo "  DB: " . $_ENV['REDIS_DB'] . "\n";
echo "  Cache DB: " . $_ENV['REDIS_CACHE_DB'] . "\n\n";

// Test DNS resolution
echo "🌐 Testando DNS Resolution:\n";
if (@gethostbyname($_ENV['DB_HOST']) !== $_ENV['DB_HOST']) {
    echo "  PostgreSQL Host ✓ Resolvido\n";
} else {
    echo "  PostgreSQL Host ✗ Não resolvido\n";
}

if (@gethostbyname($_ENV['REDIS_HOST']) !== $_ENV['REDIS_HOST']) {
    echo "  Redis Host ✓ Resolvido\n\n";
} else {
    echo "  Redis Host ✗ Não resolvido\n\n";
}

// Test socket connectivity
echo "🔗 Testando conectividade de socket:\n";
@$sock = fsockopen($_ENV['DB_HOST'], $_ENV['DB_PORT'], $errno, $errstr, 5);
if ($sock) {
    echo "  PostgreSQL Port (5432) ✓ Acessível\n";
    fclose($sock);
} else {
    echo "  PostgreSQL Port (5432) ✗ Não acessível\n";
}

@$sock = fsockopen($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT'], $errno, $errstr, 5);
if ($sock) {
    echo "  Redis Port (6379) ✓ Acessível\n";
    fclose($sock);
} else {
    echo "  Redis Port (6379) ✗ Não acessível\n";
}
