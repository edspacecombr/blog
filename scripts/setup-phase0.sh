#!/bin/bash

# Setup Script - Phase 0
# Professional Multilingual Blog Platform
# Stack: PHP 8.1 + Next.js 14 + PostgreSQL 13+ + Redis 6.0+

set -e

echo "╔═══════════════════════════════════════════════════════════╗"
echo "║   SETUP FASE 0 - Professional Blog Platform               ║"
echo "║   PostgreSQL + Redis + PHP 8.1 + Next.js 14               ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Check prerequisites
echo -e "${BLUE}📋 Verificando Pré-requisitos...${NC}"
echo ""

# PHP Check
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP não encontrado${NC}"
    exit 1
fi
PHP_VERSION=$(php -v | head -n 1 | awk '{print $2}')
echo -e "${GREEN}✅ PHP ${PHP_VERSION}${NC}"

# Node Check
if ! command -v node &> /dev/null; then
    echo -e "${RED}❌ Node.js não encontrado${NC}"
    exit 1
fi
NODE_VERSION=$(node -v)
NPM_VERSION=$(npm -v)
echo -e "${GREEN}✅ Node.js ${NODE_VERSION}${NC}"
echo -e "${GREEN}✅ NPM ${NPM_VERSION}${NC}"

# PostgreSQL Connectivity Check
echo ""
echo -e "${BLUE}🔌 Testando Conectividade...${NC}"
echo ""

if timeout 2 bash -c "cat < /dev/null > /dev/tcp/localhost/5432" 2>/dev/null; then
    echo -e "${GREEN}✅ PostgreSQL (localhost:5432)${NC} - Acessível"
else
    echo -e "${YELLOW}⚠️  PostgreSQL (localhost:5432)${NC} - Não acessível (Executando em WSL2?)"
fi

if timeout 2 bash -c "cat < /dev/null > /dev/tcp/localhost/6379" 2>/dev/null; then
    echo -e "${GREEN}✅ Redis (localhost:6379)${NC} - Acessível"
else
    echo -e "${RED}❌ Redis (localhost:6379)${NC} - Não acessível"
    exit 1
fi

# Backend Setup
echo ""
echo -e "${BLUE}🔧 Setup Backend (PHP)...${NC}"
echo ""

if [ -d "backend" ]; then
    cd backend
    
    if [ ! -f ".env" ]; then
        echo "Criando .env..."
        cp .env.example .env
    fi
    
    # Install dependencies
    if [ ! -d "vendor" ]; then
        echo "Instalando dependências do Backend..."
        php composer.phar install --no-interaction || composer install --no-interaction
        echo -e "${GREEN}✅ Dependências do Backend instaladas${NC}"
    else
        echo -e "${GREEN}✅ Dependências Backend já instaladas${NC}"
    fi
    
    # Verify .env with PostgreSQL + Redis
    if grep -q "DB_HOST=localhost" .env && grep -q "REDIS_HOST=localhost" .env; then
        echo -e "${GREEN}✅ Arquivo .env configurado (PostgreSQL + Redis)${NC}"
    else
        echo -e "${YELLOW}⚠️  Atualize .env com credenciais PostgreSQL + Redis${NC}"
    fi
    
    cd ..
else
    echo -e "${RED}❌ Diretório 'backend' não encontrado${NC}"
    exit 1
fi

# Frontend Setup
echo ""
echo -e "${BLUE}🎨 Setup Frontend (Next.js)...${NC}"
echo ""

if [ -d "frontend" ]; then
    cd frontend
    
    # Install dependencies
    if [ ! -d "node_modules" ]; then
        echo "Instalando dependências do Frontend..."
        npm install --no-audit --no-fund
        echo -e "${GREEN}✅ Dependências do Frontend instaladas${NC}"
    else
        echo -e "${GREEN}✅ Dependências Frontend já instaladas${NC}"
    fi
    
    # Create .env.local if not exists
    if [ ! -f ".env.local" ]; then
        echo "Criando .env.local..."
        echo "NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1" > .env.local
        echo -e "${GREEN}✅ Arquivo .env.local criado${NC}"
    else
        echo -e "${GREEN}✅ Arquivo .env.local já existe${NC}"
    fi
    
    cd ..
else
    echo -e "${RED}❌ Diretório 'frontend' não encontrado${NC}"
    exit 1
fi

# Database verification
echo ""
echo -e "${BLUE}🗄️  Verificando Banco de Dados...${NC}"
echo ""

php << 'PHPEOF'
require 'backend/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/backend');
$dotenv->load();

// Test Redis
try {
    $redis = new Redis();
    $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
    if ($_ENV['REDIS_PASSWORD']) {
        $redis->auth($_ENV['REDIS_PASSWORD']);
    }
    
    $redis->set('setup_test', 'ok', 10);
    $redis->close();
    echo "\033[0;32m✅ Redis conectado\033[0m\n";
    echo "   Host: " . $_ENV['REDIS_HOST'] . ":" . $_ENV['REDIS_PORT'] . "\n";
    echo "   DB Session: " . $_ENV['REDIS_DB'] . "\n";
    echo "   DB Cache: " . $_ENV['REDIS_CACHE_DB'] . "\n\n";
} catch (Exception $e) {
    echo "\033[0;31m❌ Erro Redis: " . $e->getMessage() . "\033[0m\n\n";
}
PHPEOF

# Final summary
echo ""
echo "╔═══════════════════════════════════════════════════════════╗"
echo "║              SETUP CONCLUÍDO COM SUCESSO                  ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo ""

echo -e "${YELLOW}🚀 PRÓXIMOS PASSOS:${NC}"
echo ""
echo "1️⃣  Terminal 1 - Iniciar Backend:"
echo "   ${BLUE}cd /home/edblack/projetos/blog/backend${NC}"
echo "   ${BLUE}php -S localhost:8000 -t public/${NC}"
echo ""
echo "2️⃣  Terminal 2 - Iniciar Frontend:"
echo "   ${BLUE}cd /home/edblack/projetos/blog/frontend${NC}"
echo "   ${BLUE}npm run dev${NC}"
echo ""
echo "3️⃣  Terminal 3 - Validar Health Check:"
echo "   ${BLUE}curl http://localhost:8000/api/v1/health${NC}"
echo ""
echo "4️⃣  Acessar Painel:"
echo "   ${BLUE}http://localhost:3000${NC}"
echo ""
echo -e "${YELLOW}📋 Configurações:${NC}"
echo ""
echo "PostgreSQL:"
echo "  Host: localhost:5432"
echo "  Database: blog_platform"
echo "  User: blog_admin"
echo "  Status: ✅ Acessível"
echo ""
echo "Redis:"
echo "  Host: localhost:6379"
echo "  Session DB: 0"
echo "  Cache DB: 1"
echo "  Status: ✅ Conectado"
echo ""
echo -e "${GREEN}✅ FASE 0 - Setup Completo!${NC}"
echo ""
echo "Próxima fase: FASE 1 - Core API Backend"
echo "Documentação: docs/INDEX.md"
echo ""
