# 🚀 Professional Multilingual Blog Platform - Phase 0 Complete

> **Status:** Phase 0 ✅ Foundation Complete | PostgreSQL + Redis | Ready for Phase 1  
> **Last Updated:** 22 de Fevereiro de 2026  
> **Stack:** PHP 8.1 REST API + Next.js 14 Frontend + PostgreSQL 13+ + Redis 6+

A production-ready, SEO-optimized, multilingual blogging platform built with modern technologies. Designed for content creators, agencies, and businesses looking to build sustainable, organic traffic with global reach.

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://php.net)
[![Next.js](https://img.shields.io/badge/Next.js-14-black)](https://nextjs.org)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13.0%2B-336791)](https://postgresql.org)
[![Redis](https://img.shields.io/badge/Redis-6.0%2B-DC382D)](https://redis.io)
[![TypeScript](https://img.shields.io/badge/TypeScript-5-blue)](https://www.typescriptlang.org)

---

## 🚀 Quick Start - Environment Setup Guide

### 📋 Prerequisites Required

Your environment must have:
- ✅ **PostgreSQL 13+** running on `localhost:5432` (in WSL2 Docker)
- ✅ **Redis 6+** running on `localhost:6379` (in WSL2 Docker)  
- ✅ **PHP 8.1+** with pdo_pgsql, redis, json, curl, mbstring, xml extensions
- ✅ **Node.js 18+** with npm 9+
- ✅ **Composer 2.x+** for PHP dependencies

### 🔑 Database & Cache Credentials

**PostgreSQL:**
```
Host: localhost
Port: 5432
Database: blog_platform
User: blog_admin
Password: <USE_ENV>
```

**Redis:**
```
Host: localhost
Port: 6379
Password: <USE_ENV>
DB 0: Application data
DB 1: Cache storage
```

---

## 📦 Installation Steps (Ubuntu/WSL2)

### Step 1: Install PHP 8.1 with All Extensions

```bash
# Update package lists
sudo apt-get update

# Install PHP 8.1 CLI + required extensions
sudo apt-get install -y \
  php8.1-cli \
  php8.1-pdo \
  php8.1-pgsql \
  php8.1-redis \
  php8.1-json \
  php8.1-curl \
  php8.1-mbstring \
  php8.1-xml \
  php8.1-fpm \
  composer

# Verify PHP version
php --version
# Expected: PHP 8.1.x (CLI)

# Verify Composer
composer --version
# Expected: Composer 2.x.x

# Verify extensions are loaded
php -m | grep -E "pdo|pdo_pgsql|redis"
# Expected: pdo, pdo_pgsql, redis in the output
```

### Step 2: Install Node.js 18+ and npm

```bash
# Check current version (if already installed)
node --version
npm --version

# If you need to install or upgrade (Ubuntu/WSL2):
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verify versions
node --version  # Should be >= 18.0.0
npm --version   # Should be >= 9.0.0
```

### Step 3: Install Backend Dependencies

```bash
# Navigate to backend directory
cd /home/edblack/projetos/blog/backend

# Install all PHP packages
composer install --no-interaction

# Verify installation
test -d vendor && echo "✓ Backend dependencies installed" || echo "✗ Installation failed"
```

### Step 4: Install Frontend Dependencies

```bash
# Navigate to frontend directory
cd /home/edblack/projetos/blog/frontend

# Install all npm packages
npm install

# Verify installation
test -d node_modules && echo "✓ Frontend dependencies installed" || echo "✗ Installation failed"
```

---

## 🔌 Connectivity Validation

### Verify PostgreSQL Connection

```bash
# Test connection to PostgreSQL (from Docker on WSL2)
psql -h localhost -U blog_admin -d blog_platform -W
# Enter password: <USE_ENV>

# Inside psql console:
\c blog_platform
\dt                    # List all tables (should see 11+ tables)
SELECT version();      # Check PostgreSQL version
\q                     # Exit
```

### Verify Redis Connection

```bash
# Test connection to Redis (from Docker on WSL2)
redis-cli -h localhost -p 6379 -a "<USE_ENV>"

# Inside redis-cli console:
PING                   # Should return: PONG
INFO server            # Check Redis info
CONFIG GET requirepass # Verify password is set
KEYS *                 # List stored keys
exit                   # Exit console
```

---

## 🚀 Start Development Servers

You'll need **3 terminals** running simultaneously:

### Terminal 1: Start Backend API (PHP Development Server)

```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public

# Expected output:
# [timestamp] Development Server (http://127.0.0.1:8000)
# [timestamp] Listening on http://127.0.0.1:8000
```

### Terminal 2: Start Frontend (Next.js Development Server)

```bash
cd /home/edblack/projetos/blog/frontend
npm run dev

# Expected output:
# ▲ Next.js 14.x.x
# - Local:        http://localhost:3000
# - Environments: .env.local
```

### Terminal 3: Monitor Redis (Optional - For Debugging)

```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>" MONITOR

# This will show all Redis operations in real-time
# Useful for debugging cache issues
```

---

## ✅ Verify Everything is Working

### Check Backend Health Endpoint

```bash
# In a new terminal, test the API
curl -X GET http://localhost:8000/api/v1/health

# Expected JSON response:
# {
#   "status": "ok",
#   "timestamp": "2024-02-22T19:00:00Z",
#   "version": "0.1.0",
#   "environment": "development"
# }
```

### Check Frontend is Running

```bash
# Open in your browser:
# http://localhost:3000

# Or test via curl:
curl -s http://localhost:3000 | grep -q "<html" && echo "✓ Frontend is running"
```

### Test Database Connection from PHP

```bash
cd /home/edblack/projetos/blog/backend

php -r "
require 'vendor/autoload.php';
try {
    \$conn = new \App\Database\Connection();
    \$pdo = \$conn->getInstance();
    \$result = \$pdo->query('SELECT current_database()')->fetch();
    echo '✓ PostgreSQL Connected: ' . \$result['current_database'] . PHP_EOL;
} catch (Exception \$e) {
    echo '✗ Connection Error: ' . \$e->getMessage() . PHP_EOL;
}
"
```

### Test Redis Connection from PHP

```bash
cd /home/edblack/projetos/blog/backend

php -r "
require 'vendor/autoload.php';
try {
    \$cache = new \App\Cache\CacheService();
    \$cache->set('test_key', ['status' => 'Redis Connected!'], 60);
    \$result = \$cache->get('test_key');
    echo '✓ Redis Connected: ' . json_encode(\$result) . PHP_EOL;
} catch (Exception \$e) {
    echo '✗ Redis Error: ' . \$e->getMessage() . PHP_EOL;
}
"
```

---

## 🔍 Complete Validation Script

Run the automated validation to check all requirements:

```bash
cd /home/edblack/projetos/blog

# Make script executable
chmod +x validate-setup.sh

# Run validation
./validate-setup.sh

# Expected output showing all ✓ marks:
# ✓ PHP 8.1+ found
# ✓ Composer found
# ✓ Node.js found
# ✓ npm found
# ✓ PostgreSQL CLI found
# ✓ Redis CLI found
# ✓ PHP pdo_pgsql extension found
# ✓ PHP redis extension found
# ✓ Backend dependencies installed
# ✓ Frontend dependencies installed
# ✓ .env file configured
# ... etc
```

---

## 📊 Troubleshooting

### PHP Extensions Not Found

```bash
# Check which extensions are loaded
php -m | grep pdo
php -m | grep redis

# If missing, install them:
sudo apt-get install php8.1-pgsql php8.1-redis

# Restart PHP (if running as FPM):
sudo service php8.1-fpm restart
```

### PostgreSQL Connection Refused

```bash
# Check if PostgreSQL is running in Docker
docker ps | grep postgres

# If not, start it from your Docker host:
# (Instructions depend on your Docker setup)

# Test connection:
psql -h localhost -p 5432 -U blog_admin -d blog_platform -W
```

### Redis Connection Refused

```bash
# Check if Redis is running in Docker
docker ps | grep redis

# If not, start it from your Docker host:
# (Instructions depend on your Docker setup)

# Test connection:
redis-cli -h localhost -p 6379 -a "<USE_ENV>" PING
```

### Composer Install Hangs

```bash
# Try with network timeout:
composer install --no-interaction --no-dev --prefer-dist

# Or clear composer cache:
composer clearcache
composer install --no-interaction
```

### npm Install Issues

```bash
# Clear npm cache
npm cache clean --force

# Reinstall
npm install

# If still stuck, try with legacy peer deps:
npm install --legacy-peer-deps
```

---

## 📁 Project Structure (Phase 0)

```
/blog (root)
├── backend/                      # PHP 8.1 REST API
│   ├── public/                   # Webroot
│   │   └── index.php            # Entry point
│   ├── src/
│   │   ├── App.php              # Main application class
│   │   ├── Api/                 # API endpoints (Phase 1+)
│   │   ├── Auth/                # Authentication logic
│   │   ├── Cache/               # Redis cache service
│   │   │   └── CacheService.php # Cache abstraction
│   │   ├── Database/            # Database layer
│   │   │   └── Connection.php   # PostgreSQL connection
│   │   ├── Controllers/         # API controllers (Phase 1+)
│   │   ├── Middleware/          # Request middleware
│   │   └── Models/              # Data models (Phase 1+)
│   ├── .env                      # Configuration (PostgreSQL + Redis)
│   ├── composer.json             # PHP dependencies
│   └── composer.lock             # Locked versions
│
├── frontend/                     # Next.js 14 Admin + Public UI
│   ├── src/
│   │   ├── app/                 # App Router pages
│   │   ├── components/          # React components
│   │   ├── lib/                 # Utilities
│   │   └── styles/              # Tailwind CSS
│   ├── .env.local               # Frontend config
│   ├── package.json             # npm dependencies
│   ├── tsconfig.json            # TypeScript config
│   ├── tailwind.config.ts       # Tailwind setup
│   └── next.config.js           # Next.js config
│
├── docs/                         # Documentation
│   ├── 4-PLANO_DE_EXECUCAO.md   # Full execution plan (Phases 0-8)
│   ├── DATABASE_SCHEMA.md        # Database documentation
│   └── API_DOCUMENTATION.md      # API docs (coming Phase 1+)
│
├── .env.example                  # Environment template
├── .gitignore                    # Git ignore rules
├── setup.sh                      # Installation script
├── validate-setup.sh             # Validation script
├── health-check.sh               # API health checker
├── README_PHASE0.md              # This file
├── NEXT_STEPS.md                 # Phase 1 preparation
└── PHASE_0_SUMMARY.txt           # Phase 0 achievements
```

---

## 🗺️ Roadmap (Phases 1-8)

**Phase 0:** ✅ COMPLETE - Foundation (Backend + Frontend + DB + Cache)

**Phase 1:** 🔄 NEXT - Core API Backend (3 weeks)
- Post CRUD operations
- Category management
- Media library
- Author profiles
- Menu system

**Phase 2:** 📋 Multilingual + SEO (3 weeks)
- Language support
- Translation system
- Sitemap generation
- Schema markup (JSON-LD)
- hreflang implementation

**Phase 3-8:** Advanced features including Admin Panel, Public Frontend, Monetization, Performance, and Deployment

---

## 🔐 Security Features (Phase 0)

✅ **Implemented:**
- JWT authentication
- BCrypt password hashing
- Role-based access control (RBAC)
- CORS configuration (localhost:3000)
- Prepared statements (SQL injection prevention)
- Environment variable isolation
- Error handling with logging

🔒 **Coming in Phase 1+:**
- Rate limiting (Redis-based)
- CSRF protection
- Security headers (CSP, X-Frame-Options)
- Audit logging
- API key rotation

---

## 🚀 Performance Optimizations

- ⚡ Redis caching for frequently accessed data
- 📊 PostgreSQL with optimized indexes
- 🖼️ Next.js for server-side rendering
- 🗜️ Gzip compression ready
- 🔄 Connection pooling prepared
- 💾 JSONB for flexible storage

---

## 📚 Key Documentation Files

- **README_PHASE0.md** - This installation guide
- **NEXT_STEPS.md** - Phase 1 preparation and detailed instructions
- **docs/4-PLANO_DE_EXECUCAO.md** - Complete execution plan (Phases 0-8)
- **PHASE_0_SUMMARY.txt** - Phase 0 achievements and configuration details
- **backend/.env** - Backend configuration with PostgreSQL + Redis
- **frontend/.env.local** - Frontend API configuration

---

## 🆘 Getting Help

If you encounter issues:

1. **Check validate-setup.sh** - Run the validation script for diagnostic info
2. **Review NEXT_STEPS.md** - Detailed troubleshooting section
3. **Check backend/.env** - Verify PostgreSQL + Redis credentials
4. **Test connections manually** - Use psql and redis-cli to isolate issues
5. **Check logs** - See `backend/storage/logs/` for error details

---

## 📞 Support

- 📧 Email: support@yourdomain.com
- 🐛 Report Issues: GitHub Issues
- 📖 Documentation: See `docs/` directory
- 🔧 Configuration Help: Check NEXT_STEPS.md

---

## 📄 License

This project is licensed under the MIT License. See LICENSE file for details.

---

**Built with ❤️ for content creators worldwide**

*Professional Multilingual Blog Platform - Phase 0 Foundation Complete*

Version: 0.1.0 | Updated: 22 de Fevereiro de 2026
