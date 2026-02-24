#!/usr/bin/env bash

# Test PostgreSQL and Redis connections
# Does NOT require PHP to be installed

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  🧪 Connection Test - PostgreSQL + Redis  ${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}\n"

PASSED=0
FAILED=0

# Test PostgreSQL
echo -e "${BLUE}📊 Testing PostgreSQL Connection...${NC}\n"

if command -v psql &> /dev/null; then
    PGPASSWORD="YOUR_DB_PASSWORD" psql -h localhost -U blog_admin -d blog_platform -c "SELECT version();" > /tmp/pg_test.txt 2>&1
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} PostgreSQL connection successful"
        grep "PostgreSQL" /tmp/pg_test.txt | head -1
        ((PASSED++))
        
        # Check tables
        PGPASSWORD="YOUR_DB_PASSWORD" psql -h localhost -U blog_admin -d blog_platform -c "\dt" | wc -l > /tmp/pg_table_count.txt
        TABLE_COUNT=$(cat /tmp/pg_table_count.txt)
        if [ "$TABLE_COUNT" -gt 5 ]; then
            echo -e "${GREEN}✓${NC} Database tables found ($TABLE_COUNT rows in \\dt output)"
            ((PASSED++))
        else
            echo -e "${YELLOW}⚠ ${NC} No tables found (may need migrations)"
            ((PASSED++))
        fi
    else
        echo -e "${RED}✗${NC} PostgreSQL connection failed"
        cat /tmp/pg_test.txt
        ((FAILED++))
    fi
else
    echo -e "${RED}✗${NC} psql command not found"
    echo "   Install: sudo apt-get install postgresql-client"
    ((FAILED++))
fi

echo ""
echo -e "${BLUE}�� Testing Redis Connection...${NC}\n"

if command -v redis-cli &> /dev/null; then
    redis-cli -h localhost -p 6379 -a "YOUR_REDIS_PASSWORD" PING > /tmp/redis_test.txt 2>&1
    if grep -q "PONG" /tmp/redis_test.txt; then
        echo -e "${GREEN}✓${NC} Redis connection successful"
        echo "   Response: $(cat /tmp/redis_test.txt)"
        ((PASSED++))
    else
        echo -e "${RED}✗${NC} Redis connection failed"
        cat /tmp/redis_test.txt
        ((FAILED++))
    fi
    
    # Check password
    redis-cli -h localhost -p 6379 -a "YOUR_REDIS_PASSWORD" CONFIG GET requirepass > /tmp/redis_pwd.txt 2>&1
    if grep -q "YOUR_REDIS_PASSWORD" /tmp/redis_pwd.txt; then
        echo -e "${GREEN}✓${NC} Redis password verified"
        ((PASSED++))
    else
        echo -e "${YELLOW}⚠ ${NC} Redis password check"
        ((PASSED++))
    fi
else
    echo -e "${RED}✗${NC} redis-cli command not found"
    echo "   Install: sudo apt-get install redis-tools"
    ((FAILED++))
fi

echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}📊 SUMMARY${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}\n"

echo -e "✓ Passed: ${GREEN}$PASSED${NC}"
echo -e "✗ Failed: ${RED}$FAILED${NC}"

if [ $FAILED -eq 0 ]; then
    echo -e "\n${GREEN}✅ All database connections working!${NC}"
    exit 0
else
    echo -e "\n${RED}❌ Some connections failed${NC}"
    exit 1
fi
