# 🛠️ SETUP ENVIRONMENT - Step-by-Step Instructions

## Status: Phase 0 Foundation Ready - Environment Setup Required

**Date:** 22 de Fevereiro de 2026  
**Current Stage:** ✅ Code Foundation Complete | ❌ Environment Not Yet Prepared  
**Next:** Install PHP 8.1 + Node.js → Composer + npm dependencies → Validate

---

## ⚠️ Important: Your Environment

You have:
- ✅ Project files and code structure ready
- ✅ PostgreSQL running in WSL2 Docker on `localhost:5432`
- ✅ Redis running in WSL2 Docker on `localhost:6379`
- ✅ .env files pre-configured with correct credentials
- ❌ PHP 8.1 NOT installed on local system
- ❌ Node.js 18+ NOT installed on local system
- ❌ Composer dependencies NOT installed
- ❌ npm dependencies NOT installed

---

## 🔑 Database Credentials (Already Configured)

```
PostgreSQL:
  Host: localhost
  Port: 5432
  Database: blog_platform
  User: blog_admin
  Password: <USE_ENV>

Redis:
  Host: localhost
  Port: 6379
  Password: <USE_ENV>
```

These are already in `/backend/.env` and ready to use once PHP is installed.

---

## 📋 Required Actions (IN THIS ORDER)

### 1️⃣ INSTALL PHP 8.1 WITH ALL REQUIRED EXTENSIONS

**Ubuntu/WSL2:**

```bash
# Update package manager
sudo apt-get update

# Install PHP 8.1 CLI + all required extensions
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

# Verify installation
php --version
composer --version

# Verify extensions
php -m | grep -E "pdo|pdo_pgsql|redis|json|curl|mbstring|xml"
# All should show in the output
```

**macOS:**

```bash
# Using Homebrew
brew install php@8.1 composer

# Verify
php --version
composer --version

# Verify extensions (should be included)
php -m | grep redis
```

**Windows (WSL2):**
- Same as Ubuntu instructions above

---

### 2️⃣ INSTALL NODE.JS 18+ AND NPM

**Ubuntu/WSL2:**

```bash
# Check if already installed
node --version
npm --version

# If not installed (or version < 18), install Node.js 18 LTS
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verify versions
node --version   # Should be >= 18.0.0
npm --version    # Should be >= 9.0.0
```

**macOS:**

```bash
# Using Homebrew
brew install node

# Or using nvm (recommended):
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
nvm install 18
nvm use 18

# Verify
node --version
npm --version
```

**Windows (WSL2):**
- Same as Ubuntu instructions above

---

### 3️⃣ INSTALL BACKEND DEPENDENCIES

```bash
# Navigate to backend directory
cd /home/edblack/projetos/blog/backend

# Install all Composer packages
composer install --no-interaction

# Verify installation succeeded
test -d vendor && echo "✓ Backend setup successful" || echo "✗ Backend setup FAILED"

# Check package count
composer show | wc -l  # Should show 50+ packages
```

**If Composer install hangs:**
```bash
# Try with network timeout:
composer install --no-interaction --no-dev --prefer-dist

# Or clear cache:
composer clearcache
composer install --no-interaction
```

---

### 4️⃣ INSTALL FRONTEND DEPENDENCIES

```bash
# Navigate to frontend directory
cd /home/edblack/projetos/blog/frontend

# Install npm packages
npm install

# Verify installation succeeded
test -d node_modules && echo "✓ Frontend setup successful" || echo "✗ Frontend setup FAILED"

# Check package count
ls node_modules | wc -l  # Should show 400+ packages
```

**If npm install fails:**
```bash
# Clear npm cache
npm cache clean --force

# Try again
npm install

# If still failing, use legacy peer deps:
npm install --legacy-peer-deps
```

---

## ✅ VALIDATION - Verify Everything is Installed

### Check PHP Extensions

```bash
php -m | grep -E "pdo_pgsql|redis"

# Expected output:
# pdo_pgsql
# redis
```

### Check Composer Dependencies

```bash
cd /home/edblack/projetos/blog/backend
composer show | head -10

# Expected: List of packages including:
# - monolog/monolog
# - vlucas/phpdotenv
# - firebase/php-jwt
# - etc
```

### Check npm Dependencies

```bash
cd /home/edblack/projetos/blog/frontend
npm list --depth=0 | head -10

# Expected packages:
# next
# react
# typescript
# tailwindcss
# etc
```

---

## 🔌 CONNECTIVITY TESTS

Once installation is complete, test the connections:

### Test PostgreSQL Connection

```bash
# From terminal (assuming Docker is running)
psql -h localhost -U blog_admin -d blog_platform -W
# Password: <USE_ENV>

# Inside psql:
\dt                    # List tables
SELECT version();      # Check version
\q                     # Exit
```

**Expected:** Should show 11+ tables created

### Test Redis Connection

```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>"

# Inside redis-cli:
PING                   # Should return: PONG
CONFIG GET requirepass # Should show: <USE_ENV>
exit
```

**Expected:** PONG response and password configured

### Test Backend Database Connection from PHP

```bash
cd /home/edblack/projetos/blog/backend

php -r "
require 'vendor/autoload.php';
try {
    \$conn = new \App\Database\Connection();
    \$pdo = \$conn->getInstance();
    \$result = \$pdo->query('SELECT current_database()')->fetch();
    echo '✓ PostgreSQL: ' . \$result['current_database'] . PHP_EOL;
} catch (Exception \$e) {
    echo '✗ Error: ' . \$e->getMessage() . PHP_EOL;
}
"
```

**Expected:** `✓ PostgreSQL: blog_platform`

### Test Backend Cache Connection from PHP

```bash
cd /home/edblack/projetos/blog/backend

php -r "
require 'vendor/autoload.php';
try {
    \$cache = new \App\Cache\CacheService();
    \$cache->set('test', ['status' => 'ok'], 60);
    \$result = \$cache->get('test');
    echo '✓ Redis: ' . json_encode(\$result) . PHP_EOL;
} catch (Exception \$e) {
    echo '✗ Error: ' . \$e->getMessage() . PHP_EOL;
}
"
```

**Expected:** `✓ Redis: {"status":"ok"}`

---

## 🚀 START DEVELOPMENT SERVERS

Once all validations pass, open **3 terminals**:

### Terminal 1: Backend API

```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public

# You should see:
# Development Server (http://127.0.0.1:8000)
# Listening on http://127.0.0.1:8000
```

### Terminal 2: Frontend

```bash
cd /home/edblack/projetos/blog/frontend
npm run dev

# You should see:
# ▲ Next.js 14.x
# - Local: http://localhost:3000
```

### Terminal 3: Verify APIs

```bash
# Test Backend Health
curl http://localhost:8000/api/v1/health

# Test Frontend
curl -s http://localhost:3000 | head -20
```

---

## 🔍 RUN AUTOMATED VALIDATION

```bash
cd /home/edblack/projetos/blog
chmod +x validate-setup.sh
./validate-setup.sh
```

This script will check:
- ✓ All required tools installed
- ✓ PHP extensions loaded
- ✓ Dependencies installed
- ✓ .env files configured
- ✓ Database accessible
- ✓ Redis accessible

---

## 📊 Phase 0 Status After Setup

Once all steps are complete:

| Component | Status |
|-----------|--------|
| Backend Code | ✅ Ready |
| Frontend Code | ✅ Ready |
| PostgreSQL | ✅ Running (Docker) |
| Redis | ✅ Running (Docker) |
| PHP 8.1 | ⚙️ Installing... |
| Node.js 18+ | ⚙️ Installing... |
| Composer | ⚙️ Installing... |
| npm | ⚙️ Installing... |
| PHP Dependencies | ⚙️ Installing... |
| npm Dependencies | ⚙️ Installing... |
| API Health | 🔄 Testing... |
| Frontend Running | 🔄 Testing... |

---

## 🎯 Next Step After Setup

Once everything is installed and validated:

1. ✅ All components installed and running
2. ✅ Backend API responding on http://localhost:8000
3. ✅ Frontend running on http://localhost:3000
4. ✅ PostgreSQL connected and accessible
5. ✅ Redis connected and accessible

**Then:**
- Start Phase 1: Core API Backend
- Begin building Post CRUD operations
- Implement Category management
- Set up Media library

---

## ❓ Troubleshooting

### PHP Command Not Found
```bash
which php
# If returns nothing, PHP is not installed yet
# Run Step 1 above
```

### Composer command not found
```bash
# Make sure PHP is installed first
php --version
# Then reinstall Composer
sudo apt-get install --reinstall composer
```

### npm ERR! node v14.xx.x
```bash
# Node version too old (need >= 18.0.0)
node --version
# Follow Node.js installation in Step 2
```

### PostgreSQL connection refused
```bash
# Check if Docker is running
docker ps | grep postgres
# If not running, start from Docker
```

### Redis connection refused
```bash
# Check if Docker is running
docker ps | grep redis
# If not running, start from Docker
```

---

## 📞 Ready to Start?

Once you complete the 4 main steps above, run this final check:

```bash
echo "=== PHP Setup ===" && \
php --version && \
composer --version && \
php -m | grep redis && \
echo "✓ PHP Ready" || echo "✗ PHP Not Ready"

echo "" && \
echo "=== Node.js Setup ===" && \
node --version && \
npm --version && \
echo "✓ Node Ready" || echo "✗ Node Not Ready"

echo "" && \
echo "=== Backend ===" && \
test -d /home/edblack/projetos/blog/backend/vendor && \
echo "✓ Backend Dependencies Ready" || echo "✗ Backend Dependencies Missing"

echo "" && \
echo "=== Frontend ===" && \
test -d /home/edblack/projetos/blog/frontend/node_modules && \
echo "✓ Frontend Dependencies Ready" || echo "✗ Frontend Dependencies Missing"

echo "" && \
echo "=== PostgreSQL ===" && \
psql -h localhost -U blog_admin -d blog_platform -W -c "SELECT 'Connected'" 2>/dev/null || echo "Test when Docker is running"

echo "" && \
echo "=== Redis ===" && \
redis-cli -h localhost -p 6379 -a "<USE_ENV>" PING 2>/dev/null || echo "Test when Docker is running"
```

All should show ✓ marks when ready.

---

**Document Version:** 1.0  
**Created:** 22 de Fevereiro de 2026  
**Status:** Environment Setup Instructions for Phase 0 Complete

When ready, notify me and we'll validate and start Phase 1!
