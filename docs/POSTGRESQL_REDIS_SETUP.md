# PostgreSQL + Redis Setup Guide

## Overview
FASE 0 foi atualizada para usar **PostgreSQL 13.0+** e **Redis 6.0+** conforme especificado no documento `docs/4-PLANO_DE_EXECUCAO.md`.

## Prerequisites

### PostgreSQL 13.0+
```bash
# macOS (Homebrew)
brew install postgresql@13

# Ubuntu/Debian
sudo apt-get install postgresql-13

# Windows
# Download from https://www.postgresql.org/download/windows/
```

### Redis 6.0+
```bash
# macOS (Homebrew)
brew install redis

# Ubuntu/Debian
sudo apt-get install redis-server

# Windows
# Download from https://github.com/microsoftarchive/redis/releases
```

### PHP Extensions
```bash
# PostgreSQL PDO Driver
php -r "phpinfo()" | grep -i pgsql
# If not present, install:
# macOS: brew install php@8.1 --with-pgsql
# Ubuntu: sudo apt-get install php-pgsql

# Redis Extension
php -r "phpinfo()" | grep -i redis
# If not present, install:
# macOS: pecl install redis
# Ubuntu: sudo apt-get install php-redis
```

## Configuration Files

### .env Configuration
Updated `.env.example` now includes:
```env
# Database (PostgreSQL)
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_user
DB_PASSWORD=secure_password_change_me
DB_SSLMODE=prefer

# Redis (Cache & Session)
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_DB=0
REDIS_CACHE_DB=1
```

## Setup Instructions

### 1. Create PostgreSQL Database and User
```bash
# Connect to PostgreSQL
sudo -u postgres psql

# Create database
CREATE DATABASE blog_platform 
  ENCODING 'UTF8' 
  LC_COLLATE 'en_US.UTF-8' 
  LC_CTYPE 'en_US.UTF-8';

# Create user
CREATE USER blog_user WITH PASSWORD 'your_secure_password';

# Grant privileges
GRANT ALL PRIVILEGES ON DATABASE blog_platform TO blog_user;

# Connect to database and grant schema privileges
\c blog_platform
GRANT ALL ON SCHEMA public TO blog_user;

# Exit psql
\q
```

### 2. Configure Backend
```bash
cd backend
cp .env.example .env

# Edit .env with your PostgreSQL and Redis credentials
nano .env
```

### 3. Install PHP Dependencies
```bash
cd backend
composer install
```

### 4. Run Database Migrations
```bash
# Create migration runner script
php -r "
  require 'vendor/autoload.php';
  \$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
  \$dotenv->load();
  \$migration = new \App\Database\Migration();
  \$migration->run();
"
```

### 5. Start Services
```bash
# PostgreSQL
# Usually starts automatically after installation

# Redis
redis-server

# Backend API (in new terminal)
cd backend
php -S localhost:8000 -t public

# Frontend (in another terminal)
cd frontend
npm install
npm run dev
```

### 6. Verify Setup
```bash
# Test PostgreSQL connection
psql -h localhost -U blog_user -d blog_platform -c "SELECT version();"

# Test Redis connection
redis-cli ping
# Expected: PONG

# Test API
curl http://localhost:8000/api/v1/health
# Expected: {"status":"ok","timestamp":"..."}
```

## File Changes Made

### Backend
- `backend/.env.example` - Updated for PostgreSQL + Redis
- `backend/src/Database/Connection.php` - Changed DSN to PostgreSQL
- `backend/src/Database/Migration.php` - Converted to PostgreSQL syntax
- `backend/src/Cache/CacheService.php` - NEW: Redis cache service
- `backend/composer.json` - Updated dependencies

### Documentation
- `docs/DATABASE_SCHEMA.md` - Updated for PostgreSQL

## Migration from MySQL (if applicable)

If you had a MySQL setup, migrate using:
```bash
# Export MySQL data
mysqldump blog_platform -u root -p > backup.sql

# Convert to PostgreSQL (manual or using tools like pgLoader)
# or simply run the new migrations on PostgreSQL
```

## Redis Cache Keys

The cache service uses these key prefixes:
```
blog:post:{id}                  # Individual post
blog:posts:lang:{lang}          # Posts list per language
blog:categories:lang:{lang}     # Categories per language
blog:menus:lang:{lang}          # Menus per language
blog:settings                   # Global settings
blog:user_sessions:{user_id}    # User sessions
```

## CacheService Usage in PHP

```php
use App\Cache\CacheService;

// Initialize cache service
$cache = new CacheService('blog:');

// Get value
$post = $cache->get('post:1');

// Set value (3600 seconds TTL)
$cache->set('post:1', $postData, 3600);

// Check existence
if ($cache->exists('post:1')) {
    // Do something
}

// Delete
$cache->delete('post:1');

// Increment counter
$cache->increment('views:post:1');
```

## Environment Variables Reference

| Variable | Default | Description |
|----------|---------|-------------|
| DB_HOST | localhost | PostgreSQL host |
| DB_PORT | 5432 | PostgreSQL port |
| DB_DATABASE | blog_platform | Database name |
| DB_USER | blog_user | Database user |
| DB_PASSWORD | - | Database password |
| DB_SSLMODE | prefer | PostgreSQL SSL mode |
| REDIS_HOST | localhost | Redis host |
| REDIS_PORT | 6379 | Redis port |
| REDIS_PASSWORD | null | Redis password |
| REDIS_DB | 0 | Redis database for sessions |
| REDIS_CACHE_DB | 1 | Redis database for cache |

## Troubleshooting

### PostgreSQL Connection Issues
```bash
# Check PostgreSQL is running
sudo service postgresql status

# Check connection
psql -h localhost -U blog_user -d blog_platform -c "SELECT 1;"
```

### Redis Connection Issues
```bash
# Check Redis is running
redis-cli ping

# Monitor Redis commands
redis-cli MONITOR
```

### PHP Extension Issues
```bash
# Verify PostgreSQL extension
php -m | grep pdo_pgsql

# Verify Redis extension
php -m | grep redis
```

## Performance Notes

- **PostgreSQL:** Full ACID compliance, better for transactional data
- **Redis:** In-memory cache, faster access to frequently used data
- **JSONB fields:** Efficient querying of complex multilingual data
- **GIN indexes:** Fast full-text search in PostgreSQL

## Next Steps

1. Verify all services are running
2. Test API endpoints
3. Proceed with Phase 1 (Core API Backend)
4. Implement caching strategies throughout the application

---

**Updated for Phase 0: PostgreSQL + Redis Stack**
