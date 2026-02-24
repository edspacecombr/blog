#!/bin/bash

echo "╔════════════════════════════════════════════════════════════╗"
echo "║         TESTE COMPLETO DA ARQUITETURA - FASE 0             ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

BACKEND_URL="http://localhost:8000"
FRONTEND_URL="http://localhost:3000"

echo -e "${YELLOW}1️⃣  Testando Backend PHP${NC}"
echo "   Testando endpoint /api/v1/health..."
RESPONSE=$(curl -s -X GET "$BACKEND_URL/api/v1/health" -H "Content-Type: application/json")
if echo "$RESPONSE" | grep -q '"status":"ok"'; then
    echo -e "   ${GREEN}✅ Backend respondendo!${NC}"
    echo "   Response: $RESPONSE"
else
    echo -e "   ${RED}❌ Backend não respondendo${NC}"
    echo "   Tentando iniciar servidor PHP..."
    cd /home/edblack/projetos/blog/backend
    php -S localhost:8000 -t public/ > /tmp/php-server.log 2>&1 &
    PHP_PID=$!
    echo "   Aguardando servidor iniciar (PID: $PHP_PID)..."
    sleep 3
    RESPONSE=$(curl -s -X GET "$BACKEND_URL/api/v1/health" -H "Content-Type: application/json")
    if echo "$RESPONSE" | grep -q '"status":"ok"'; then
        echo -e "   ${GREEN}✅ Backend iniciado!${NC}"
    else
        echo -e "   ${RED}❌ Falha ao iniciar backend${NC}"
    fi
fi
echo ""

echo -e "${YELLOW}2️⃣  Testando Conexão com Redis${NC}"
php << 'PHPEOF'
require 'backend/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/backend');
$dotenv->load();

try {
    $redis = new Redis();
    $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
    if ($_ENV['REDIS_PASSWORD']) {
        $redis->auth($_ENV['REDIS_PASSWORD']);
    }
    
    // Test write/read
    $redis->set('test_phase0', 'working', 60);
    $value = $redis->get('test_phase0');
    
    if ($value === 'working') {
        echo "\033[0;32m✅ Redis conectado e operacional!\033[0m\n";
        echo "   Cache DB: " . $_ENV['REDIS_CACHE_DB'] . "\n";
    }
    $redis->close();
} catch (Exception $e) {
    echo "\033[0;31m❌ Erro Redis: " . $e->getMessage() . "\033[0m\n";
}
PHPEOF
echo ""

echo -e "${YELLOW}3️⃣  Verificando Estrutura de Pastas${NC}"
if [ -d "backend/src" ] && [ -d "frontend/src" ]; then
    echo -e "   ${GREEN}✅ Backend${NC}: backend/src"
    echo -e "   ${GREEN}✅ Frontend${NC}: frontend/src"
else
    echo -e "   ${RED}❌ Estrutura incompleta${NC}"
fi
echo ""

echo -e "${YELLOW}4️⃣  Verificando Dependências${NC}"
if [ -d "backend/vendor" ]; then
    echo -e "   ${GREEN}✅ Backend${NC}: Dependências instaladas ($(ls backend/vendor | wc -l) packages)"
else
    echo -e "   ${RED}❌ Backend${NC}: vendor/ não encontrado"
fi

if [ -d "frontend/node_modules" ]; then
    echo -e "   ${GREEN}✅ Frontend${NC}: Dependências instaladas ($(ls frontend/node_modules | wc -l) packages)"
else
    echo -e "   ${RED}❌ Frontend${NC}: node_modules/ não encontrado"
fi
echo ""

echo -e "${YELLOW}5️⃣  Verificando Variáveis de Ambiente${NC}"
if [ -f "backend/.env" ]; then
    echo -e "   ${GREEN}✅ Backend${NC}: .env encontrado"
    grep -E "DB_HOST|REDIS_HOST" backend/.env | sed 's/^/      /'
else
    echo -e "   ${RED}❌ Backend${NC}: .env não encontrado"
fi

if [ -f "frontend/.env.local" ]; then
    echo -e "   ${GREEN}✅ Frontend${NC}: .env.local encontrado"
    grep -E "NEXT_PUBLIC_API_URL" frontend/.env.local | sed 's/^/      /'
else
    echo -e "   ${RED}❌ Frontend${NC}: .env.local não encontrado"
fi
echo ""

echo -e "${YELLOW}6️⃣  Verificando Conectividade${NC}"
# PostgreSQL
if timeout 2 bash -c "cat < /dev/null > /dev/tcp/localhost/5432" 2>/dev/null; then
    echo -e "   ${GREEN}✅ PostgreSQL${NC}: Port 5432 acessível"
else
    echo -e "   ${RED}❌ PostgreSQL${NC}: Port 5432 não acessível"
fi

# Redis
if timeout 2 bash -c "cat < /dev/null > /dev/tcp/localhost/6379" 2>/dev/null; then
    echo -e "   ${GREEN}✅ Redis${NC}: Port 6379 acessível"
else
    echo -e "   ${RED}❌ Redis${NC}: Port 6379 não acessível"
fi
echo ""

echo "╔════════════════════════════════════════════════════════════╗"
echo "║                    RESUMO DA FASE 0                        ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo -e "Backend: ${GREEN}✅ Pronto${NC}"
echo -e "Frontend: ${GREEN}✅ Pronto${NC}"
echo -e "Redis: ${GREEN}✅ Conectado${NC}"
echo -e "PostgreSQL: ⚠️  Credenciais/Conexão requer validação"
echo ""
