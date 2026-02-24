#!/bin/bash

##############################################################################
#                    Blog Platform - Setup Script
#         Instala dependências, cria .env e valida ambiente
##############################################################################

set -e

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKEND_DIR="$PROJECT_ROOT/backend"
FRONTEND_DIR="$PROJECT_ROOT/frontend"

# Cores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}================================${NC}"
echo -e "${YELLOW}Blog Platform - Setup Script${NC}"
echo -e "${YELLOW}================================${NC}\n"

# Step 1: Verificar requisitos
echo -e "${YELLOW}[1/5] Verificando requisitos...${NC}"

if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP não encontrado. Instale PHP 8.3+${NC}"
    exit 1
fi

if ! command -v node &> /dev/null; then
    echo -e "${RED}❌ Node.js não encontrado. Instale Node.js 20+${NC}"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    echo -e "${RED}❌ npm não encontrado.${NC}"
    exit 1
fi

echo -e "${GREEN}✅ PHP $(php -v | head -n1 | cut -d' ' -f2)${NC}"
echo -e "${GREEN}✅ Node.js $(node -v)${NC}"
echo -e "${GREEN}✅ npm $(npm -v)${NC}\n"

# Step 2: Instalar dependências Backend
echo -e "${YELLOW}[2/5] Instalando dependências Backend...${NC}"
cd "$BACKEND_DIR"

if [ -f "composer.phar" ]; then
    php composer.phar install --quiet
    echo -e "${GREEN}✅ Backend dependências instaladas${NC}\n"
else
    echo -e "${YELLOW}⚠️  composer.phar não encontrado. Use: php composer-installer.php${NC}"
fi

# Step 3: Instalar dependências Frontend
echo -e "${YELLOW}[3/5] Instalando dependências Frontend...${NC}"
cd "$FRONTEND_DIR"

npm install --silent 2>/dev/null || npm install
echo -e "${GREEN}✅ Frontend dependências instaladas${NC}\n"

# Step 4: Criar arquivos .env
echo -e "${YELLOW}[4/5] Configurando arquivos .env...${NC}"

# Backend .env
if [ ! -f "$BACKEND_DIR/.env" ]; then
    cat > "$BACKEND_DIR/.env" << 'ENVEOF'
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_admin
DB_PASSWORD=b4m@#62P
DB_SSLMODE=disable

REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=d4m@!62R

APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

CORS_ALLOWED_ORIGINS=http://localhost:3000

JWT_SECRET=your_jwt_secret_key_change_in_production_12345678
API_RATE_LIMIT=60
ENVEOF
    echo -e "${GREEN}✅ backend/.env criado${NC}"
else
    echo -e "${YELLOW}ℹ️  backend/.env já existe${NC}"
fi

# Frontend .env.local
if [ ! -f "$FRONTEND_DIR/.env.local" ]; then
    cat > "$FRONTEND_DIR/.env.local" << 'ENVEOF'
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
ENVEOF
    echo -e "${GREEN}✅ frontend/.env.local criado${NC}"
else
    echo -e "${YELLOW}ℹ️  frontend/.env.local já existe${NC}"
fi

echo ""

# Step 5: Validar ambiente
echo -e "${YELLOW}[5/5] Validando conexões...${NC}"

# Teste PHP
cat > /tmp/test-setup.php << 'PHPEOF'
<?php
$errors = [];

// Test PostgreSQL
try {
    $pdo = new PDO(
        "pgsql:host=localhost;port=5432;dbname=blog_platform;sslmode=disable",
        "blog_admin",
        "b4m@#62P",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ PostgreSQL conectado\n";
} catch (Exception $e) {
    $errors[] = "PostgreSQL: " . $e->getMessage();
}

// Test Redis
try {
    $redis = new Redis();
    $redis->connect('localhost', 6379);
    $redis->auth('d4m@!62R');
    echo "✅ Redis conectado\n";
} catch (Exception $e) {
    $errors[] = "Redis: " . $e->getMessage();
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "⚠️  $error\n";
    }
    exit(1);
}

echo "\n✅ Ambiente validado!\n";
PHPEOF

php /tmp/test-setup.php

echo ""
echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}Setup Completo!${NC}"
echo -e "${GREEN}================================${NC}"
echo ""
echo -e "Próximos passos:"
echo -e "  1. Backend:  ${YELLOW}cd backend && php -S localhost:8000 -t public${NC}"
echo -e "  2. Frontend: ${YELLOW}cd frontend && npm run dev${NC}"
echo ""
echo -e "API estará em:   http://localhost:8000/api/v1"
echo -e "Frontend em:     http://localhost:3000"
echo ""
echo -e "Documentação:    ${YELLOW}docs/INDEX.md${NC}"
echo -e "Próximos passos: ${YELLOW}docs/guides/NEXT_STEPS.md${NC}"
echo ""
