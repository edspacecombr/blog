#!/usr/bin/env bash

# Professional Multilingual Blog - Phase 0 Setup Script
# PostgreSQL 13.0+ + Redis 6.0+ Configuration
# This script automates the installation and setup of backend and frontend

set -e

echo "🚀 Professional Multilingual Blog - Phase 0 Setup"
echo "=================================================="
echo "Stack: PostgreSQL 13.0+ + Redis 6.0+ + PHP 8.1 + Next.js 14"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Check prerequisites
echo -e "\n${BLUE}📋 Checking prerequisites...${NC}"

check_command() {
    if ! command -v $1 &> /dev/null; then
        echo -e "${RED}❌ $2 is not installed${NC}"
        return 1
    fi
    echo -e "${GREEN}✓ $2 found${NC}"
    return 0
}

check_command "php" "PHP 8.1+"
check_command "composer" "Composer"
check_command "node" "Node.js"
check_command "npm" "npm"

# Check for PostgreSQL
if ! command -v psql &> /dev/null; then
    echo -e "${RED}❌ PostgreSQL CLI (psql) is not installed${NC}"
    echo -e "${YELLOW}⚠️  Please install PostgreSQL 13+:${NC}"
    echo "   macOS: brew install postgresql@13"
    echo "   Ubuntu: sudo apt-get install postgresql-13"
    echo "   Windows: Download from https://www.postgresql.org/download/windows/"
    exit 1
fi
echo -e "${GREEN}✓ PostgreSQL CLI found${NC}"

# Check for Redis
if ! command -v redis-cli &> /dev/null; then
    echo -e "${RED}❌ Redis CLI (redis-cli) is not installed${NC}"
    echo -e "${YELLOW}⚠️  Please install Redis 6+:${NC}"
    echo "   macOS: brew install redis"
    echo "   Ubuntu: sudo apt-get install redis-server"
    echo "   Windows: Download from https://github.com/microsoftarchive/redis/releases"
    exit 1
fi
echo -e "${GREEN}✓ Redis CLI found${NC}"

# Check PHP Extensions
echo -e "\n${BLUE}📦 Checking PHP Extensions...${NC}"

php_extension_check() {
    if php -m | grep -q $1; then
        echo -e "${GREEN}✓ PHP $1 extension found${NC}"
        return 0
    else
        echo -e "${RED}❌ PHP $1 extension not found${NC}"
        return 1
    fi
}

php_extension_check "pdo_pgsql" || echo -e "${YELLOW}⚠️  Install: pecl install pdo_pgsql${NC}"
php_extension_check "redis" || echo -e "${YELLOW}⚠️  Install: pecl install redis${NC}"

echo ""

# Backend setup
echo -e "\n${BLUE}⚙️  Setting up Backend...${NC}"

cd backend

if [ ! -f ".env" ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
    echo -e "${GREEN}✓ .env created${NC}"
    echo -e "${YELLOW}⚠️  Please edit backend/.env with:${NC}"
    echo "   - PostgreSQL credentials (DB_HOST, DB_USER, DB_PASSWORD)"
    echo "   - Redis credentials (REDIS_HOST, REDIS_PORT)"
else
    echo -e "${YELLOW}⚠️  .env already exists${NC}"
fi

echo "Installing PHP dependencies..."
composer install --no-interaction

echo -e "${GREEN}✓ Backend setup complete${NC}"

# Frontend setup
echo -e "\n${BLUE}⚙️  Setting up Frontend...${NC}"

cd ../frontend

echo "Installing npm dependencies..."
npm install

echo -e "${GREEN}✓ Frontend setup complete${NC}"

# Database setup information
echo -e "\n${BLUE}🗄️  PostgreSQL + Redis Setup Required${NC}"

cd ..

echo -e "${YELLOW}⚠️  DATABASE SETUP (PostgreSQL 13+):${NC}"
echo ""
echo "1. Start PostgreSQL (if not running):"
echo "   macOS: brew services start postgresql@13"
echo "   Ubuntu: sudo service postgresql start"
echo ""
echo "2. Create database and user:"
echo "   sudo -u postgres psql"
echo "   CREATE DATABASE blog_platform ENCODING 'UTF8';"
echo "   CREATE USER blog_user WITH PASSWORD 'your_secure_password';"
echo "   GRANT ALL PRIVILEGES ON DATABASE blog_platform TO blog_user;"
echo "   \\c blog_platform"
echo "   GRANT ALL ON SCHEMA public TO blog_user;"
echo "   \\q"
echo ""
echo "3. Update backend/.env with PostgreSQL credentials:"
echo "   DB_HOST=localhost"
echo "   DB_PORT=5432"
echo "   DB_DATABASE=blog_platform"
echo "   DB_USER=blog_user"
echo "   DB_PASSWORD=your_secure_password"
echo ""

echo -e "${YELLOW}⚠️  REDIS SETUP (6+):${NC}"
echo ""
echo "1. Start Redis server:"
echo "   redis-server"
echo ""
echo "2. Verify Redis connection:"
echo "   redis-cli ping"
echo "   (should return: PONG)"
echo ""
echo "3. Update backend/.env with Redis config:"
echo "   REDIS_HOST=localhost"
echo "   REDIS_PORT=6379"
echo "   REDIS_PASSWORD=null (or set a password)"
echo "   REDIS_DB=0"
echo "   REDIS_CACHE_DB=1"
echo ""

# Summary
echo -e "\n${GREEN}✅ Phase 0 Setup Complete!${NC}"
echo ""
echo -e "${YELLOW}📋 NEXT STEPS:${NC}"
echo ""
echo "1. Configure PostgreSQL Database:"
echo "   Follow the database setup instructions above"
echo ""
echo "2. Configure Redis:"
echo "   Follow the Redis setup instructions above"
echo ""
echo "3. Update backend/.env file:"
echo "   nano backend/.env"
echo ""
echo "4. Run Database Migrations:"
echo "   cd backend"
echo "   php -r \"require 'vendor/autoload.php';"
echo "   \\$m = new \\App\\Database\\Migration();"
echo "   \\$m->run();\""
echo ""
echo "5. Start Services:"
echo "   Terminal 1: redis-server"
echo "   Terminal 2: cd backend && php -S localhost:8000 -t public"
echo "   Terminal 3: cd frontend && npm run dev"
echo ""
echo "6. Access the application:"
echo "   - Frontend: http://localhost:3000"
echo "   - Backend API: http://localhost:8000"
echo "   - Health Check: http://localhost:8000/api/v1/health"
echo ""
echo -e "${BLUE}📚 Documentation:${NC}"
echo "   - Setup Guide: docs/POSTGRESQL_REDIS_SETUP.md"
echo "   - Database Schema: docs/DATABASE_SCHEMA.md"
echo "   - Architecture: docs/ARCHITECTURE_OVERVIEW.md"
echo "   - Next Steps: NEXT_STEPS.md"
echo ""
echo -e "${GREEN}Happy coding! 🚀${NC}"
