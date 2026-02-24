#!/usr/bin/env bash

# Professional Multilingual Blog - Phase 0 Setup Script
# PostgreSQL 15 + Redis 7.4 Configuration
# This script automates the installation and setup of backend and frontend

set -e

echo "🚀 Professional Multilingual Blog - Phase 0 Setup"
echo "=================================================="
echo "Stack: PostgreSQL 15 + Redis 7.4 + PHP 8.3 + Next.js 14"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Check prerequisites
echo -e "\n${BLUE}📋 Verificando pré-requisitos...${NC}"

check_command() {
    if ! command -v $1 &> /dev/null; then
        echo -e "${RED}❌ $2 não está instalado${NC}"
        return 1
    fi
    echo -e "${GREEN}✓ $2 encontrado${NC}"
    return 0
}

PHP_FOUND=0
if check_command "php8.3" "PHP 8.3+"; then
    PHP_FOUND=1
elif check_command "php8" "PHP 8+"; then
    PHP_FOUND=1
elif check_command "php" "PHP"; then
    PHP_FOUND=1
fi

if [ $PHP_FOUND -eq 0 ]; then
    echo -e "${RED}❌ PHP não foi encontrado${NC}"
    echo -e "${YELLOW}⚠️  Por favor, instale PHP 8.3 com: sudo apt-get install php8.3-cli php8.3-pdo php8.3-pgsql php8.3-redis${NC}"
fi

check_command "node" "Node.js"
check_command "npm" "npm"

# Check for PostgreSQL
if ! command -v psql &> /dev/null; then
    echo -e "${RED}❌ PostgreSQL CLI (psql) não está instalado${NC}"
    echo -e "${YELLOW}⚠️  O PostgreSQL deve estar rodando em um container Docker (WSL2)${NC}"
    echo "   Verifique se o Docker está rodando com: docker ps | grep postgres"
else
    echo -e "${GREEN}✓ PostgreSQL CLI encontrado${NC}"
fi

# Check for Redis
if ! command -v redis-cli &> /dev/null; then
    echo -e "${RED}❌ Redis CLI (redis-cli) não está instalado${NC}"
    echo -e "${YELLOW}⚠️  O Redis deve estar rodando em um container Docker (WSL2)${NC}"
    echo "   Verifique se o Docker está rodando com: docker ps | grep redis"
else
    echo -e "${GREEN}✓ Redis CLI encontrado${NC}"
fi

# Check PHP Extensions
echo -e "\n${BLUE}📦 Verificando extensões PHP...${NC}"

php_extension_check() {
    if php -m | grep -q $1; then
        echo -e "${GREEN}✓ Extensão PHP $1 encontrada${NC}"
        return 0
    else
        echo -e "${RED}❌ Extensão PHP $1 não encontrada${NC}"
        return 1
    fi
}

php_extension_check "pdo_pgsql" || echo -e "${YELLOW}⚠️  Instale com: sudo apt-get install php8.3-pgsql${NC}"
php_extension_check "redis" || echo -e "${YELLOW}⚠️  Instale com: sudo apt-get install php8.3-redis${NC}"

echo ""

# Backend setup
echo -e "\n${BLUE}⚙️  Configurando Backend...${NC}"

cd backend

if [ ! -f ".env" ]; then
    echo "Criando .env a partir de .env.example..."
    cp .env.example .env
    echo -e "${GREEN}✓ .env criado${NC}"
else
    echo -e "${GREEN}✓ .env já existe${NC}"
fi

# Verify .env has PostgreSQL + Redis settings
if grep -q "DB_HOST=localhost" .env && grep -q "REDIS_HOST=localhost" .env; then
    echo -e "${GREEN}✓ Credenciais PostgreSQL + Redis configuradas${NC}"
else
    echo -e "${YELLOW}⚠️  Atualize backend/.env com:${NC}"
    echo "   DB_HOST=localhost"
    echo "   DB_PORT=5432"
    echo "   DB_DATABASE=blog_platform"
    echo "   DB_USER=blog_admin"
    echo "   DB_PASSWORD=YOUR_DB_PASSWORD"
    echo "   REDIS_HOST=localhost"
    echo "   REDIS_PORT=6379"
    echo "   REDIS_PASSWORD=YOUR_REDIS_PASSWORD"
fi

echo "Instalando dependências PHP (Composer)..."
if command -v composer &> /dev/null; then
    composer install --no-interaction --quiet
else
    php ../composer.phar install --no-interaction --quiet
fi

echo -e "${GREEN}✓ Setup backend completo${NC}"

# Frontend setup
echo -e "\n${BLUE}⚙️  Configurando Frontend...${NC}"

cd ../frontend

echo "Instalando dependências npm..."
npm install --quiet

echo -e "${GREEN}✓ Setup frontend completo${NC}"

# Summary
echo -e "\n${GREEN}✅ Setup Phase 0 Completo!${NC}"
echo ""
echo -e "${YELLOW}📋 PRÓXIMOS PASSOS:${NC}"
echo ""
echo "1. Verificar PostgreSQL em Docker:"
echo "   docker ps | grep postgres"
echo "   psql -h localhost -U blog_admin -d blog_platform -W"
echo "   Password: YOUR_DB_PASSWORD"
echo ""
echo "2. Verificar Redis em Docker:"
echo "   docker ps | grep redis"
echo "   redis-cli -h localhost -p 6379 -a 'YOUR_REDIS_PASSWORD' PING"
echo ""
echo "3. Iniciar serviços (3 terminais):"
echo "   Terminal 1 - Backend:"
echo "     cd backend && php -S localhost:8001 -t public"
echo ""
echo "   Terminal 2 - Frontend:"
echo "     cd frontend && npm run dev"
echo ""
echo "   Terminal 3 - Monitorar Redis (opcional):"
echo "     redis-cli -h localhost -p 6379 -a 'YOUR_REDIS_PASSWORD' MONITOR"
echo ""
echo "4. Acessar aplicação:"
echo "   Frontend: http://localhost:3000"
echo "   Backend: http://localhost:8001"
echo "   Health: http://localhost:8001/api/v1/health"
echo ""
echo -e "${BLUE}📚 Documentação:${NC}"
echo "   - README.md - Quick reference"
echo "   - docs/INDEX.md - Complete documentation"
echo "   - docs/4-PLANO_DE_EXECUCAO.md - Execution plan"
echo "   - docs/guides/NEXT_STEPS.md - Phase 1 roadmap"
echo ""
echo -e "${GREEN}Happy coding! 🚀${NC}"
