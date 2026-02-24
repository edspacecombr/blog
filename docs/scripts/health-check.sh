#!/bin/bash

# Environment Health Check Script
# Validates PHP 8.1 environment for NEXT_STEPS.md validation

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}════════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  🔍 Environment Health Check${NC}"
echo -e "${BLUE}════════════════════════════════════════════════════════════════${NC}\n"

ERRORS=0

# Check PHP
echo -e "${BLUE}PHP Installation:${NC}"
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -1)
    echo -e "${GREEN}✓${NC} $PHP_VERSION"
else
    echo -e "${RED}✗${NC} PHP not installed"
    ERRORS=$((ERRORS + 1))
fi

# Check PHP Extensions
echo -e "\n${BLUE}PHP Extensions:${NC}"

# PDO
if php -m 2>/dev/null | grep -qi "^pdo$"; then
    echo -e "${GREEN}✓${NC} PDO"
else
    echo -e "${RED}✗${NC} PDO"
    ERRORS=$((ERRORS + 1))
fi

# PostgreSQL PDO
if php -m 2>/dev/null | grep -qi "pdo_pgsql"; then
    echo -e "${GREEN}✓${NC} PDO PostgreSQL"
else
    echo -e "${RED}✗${NC} PDO PostgreSQL"
    ERRORS=$((ERRORS + 1))
fi

# Redis
if php -m 2>/dev/null | grep -qi "redis"; then
    echo -e "${GREEN}✓${NC} Redis"
else
    echo -e "${RED}✗${NC} Redis"
    ERRORS=$((ERRORS + 1))
fi

# cURL
if php -m 2>/dev/null | grep -qi "curl"; then
    echo -e "${GREEN}✓${NC} cURL"
else
    echo -e "${RED}✗${NC} cURL"
    ERRORS=$((ERRORS + 1))
fi

# JSON
if php -m 2>/dev/null | grep -qi "json"; then
    echo -e "${GREEN}✓${NC} JSON"
else
    echo -e "${RED}✗${NC} JSON"
    ERRORS=$((ERRORS + 1))
fi

# mbstring
if php -m 2>/dev/null | grep -qi "mbstring"; then
    echo -e "${GREEN}✓${NC} mbstring"
else
    echo -e "${RED}✗${NC} mbstring"
    ERRORS=$((ERRORS + 1))
fi

# Composer
echo -e "\n${BLUE}Composer:${NC}"
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version)
    echo -e "${GREEN}✓${NC} $COMPOSER_VERSION"
else
    echo -e "${RED}✗${NC} Composer not installed"
    ERRORS=$((ERRORS + 1))
fi

# Node.js
echo -e "\n${BLUE}Node.js:${NC}"
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    echo -e "${GREEN}✓${NC} Node.js $NODE_VERSION"
else
    echo -e "${YELLOW}⚠${NC} Node.js not installed (optional)"
fi

# npm
echo -e "\n${BLUE}npm:${NC}"
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm -v)
    echo -e "${GREEN}✓${NC} npm $NPM_VERSION"
else
    echo -e "${YELLOW}⚠${NC} npm not installed (optional)"
fi

# Project Files
echo -e "\n${BLUE}Project Structure:${NC}"

if [ -f "backend/composer.json" ]; then
    echo -e "${GREEN}✓${NC} backend/composer.json"
else
    echo -e "${RED}✗${NC} backend/composer.json"
    ERRORS=$((ERRORS + 1))
fi

if [ -d "backend/vendor" ]; then
    echo -e "${GREEN}✓${NC} backend/vendor (dependencies installed)"
else
    echo -e "${YELLOW}⚠${NC} backend/vendor (run composer install)"
fi

if [ -f "backend/.env" ]; then
    echo -e "${GREEN}✓${NC} backend/.env (configured)"
else
    echo -e "${YELLOW}⚠${NC} backend/.env (missing)"
fi

if [ -f "frontend/package.json" ]; then
    echo -e "${GREEN}✓${NC} frontend/package.json"
else
    echo -e "${RED}✗${NC} frontend/package.json"
    ERRORS=$((ERRORS + 1))
fi

if [ -d "frontend/node_modules" ]; then
    echo -e "${GREEN}✓${NC} frontend/node_modules (dependencies installed)"
else
    echo -e "${YELLOW}⚠${NC} frontend/node_modules (run npm install)"
fi

# Database Tools
echo -e "\n${BLUE}Database Tools:${NC}"

if command -v psql &> /dev/null; then
    PSQL_VERSION=$(psql --version)
    echo -e "${GREEN}✓${NC} PostgreSQL CLI: $PSQL_VERSION"
else
    echo -e "${YELLOW}⚠${NC} PostgreSQL CLI not installed (optional, for direct queries)"
fi

if command -v redis-cli &> /dev/null; then
    REDIS_VERSION=$(redis-cli --version)
    echo -e "${GREEN}✓${NC} Redis CLI: $REDIS_VERSION"
else
    echo -e "${YELLOW}⚠${NC} Redis CLI not installed (optional, for direct queries)"
fi

# Summary
echo -e "\n${BLUE}════════════════════════════════════════════════════════════════${NC}"

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ All critical components installed!${NC}"
else
    echo -e "${RED}❌ $ERRORS critical component(s) missing${NC}"
    echo -e "${YELLOW}Run: ./install-php8-env.sh${NC}"
fi

echo -e "${BLUE}════════════════════════════════════════════════════════════════${NC}\n"

# Next Steps
echo -e "${BLUE}📋 Next Steps:${NC}\n"
echo "1. Provide PostgreSQL + Redis connection details"
echo "2. Run: php validate-connections.php"
echo "3. Start backend: php -S localhost:8000 -t public"
echo "4. Start frontend: npm run dev"
echo ""

exit $ERRORS
