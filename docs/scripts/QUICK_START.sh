#!/bin/bash

# Quick Start Script - FASE 0 Blog Platform
# Use este script para iniciar o projeto rapidamente

echo "🚀 Quick Start - Blog Platform"
echo "==============================="
echo ""

# Cores
GREEN='\033[92m'
BLUE='\033[94m'
RESET='\033[0m'

# Verificar se está no diretório correto
if [ ! -f "README.md" ]; then
    echo "❌ Execute este script da raiz do projeto"
    exit 1
fi

echo "📋 Checklist rápido:"
echo ""

# Verificar PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | grep "PHP" | awk '{print $2}')
    echo -e "${GREEN}✓${RESET} PHP $PHP_VERSION"
else
    echo "❌ PHP não encontrado"
    exit 1
fi

# Verificar Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    echo -e "${GREEN}✓${RESET} Node.js $NODE_VERSION"
else
    echo "❌ Node.js não encontrado"
    exit 1
fi

# Verificar npm
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm -v)
    echo -e "${GREEN}✓${RESET} npm $NPM_VERSION"
else
    echo "❌ npm não encontrado"
    exit 1
fi

echo ""
echo "🎯 Próximas etapas:"
echo ""
echo -e "${BLUE}Terminal 1 - Backend API:${RESET}"
echo "  cd backend"
echo "  php -S localhost:8000 -t public"
echo ""
echo -e "${BLUE}Terminal 2 - Frontend:${RESET}"
echo "  cd frontend"
echo "  npm run dev"
echo ""
echo -e "${BLUE}Acessar:${RESET}"
echo "  • Frontend: http://localhost:3000"
echo "  • Backend: http://localhost:8000/api/v1/health"
echo ""
echo "📚 Documentação:"
echo "  cat docs/INDEX.md"
echo "  cat NEXT_STEPS.md"
echo ""
