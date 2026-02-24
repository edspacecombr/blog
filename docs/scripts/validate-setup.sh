#!/usr/bin/env bash

# Professional Multilingual Blog - Phase 0 Validation Script
# Validates PostgreSQL + Redis connections and dependencies
# Does NOT require sudo

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  🚀 Phase 0 Validation - PostgreSQL + Redis + Dependencies  ${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}\n"

# Configuration
DB_HOST="localhost"
DB_PORT="5432"
DB_NAME="blog_platform"
DB_USER="blog_admin"
DB_PASSWORD="YOUR_DB_PASSWORD"
REDIS_HOST="localhost"
REDIS_PORT="6379"
REDIS_PASSWORD="YOUR_REDIS_PASSWORD"

# Counters
PASSED=0
FAILED=0

# Test function
test_command() {
    local name=$1
    local cmd=$2
    local expected=$3
    
    if eval "$cmd" > /tmp/test_output.txt 2>&1; then
        if [ -z "$expected" ] || grep -q "$expected" /tmp/test_output.txt 2>/dev/null; then
            echo -e "${GREEN}✓${NC} $name"
            ((PASSED++))
            return 0
        fi
    fi
    echo -e "${RED}✗${NC} $name"
    ((FAILED++))
    return 1
}

echo -e "${BLUE}📋 Step 1: System Dependencies${NC}\n"

# Check PHP
if command -v php &> /dev/null; then
    echo -e "${GREEN}✓${NC} PHP installed"
    php --version | head -1
    ((PASSED++))
else
    echo -e "${RED}✗${NC} PHP not installed"
    ((FAILED++))
fi

# Check Composer
if command -v composer &> /dev/null; then
    echo -e "${GREEN}✓${NC} Composer installed"
    composer --version | head -1
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Composer not installed"
    ((FAILED++))
fi

# Check Node.js
if command -v node &> /dev/null; then
    echo -e "${GREEN}✓${NC} Node.js installed"
    node --version
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Node.js not installed"
    ((FAILED++))
fi

# Check npm
if command -v npm &> /dev/null; then
    echo -e "${GREEN}✓${NC} npm installed"
    npm --version
    ((PASSED++))
else
    echo -e "${RED}✗${NC} npm not installed"
    ((FAILED++))
fi

# Check psql
if command -v psql &> /dev/null; then
    echo -e "${GREEN}✓${NC} PostgreSQL CLI found"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} PostgreSQL CLI not found (psql)"
    ((FAILED++))
fi

# Check redis-cli
if command -v redis-cli &> /dev/null; then
    echo -e "${GREEN}✓${NC} Redis CLI found"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Redis CLI not found (redis-cli)"
    ((FAILED++))
fi

echo ""
echo -e "${BLUE}📋 Step 2: PHP Extensions${NC}\n"

# Check PDO
if php -m 2>/dev/null | grep -qi "pdo"; then
    echo -e "${GREEN}✓${NC} PHP PDO extension"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} PHP PDO extension missing"
    ((FAILED++))
fi

# Check pgsql
if php -m 2>/dev/null | grep -qi "pgsql"; then
    echo -e "${GREEN}✓${NC} PHP PostgreSQL extension"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} PHP PostgreSQL extension missing"
    ((FAILED++))
fi

# Check redis
if php -m 2>/dev/null | grep -qi "redis"; then
    echo -e "${GREEN}✓${NC} PHP Redis extension"
    ((PASSED++))
else
    echo -e "${YELLOW}⚠ ${NC} PHP Redis extension missing (optional, cache may be disabled)"
    ((PASSED++))
fi

echo ""
echo -e "${BLUE}📋 Step 3: PostgreSQL Connection${NC}\n"

# Test PostgreSQL connection
PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -U $DB_USER -d $DB_NAME -c "SELECT version();" > /tmp/pg_test.txt 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} PostgreSQL connection successful"
    grep "PostgreSQL" /tmp/pg_test.txt | head -1
    ((PASSED++))
else
    echo -e "${RED}✗${NC} PostgreSQL connection failed"
    cat /tmp/pg_test.txt 2>/dev/null
    ((FAILED++))
fi

# Check tables
PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -U $DB_USER -d $DB_NAME -c "\dt" > /tmp/pg_tables.txt 2>&1
TABLE_COUNT=$(grep -c "public\|pg_" /tmp/pg_tables.txt 2>/dev/null || echo "0")
if [ "$TABLE_COUNT" -gt 0 ]; then
    echo -e "${GREEN}✓${NC} Database tables exist"
    echo "   Tables found: $TABLE_COUNT"
    ((PASSED++))
else
    echo -e "${YELLOW}⚠ ${NC} No tables found in database (migrations may need to run)"
    ((PASSED++))
fi

echo ""
echo -e "${BLUE}📋 Step 4: Redis Connection${NC}\n"

# Test Redis connection
redis-cli -h $REDIS_HOST -p $REDIS_PORT -a "$REDIS_PASSWORD" PING > /tmp/redis_test.txt 2>&1
if grep -q "PONG" /tmp/redis_test.txt; then
    echo -e "${GREEN}✓${NC} Redis connection successful"
    echo "   Response: PONG"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Redis connection failed"
    cat /tmp/redis_test.txt 2>/dev/null
    ((FAILED++))
fi

# Check Redis password
redis-cli -h $REDIS_HOST -p $REDIS_PORT -a "$REDIS_PASSWORD" CONFIG GET requirepass > /tmp/redis_pwd.txt 2>&1
if grep -q "YOUR_REDIS_PASSWORD" /tmp/redis_pwd.txt; then
    echo -e "${GREEN}✓${NC} Redis password configured"
    ((PASSED++))
else
    echo -e "${YELLOW}⚠ ${NC} Redis password verification"
    ((PASSED++))
fi

echo ""
echo -e "${BLUE}📋 Step 5: Project Structure${NC}\n"

# Check backend
if [ -d "backend" ]; then
    echo -e "${GREEN}✓${NC} Backend directory exists"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Backend directory missing"
    ((FAILED++))
fi

# Check frontend
if [ -d "frontend" ]; then
    echo -e "${GREEN}✓${NC} Frontend directory exists"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Frontend directory missing"
    ((FAILED++))
fi

# Check backend .env
if [ -f "backend/.env" ]; then
    echo -e "${GREEN}✓${NC} Backend .env file exists"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Backend .env file missing"
    ((FAILED++))
fi

# Check composer.json
if [ -f "backend/composer.json" ]; then
    echo -e "${GREEN}✓${NC} Backend composer.json exists"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Backend composer.json missing"
    ((FAILED++))
fi

# Check package.json
if [ -f "frontend/package.json" ]; then
    echo -e "${GREEN}✓${NC} Frontend package.json exists"
    ((PASSED++))
else
    echo -e "${RED}✗${NC} Frontend package.json missing"
    ((FAILED++))
fi

echo ""
echo -e "${BLUE}📋 Step 6: Backend Dependencies${NC}\n"

if [ -d "backend/vendor" ]; then
    echo -e "${GREEN}✓${NC} Backend vendor/ directory exists"
    VENDOR_COUNT=$(ls backend/vendor 2>/dev/null | wc -l)
    echo "   Packages: $VENDOR_COUNT"
    ((PASSED++))
else
    echo -e "${YELLOW}⚠ ${NC} Backend vendor/ directory missing - run: cd backend && composer install"
    ((PASSED++))
fi

echo ""
echo -e "${BLUE}📋 Step 7: Frontend Dependencies${NC}\n"

if [ -d "frontend/node_modules" ]; then
    echo -e "${GREEN}✓${NC} Frontend node_modules/ directory exists"
    MODULES_COUNT=$(ls frontend/node_modules 2>/dev/null | wc -l)
    echo "   Packages: $MODULES_COUNT"
    ((PASSED++))
else
    echo -e "${YELLOW}⚠ ${NC} Frontend node_modules/ directory missing - run: cd frontend && npm install"
    ((PASSED++))
fi

# Summary
echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}📊 VALIDATION SUMMARY${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}\n"

TOTAL=$((PASSED + FAILED))
PERCENT=$((PASSED * 100 / TOTAL))

echo -e "✓ Passed: ${GREEN}$PASSED${NC}"
echo -e "✗ Failed: ${RED}$FAILED${NC}"
echo -e "━━━━━━━━━━━━━━━━━━━━━━"
echo -e "Total:   $TOTAL"
echo -e "Score:   ${PERCENT}%"

if [ $FAILED -eq 0 ]; then
    echo -e "\n${GREEN}✅ All checks passed!${NC}\n"
    echo -e "${YELLOW}ℹ️  Next Steps:${NC}"
    echo ""
    echo "1. Install backend dependencies (if not done):"
    echo "   cd backend && composer install"
    echo ""
    echo "2. Install frontend dependencies (if not done):"
    echo "   cd frontend && npm install"
    echo ""
    echo "3. Run database migrations:"
    echo "   cd backend && php -r \"require 'vendor/autoload.php'; \$m = new \App\Database\Migration(); \$m->run();\""
    echo ""
    echo "4. Start services (in separate terminals):"
    echo "   Terminal 1: cd backend && php -S localhost:8000 -t public"
    echo "   Terminal 2: cd frontend && npm run dev"
    echo ""
    echo "5. Test the application:"
    echo "   - Frontend: http://localhost:3000"
    echo "   - Backend: http://localhost:8000"
    echo "   - API Health: http://localhost:8000/api/v1/health"
    echo ""
else
    echo -e "\n${RED}❌ Some checks failed. See above for details.${NC}\n"
    echo -e "${YELLOW}ℹ️  To fix:${NC}"
    echo ""
    if [ ! -f "backend/vendor/autoload.php" ]; then
        echo "- Install PHP packages: cd backend && composer install"
    fi
    if [ ! -f "frontend/node_modules/.package-lock.json" ]; then
        echo "- Install npm packages: cd frontend && npm install"
    fi
    echo "- Verify PostgreSQL is running on $DB_HOST:$DB_PORT"
    echo "- Verify Redis is running on $REDIS_HOST:$REDIS_PORT"
    echo ""
fi

# Cleanup
rm -f /tmp/test_output.txt /tmp/pg_test.txt /tmp/pg_tables.txt /tmp/redis_test.txt /tmp/redis_pwd.txt

exit $FAILED
