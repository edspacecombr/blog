#!/bin/bash

##############################################################################
# Setup Script - Blog Platform (FASE 0 - Fundação)
# Stack: PHP 8.3 + Next.js 14 + PostgreSQL 15 + Redis 7.4
# Ambiente: WSL2 Linux
##############################################################################

set -e

echo "🚀 Iniciando setup do Blog Platform..."
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Verificar se está na raiz do projeto
if [ ! -f "README.md" ] || [ ! -d "backend" ] || [ ! -d "frontend" ]; then
    echo -e "${RED}✗ Execute este script da raiz do projeto!${NC}"
    exit 1
fi

echo -e "${BLUE}▶ Passo 1: Verificar requisitos do sistema${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Verificar PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}✗ PHP não encontrado!${NC}"
    exit 1
fi
PHP_VERSION=$(php --version | head -1)
echo -e "${GREEN}✓ PHP${NC}: $PHP_VERSION"

# Verificar Node
if ! command -v node &> /dev/null; then
    echo -e "${RED}✗ Node.js não encontrado!${NC}"
    exit 1
fi
NODE_VERSION=$(node --version)
echo -e "${GREEN}✓ Node.js${NC}: $NODE_VERSION"

# Verificar npm
if ! command -v npm &> /dev/null; then
    echo -e "${RED}✗ npm não encontrado!${NC}"
    exit 1
fi
NPM_VERSION=$(npm --version)
echo -e "${GREEN}✓ npm${NC}: $NPM_VERSION"

# Verificar extensões PHP
echo ""
echo -e "${BLUE}▶ Passo 2: Verificar extensões PHP${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

PHP_EXTENSIONS=("pgsql" "redis" "curl" "json")
for ext in "${PHP_EXTENSIONS[@]}"; do
    if php -m | grep -q "^$ext$"; then
        echo -e "${GREEN}✓${NC} Extensão $ext disponível"
    else
        echo -e "${YELLOW}⚠${NC} Extensão $ext não encontrada (opcional para alguns recursos)"
    fi
done

# Verificar conexão PostgreSQL
echo ""
echo -e "${BLUE}▶ Passo 3: Testar conexão PostgreSQL${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ -f "backend/.env" ]; then
    # Ler configurações do .env (sem expor valores)
    DB_HOST=$(grep "DB_HOST=" backend/.env | cut -d'=' -f2 | tr -d '"')
    DB_PORT=$(grep "DB_PORT=" backend/.env | cut -d'=' -f2 | tr -d '"')
    DB_DATABASE=$(grep "DB_DATABASE=" backend/.env | cut -d'=' -f2 | tr -d '"')
    
    if php -r "
        \$env_file = 'backend/.env';
        if (file_exists(\$env_file)) {
            \$lines = file(\$env_file, FILE_SKIP_EMPTY_LINES);
            foreach (\$lines as \$line) {
                if (strpos(\$line, '=') !== false && \$line[0] !== '#') {
                    list(\$key, \$value) = explode('=', \$line, 2);
                    \$key = trim(\$key);
                    \$value = trim(\$value, \"\r\n\\\"\");
                    putenv(\"\$key=\$value\");
                }
            }
        }
        
        try {
            \$host = getenv('DB_HOST') ?: 'localhost';
            \$port = getenv('DB_PORT') ?: 5432;
            \$database = getenv('DB_DATABASE') ?: 'blog_platform';
            \$user = getenv('DB_USER') ?: 'blog_admin';
            \$password = getenv('DB_PASSWORD') ?: '';
            
            \$dsn = \"pgsql:host=\$host;port=\$port;dbname=\$database;sslmode=disable\";
            \$pdo = new PDO(\$dsn, \$user, \$password);
            echo 'ok';
        } catch (Exception \$e) {
            echo 'fail';
        }
    " 2>/dev/null | grep -q "ok"; then
        echo -e "${GREEN}✓ PostgreSQL${NC} conectado com sucesso ($DB_HOST:$DB_PORT/$DB_DATABASE)"
    else
        echo -e "${RED}✗ PostgreSQL${NC} não conseguiu conectar em $DB_HOST:$DB_PORT"
        echo -e "${YELLOW}  Certifique-se que PostgreSQL está rodando no Docker/WSL2${NC}"
    fi
else
    echo -e "${YELLOW}⚠ Arquivo backend/.env não encontrado${NC}"
fi

# Verificar conexão Redis
echo ""
echo -e "${BLUE}▶ Passo 4: Testar conexão Redis${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if php -r "
    \$env_file = 'backend/.env';
    if (file_exists(\$env_file)) {
        \$lines = file(\$env_file, FILE_SKIP_EMPTY_LINES);
        foreach (\$lines as \$line) {
            if (strpos(\$line, '=') !== false && \$line[0] !== '#') {
                list(\$key, \$value) = explode('=', \$line, 2);
                \$key = trim(\$key);
                \$value = trim(\$value, \"\r\n\\\"\");
                putenv(\"\$key=\$value\");
            }
        }
    }
    
    try {
        if (extension_loaded('redis')) {
            \$redis = new Redis();
            \$host = getenv('REDIS_HOST') ?: 'localhost';
            \$port = getenv('REDIS_PORT') ?: 6379;
            \$password = getenv('REDIS_PASSWORD') ?: '';
            
            \$redis->connect(\$host, \$port, 3);
            if (\$password) {
                \$redis->auth(\$password);
            }
            \$redis->ping();
            echo 'ok';
        } else {
            echo 'ext_not_found';
        }
    } catch (Exception \$e) {
        echo 'fail';
    }
" 2>/dev/null | grep -q "ok"; then
    echo -e "${GREEN}✓ Redis${NC} conectado com sucesso"
elif php -r "
    \$env_file = 'backend/.env';
    if (file_exists(\$env_file)) {
        \$lines = file(\$env_file, FILE_SKIP_EMPTY_LINES);
        foreach (\$lines as \$line) {
            if (strpos(\$line, '=') !== false && \$line[0] !== '#') {
                list(\$key, \$value) = explode('=', \$line, 2);
                \$key = trim(\$key);
                \$value = trim(\$value, \"\r\n\\\"\");
                putenv(\"\$key=\$value\");
            }
        }
    }
    
    try {
        if (extension_loaded('redis')) {
            \$redis = new Redis();
            \$host = getenv('REDIS_HOST') ?: 'localhost';
            \$port = getenv('REDIS_PORT') ?: 6379;
            \$password = getenv('REDIS_PASSWORD') ?: '';
            
            \$redis->connect(\$host, \$port, 3);
            if (\$password) {
                \$redis->auth(\$password);
            }
            \$redis->ping();
            echo 'ok';
        } else {
            echo 'ext_not_found';
        }
    } catch (Exception \$e) {
        echo 'fail';
    }
" 2>/dev/null | grep -q "ext_not_found"; then
    echo -e "${YELLOW}⚠ Extensão Redis do PHP não disponível${NC}"
    echo -e "${YELLOW}  Instalação: pecl install redis${NC}"
else
    echo -e "${YELLOW}⚠ Redis pode não estar conectando${NC}"
fi

# Instalar dependências Backend
echo ""
echo -e "${BLUE}▶ Passo 5: Instalar dependências Backend${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

cd backend

if [ -f "../composer.phar" ]; then
    echo -e "${YELLOW}→ Instalando com php composer.phar...${NC}"
    php ../composer.phar install --no-dev -q
    echo -e "${GREEN}✓ Composer dependências instaladas${NC}"
elif command -v composer &> /dev/null; then
    echo -e "${YELLOW}→ Instalando com composer...${NC}"
    composer install --no-dev -q
    echo -e "${GREEN}✓ Composer dependências instaladas${NC}"
else
    echo -e "${RED}✗ Composer não encontrado!${NC}"
    exit 1
fi

cd ..

# Instalar dependências Frontend
echo ""
echo -e "${BLUE}▶ Passo 6: Instalar dependências Frontend${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

cd frontend
if [ -f "package.json" ]; then
    echo -e "${YELLOW}→ Instalando npm packages...${NC}"
    npm install -q
    echo -e "${GREEN}✓ NPM dependências instaladas${NC}"
else
    echo -e "${RED}✗ package.json não encontrado em frontend!${NC}"
    exit 1
fi
cd ..

# Executar migrações
echo ""
echo -e "${BLUE}▶ Passo 7: Executar migrações do banco de dados${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ -f "backend/migrate.php" ]; then
    OUTPUT=$(cd backend && php migrate.php 2>&1)
    if echo "$OUTPUT" | grep -q "completed successfully"; then
        echo -e "${GREEN}✓ Migrações completadas${NC}"
        echo "$OUTPUT" | grep "✓"
    else
        echo -e "${YELLOW}⚠ Migrações podem já estar rodadas${NC}"
    fi
else
    echo -e "${YELLOW}⚠ migrate.php não encontrado${NC}"
fi

# Resumo final
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo -e "${GREEN}✅ Setup concluído com sucesso!${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo -e "${BLUE}📋 Próximos passos:${NC}"
echo ""
echo "1. ${YELLOW}Iniciar Backend:${NC}"
echo "   cd backend && php -S localhost:8000 -t public"
echo ""
echo "2. ${YELLOW}Iniciar Frontend (novo terminal):${NC}"
echo "   cd frontend && npm run dev"
echo ""
echo "3. ${YELLOW}Verificar endpoints:${NC}"
echo "   curl http://localhost:8000/api/v1/health"
echo "   curl http://localhost:3000/"
echo ""
echo "4. ${YELLOW}Ver próximas tarefas (FASE 1):${NC}"
echo "   cat docs/guides/NEXT_STEPS.md"
echo ""
echo -e "${BLUE}📚 Documentação:${NC} docs/INDEX.md"
echo -e "${BLUE}🗂️  Plano de Execução:${NC} docs/4-PLANO_DE_EXECUCAO.md"
echo ""
