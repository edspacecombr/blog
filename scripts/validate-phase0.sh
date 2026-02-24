#!/bin/bash

# Quick Validation Script for PHASE 0 - PostgreSQL + Redis
# Run this after starting backend and frontend

set -e

echo "🔍 PHASE 0 QUICK VALIDATION"
echo "═══════════════════════════════════════════════════════════════"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
API_URL="http://localhost:8000"
FRONTEND_URL="http://localhost:3000"
PG_HOST="localhost"
PG_PORT="5432"
PG_DB="blog_platform"
PG_USER="blog_admin"
PG_PASSWORD="b4m@#62P"
REDIS_HOST="localhost"
REDIS_PORT="6379"
REDIS_PASSWORD="d4m@!62R"

# Test counters
PASSED=0
FAILED=0

# Test function
test_endpoint() {
    local name=$1
    local method=$2
    local url=$3
    local data=$4
    
    echo -n "Testing $name... "
    
    if [ -n "$data" ]; then
        response=$(curl -s -X $method "$url" \
            -H "Content-Type: application/json" \
            -d "$data" 2>&1)
    else
        response=$(curl -s -X $method "$url" 2>&1)
    fi
    
    if echo "$response" | grep -q '"success"' || echo "$response" | grep -q '"status"'; then
        echo -e "${GREEN}✓ PASS${NC}"
        ((PASSED++))
    else
        echo -e "${RED}✗ FAIL${NC}"
        echo "Response: $response"
        ((FAILED++))
    fi
}

# Test PostgreSQL
echo -e "${BLUE}📊 Testing PostgreSQL Connection${NC}"
echo -n "PostgreSQL: "
if PGPASSWORD="$PG_PASSWORD" psql -h "$PG_HOST" -U "$PG_USER" -d "$PG_DB" -c "SELECT 1" >/dev/null 2>&1; then
    echo -e "${GREEN}✓ Connected${NC}"
    ((PASSED++))
else
    echo -e "${RED}✗ Connection Failed${NC}"
    ((FAILED++))
fi

# Test PostgreSQL tables
echo -n "Tables created: "
table_count=$(PGPASSWORD="$PG_PASSWORD" psql -h "$PG_HOST" -U "$PG_USER" -d "$PG_DB" -t -c "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='public';" 2>/dev/null || echo "0")
if [ "$table_count" = "11" ]; then
    echo -e "${GREEN}✓ 11 tables${NC}"
    ((PASSED++))
else
    echo -e "${RED}✗ Expected 11, got $table_count${NC}"
    ((FAILED++))
fi

echo ""

# Test Redis
echo -e "${BLUE}💚 Testing Redis Connection${NC}"
echo -n "Redis: "
if redis-cli -h "$REDIS_HOST" -p "$REDIS_PORT" -a "$REDIS_PASSWORD" ping 2>/dev/null | grep -q "PONG"; then
    echo -e "${GREEN}✓ Connected${NC}"
    ((PASSED++))
else
    echo -e "${RED}✗ Connection Failed${NC}"
    ((FAILED++))
fi

echo ""

# Test API endpoints
echo -e "${BLUE}📡 Testing API Endpoints${NC}"

# Health check
echo -n "Health Check: "
response=$(curl -s "$API_URL/api/v1/health" 2>&1)
if echo "$response" | grep -q '"status":"ok"'; then
    echo -e "${GREEN}✓ OK${NC}"
    ((PASSED++))
else
    echo -e "${RED}✗ FAIL${NC}"
    ((FAILED++))
fi

# Register
echo -n "Register Endpoint: "
response=$(curl -s -X POST "$API_URL/api/v1/auth/register" \
    -H "Content-Type: application/json" \
    -d '{
        "name": "Test User",
        "email": "test'"$(date +%s)"'@example.com",
        "password": "TestPass123!",
        "password_confirmation": "TestPass123!"
    }' 2>&1)
if echo "$response" | grep -q '"success"'; then
    echo -e "${GREEN}✓ Working${NC}"
    ((PASSED++))
else
    echo -e "${RED}✗ FAIL${NC}"
    ((FAILED++))
fi

echo ""

# Test Frontend
echo -e "${BLUE}🌐 Testing Frontend${NC}"
echo -n "Frontend Access: "
if curl -s "$FRONTEND_URL" | grep -q "html" >/dev/null 2>&1; then
    echo -e "${GREEN}✓ Responding${NC}"
    ((PASSED++))
else
    echo -e "${RED}✗ Not responding${NC}"
    ((FAILED++))
fi

echo ""
echo "═══════════════════════════════════════════════════════════════"
echo -e "${BLUE}Summary:${NC}"
echo -e "  ${GREEN}Passed: $PASSED${NC}"
echo -e "  ${RED}Failed: $FAILED${NC}"
echo "═══════════════════════════════════════════════════════════════"

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✅ All tests passed! PHASE 0 is ready.${NC}"
    exit 0
else
    echo -e "${RED}⚠️  Some tests failed. Check your setup.${NC}"
    exit 1
fi
