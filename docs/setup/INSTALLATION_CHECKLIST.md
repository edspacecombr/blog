# ✅ Installation Checklist - Phase 0 Validation

**Status:** Ready for Local Environment Setup  
**Date:** 22 de Fevereiro de 2026  
**Prerequisites:** PostgreSQL 13+ + Redis 6+ (running in WSL2 Docker)

---

## 📋 Current Status

### ✅ Already Completed
- [x] Backend project structure created
- [x] Frontend project structure created  
- [x] Database schema designed (11+ tables)
- [x] Environment configuration prepared (.env files)
- [x] Documentation completed
- [x] API endpoints scaffolded
- [x] Authentication system configured
- [x] Cache layer strategy defined

### ⏳ Requires Local Setup (Your Environment)

---

## 🔧 Step 1: Install System Prerequisites

### Required CLI Tools

**PostgreSQL Client:**
```bash
# Ubuntu/Debian/WSL2
sudo apt-get update
sudo apt-get install -y postgresql-client

# Verify
psql --version
```

**Redis Client:**
```bash
# Ubuntu/Debian/WSL2
sudo apt-get install -y redis-tools

# Verify
redis-cli --version
```

**PHP 8.1:**
```bash
# Ubuntu/Debian/WSL2
sudo apt-get update
sudo apt-get install -y \
  php8.1-cli \
  php8.1-pdo \
  php8.1-pgsql \
  php8.1-redis \
  php8.1-json \
  php8.1-curl \
  php8.1-mbstring \
  php8.1-xml

# Verify
php --version
php -m | grep -E "pdo|pgsql|redis"
```

**Composer:**
```bash
# Ubuntu/Debian/WSL2
sudo apt-get install -y composer

# Verify
composer --version
```

### Verification Commands

```bash
# Check all installations
echo "=== System Tools ==="
php --version && echo "✓ PHP" || echo "✗ PHP"
composer --version && echo "✓ Composer" || echo "✗ Composer"
node --version && echo "✓ Node.js" || echo "✗ Node.js"
npm --version && echo "✓ npm" || echo "✗ npm"
psql --version && echo "✓ PostgreSQL CLI" || echo "✗ PostgreSQL CLI"
redis-cli --version && echo "✓ Redis CLI" || echo "✗ Redis CLI"

echo ""
echo "=== PHP Extensions ==="
php -m | grep -i pdo && echo "✓ PDO" || echo "✗ PDO"
php -m | grep -i pgsql && echo "✓ PostgreSQL" || echo "✗ PostgreSQL"
php -m | grep -i redis && echo "✓ Redis" || echo "✗ Redis"
```

---

## 🗄️ Step 2: Verify Remote Database Connections

### PostgreSQL Connection

**Command:**
```bash
psql -h localhost -U blog_admin -d blog_platform -W
```

**When prompted for password, enter:**
```
<USE_ENV>
```

**Inside psql, verify:**
```sql
-- Check current database
SELECT current_database();
-- Expected: blog_platform

-- List tables
\dt
-- Expected: 11+ tables (users, posts, categories, etc)

-- Exit
\q
```

**If connection fails:**
- Verify PostgreSQL is running in WSL2: `docker ps | grep postgres`
- Check port forwarding: `netstat -an | grep 5432`
- Test connectivity: `nc -zv localhost 5432`

---

### Redis Connection

**Command:**
```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>"
```

**Inside redis-cli, verify:**
```
# Test connection
PING
# Expected: PONG

# Check password
CONFIG GET requirepass
# Expected: <USE_ENV>

# Exit
exit
```

**If connection fails:**
- Verify Redis is running in WSL2: `docker ps | grep redis`
- Check port forwarding: `netstat -an | grep 6379`
- Test connectivity: `nc -zv localhost 6379`

---

## 📦 Step 3: Install Project Dependencies

### Backend Dependencies

```bash
cd /home/edblack/projetos/blog/backend

# Install PHP packages
composer install --no-interaction

# Verify installation
ls -la vendor/
composer show | head -20
```

**Expected output:**
- Directory `vendor/` created
- 50+ packages installed
- Key packages: monolog, phpdotenv, etc

### Frontend Dependencies

```bash
cd /home/edblack/projetos/blog/frontend

# Install npm packages
npm install

# Verify installation
ls -la node_modules/
npm list --depth=0
```

**Expected output:**
- Directory `node_modules/` created
- 400+ packages installed
- Key packages: react, next, typescript, tailwindcss

---

## 🔐 Step 4: Database Initialization

### Option A: Automatic Migrations

```bash
cd /home/edblack/projetos/blog/backend

# Run migrations via PHP
php -r "
  require 'vendor/autoload.php';
  \$dotenv = new Dotenv\Dotenv(__DIR__);
  \$dotenv->load();
  \$migration = new \App\Database\Migration();
  \$migration->run();
"
```

### Option B: Manual via psql

```bash
# If tables need to be created manually:
psql -h localhost -U blog_admin -d blog_platform -W < docs/database-schema.sql
# (SQL file needs to be prepared from schema)
```

---

## ✅ Step 5: Validation

### Run Full Validation

```bash
cd /home/edblack/projetos/blog
./validate-setup.sh
```

**Expected output:**
```
✓ PHP installed
✓ Composer installed
✓ Node.js installed
✓ npm installed
✓ PostgreSQL CLI found
✓ Redis CLI found
✓ PHP PDO extension
✓ PHP PostgreSQL extension
✓ PostgreSQL connection successful
✓ Database tables exist
✓ Redis connection successful
✓ Redis password configured
...
✅ All checks passed!
```

### Test Database Connection

```bash
./test-connections.sh
```

**Expected:**
```
✓ PostgreSQL connection successful
✓ Database tables found
✓ Redis connection successful
✓ Redis password verified
✅ All database connections working!
```

### Test API Endpoint (after starting backend)

```bash
curl http://localhost:8000/api/v1/health
```

**Expected response:**
```json
{
  "status": "ok",
  "timestamp": "2026-02-22T18:30:00Z"
}
```

---

## 🚀 Step 6: Start Development Services

### Terminal 1: Backend API

```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public
```

**Expected output:**
```
Development Server started
Listening on http://localhost:8000
```

### Terminal 2: Frontend

```bash
cd /home/edblack/projetos/blog/frontend
npm run dev
```

**Expected output:**
```
> next dev
  ▲ Next.js 14.0.0
  - Local:        http://localhost:3000
  - Environments: .env.local
```

### Terminal 3: Monitor Redis (Optional)

```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>" MONITOR
```

---

## 🌐 Step 7: Access Applications

Once all services are running:

- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000
- **Health Check:** http://localhost:8000/api/v1/health
- **Redis Monitor:** (from Terminal 3)

---

## 🐛 Troubleshooting

### "PHP command not found"
```bash
# Install PHP 8.1
sudo apt-get install php8.1-cli

# If still not found, check PATH
which php
```

### "Cannot connect to PostgreSQL"
```bash
# Check if PostgreSQL Docker container is running
docker ps | grep postgres

# If not running, start it in your WSL2 instance
docker-compose up -d

# Test connectivity
nc -zv localhost 5432
```

### "Cannot connect to Redis"
```bash
# Check if Redis Docker container is running
docker ps | grep redis

# If not running, start it in your WSL2 instance
docker run -d -p 6379:6379 -e REDIS_PASSWORD=<USE_ENV> redis

# Test connectivity
redis-cli -a "<USE_ENV>" PING
```

### "Port 8000 already in use"
```bash
# Find process using port 8000
sudo lsof -i :8000

# Kill the process
sudo kill -9 <PID>

# Or use a different port
php -S localhost:8001 -t public
```

### "npm install fails"
```bash
# Clear npm cache
npm cache clean --force

# Reinstall
npm install
```

### "Composer install fails"
```bash
# Update composer
composer self-update

# Clear cache
composer clear-cache

# Reinstall
composer install --no-interaction
```

---

## 📋 Pre-Flight Checklist

Before proceeding to Phase 1, ensure:

- [ ] PHP 8.1+ installed with all extensions
- [ ] Composer installed
- [ ] Node.js + npm installed
- [ ] PostgreSQL CLI installed
- [ ] Redis CLI installed
- [ ] Backend dependencies installed (`vendor/` exists)
- [ ] Frontend dependencies installed (`node_modules/` exists)
- [ ] PostgreSQL connection successful
- [ ] Redis connection successful
- [ ] Database tables created (or migrations ready)
- [ ] Backend server starts without errors
- [ ] Frontend server starts without errors
- [ ] API health endpoint responds
- [ ] validate-setup.sh passes all checks

---

## 🎯 Success Criteria

You're ready for Phase 1 when:

1. ✅ All system prerequisites installed
2. ✅ Both database connections working
3. ✅ Both projects' dependencies installed
4. ✅ Backend API server running
5. ✅ Frontend dev server running
6. ✅ Health check endpoint responding
7. ✅ validate-setup.sh showing 100% pass rate

---

## 📞 Support

If you encounter any issues:

1. Check logs:
   ```bash
   cat backend/storage/logs/debug.log
   ```

2. Run validation:
   ```bash
   ./validate-setup.sh
   ```

3. Test connections:
   ```bash
   ./test-connections.sh
   ```

4. Check documentation:
   - SETUP_COMPLETE_GUIDE.md (detailed setup)
   - README.md (quick start)
   - docs/4-PLANO_DE_EXECUCAO.md (execution plan)

---

## ✨ Next: Phase 1

Once all prerequisites are validated, you're ready to start Phase 1: Content Management API.

See: `NEXT_STEPS.md`

---

**Last Updated:** 22/02/2026  
**Version:** 0.1.0

