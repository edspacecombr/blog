# 🚀 PHP 8.1 Local Environment Setup Guide

Este guia ajuda a preparar o ambiente local PHP 8.1 para validar os NEXT_STEPS.md, com PostgreSQL e Redis rodando em Docker em outra instância WSL2.

**Data**: 22 de Fevereiro de 2026  
**Stack**: PHP 8.1 + PostgreSQL 13.0+ + Redis 6.0+  
**Ambiente**: Ubuntu 24.04.1 LTS (WSL2 Local)

---

## 📋 Pré-requisitos

### Local (WSL2 - Esta Instância)
- [ ] Ubuntu 24.04.1 LTS ou equivalente
- [ ] Acesso a sudo (para instalação de pacotes)
- [ ] Conexão de rede para Docker WSL2 remoto

### Remoto (Docker WSL2 - Outra Instância)
- [ ] PostgreSQL 13.0+ rodando em Docker
- [ ] Redis 6.0+ rodando em Docker
- [ ] Portas 5432 (PostgreSQL) e 6379 (Redis) acessíveis
- [ ] Credenciais de acesso configuradas

---

## ✅ Passo 1: Instalação do PHP 8.1

### Opção A: Script Automático (Recomendado)

```bash
chmod +x install-php8-env.sh
./install-php8-env.sh
```

O script irá:
1. ✅ Verificar sistema
2. ✅ Atualizar gerenciador de pacotes
3. ✅ Instalar PHP 8.1 + extensões
4. ✅ Instalar Composer
5. ✅ Instalar Node.js + npm
6. ✅ Validar instalação
7. ✅ Instalar dependências do projeto

### Opção B: Instalação Manual

Se preferir instalar manualmente:

```bash
# Atualizar repositórios
sudo apt-get update

# Instalar PHP 8.1 com extensões críticas
sudo apt-get install -y \
  php8.1-cli \
  php8.1-pdo \
  php8.1-pgsql \
  php8.1-redis \
  php8.1-json \
  php8.1-curl \
  php8.1-mbstring \
  php8.1-xml \
  php8.1-fpm

# Instalar Composer
sudo apt-get install -y composer

# Instalar Node.js + npm (se não estiver)
sudo apt-get install -y nodejs npm

# Verificar instalação
php -v
composer --version
node -v
npm -v
```

### Verificar Extensões PHP

```bash
# Listar todas as extensões
php -m

# Verificar extensões críticas
php -r "echo extension_loaded('pdo_pgsql') ? '✓ PDO PostgreSQL' : '✗ PDO PostgreSQL'; echo PHP_EOL;"
php -r "echo extension_loaded('redis') ? '✓ Redis' : '✗ Redis'; echo PHP_EOL;"
```

---

## 🔗 Passo 2: Coletar Informações do Docker Remoto

Você precisa das credenciais do PostgreSQL e Redis que estão rodando em Docker em outra WSL2.

### Informações Necessárias

**PostgreSQL:**
```
Host: [OBTIDO DO DOCKER WSL2]
Port: [OBTIDO DO DOCKER WSL2]
Database: [OBTIDO DO DOCKER WSL2]
Username: [OBTIDO DO DOCKER WSL2]
Password: [OBTIDO DO DOCKER WSL2]
```

**Redis:**
```
Host: [OBTIDO DO DOCKER WSL2]
Port: [OBTIDO DO DOCKER WSL2]
Password: [OBTIDO DO DOCKER WSL2] (se existir)
```

### Como Obter de Docker WSL2 Remoto

**Para PostgreSQL:**
```bash
# Na WSL2 remota com Docker
docker ps | grep postgres
docker inspect [CONTAINER_ID] | grep -i ipaddress
# Ou verificar docker-compose.yml para host e porta

# Obter credenciais
docker exec [CONTAINER_ID] env | grep POSTGRES
```

**Para Redis:**
```bash
# Na WSL2 remota com Docker
docker ps | grep redis
docker inspect [CONTAINER_ID] | grep -i ipaddress
# Ou verificar docker-compose.yml para host e porta

# Testar conexão
docker exec [CONTAINER_ID] redis-cli ping
```

---

## 🧪 Passo 3: Validar Conexões

Execute o script de validação interativo:

```bash
chmod +x validate-connections.php
php validate-connections.php
```

O script:
1. ✅ Solicita credenciais PostgreSQL e Redis
2. ✅ Testa conexão PostgreSQL
3. ✅ Testa conexão Redis
4. ✅ Verifica schema PostgreSQL
5. ✅ Testa operações de cache
6. ✅ Gera arquivo .env

### Saída Esperada

```
════════════════════════════════════════════════════════════════════════════════
  🔗 PostgreSQL + Redis Connection Validator
════════════════════════════════════════════════════════════════════════════════

📋 Current Configuration:

🔵 PostgreSQL:
  • Host: [HOST]
  • Port: [PORT]
  • Database: blog_platform
  • User: blog_user
  • Password: ***

🔵 Redis:
  • Host: [HOST]
  • Port: 6379
  • Password: (none)
  • Database: 0

❓ Use these settings? (yes/no/customize): yes

════════════════════════════════════════════════════════════════════════════════
  🧪 Testing Connections
════════════════════════════════════════════════════════════════════════════════

🔗 Testing PostgreSQL Connection...
✓ PostgreSQL Connection Successful!
  • Timestamp: 2026-02-22 15:56:03.526
  • Version: PostgreSQL 13.0...

🔗 Testing Redis Connection...
✓ Redis Connection Successful!
  • Ping: PONG
  • Version: 6.0.0
  • Database: 0

✓ All expected tables found!

✓ Cache operations working!
  • SET: OK
  • GET: OK
  • TTL: 60 seconds

════════════════════════════════════════════════════════════════════════════════
  ✅ All Connections Validated Successfully!
════════════════════════════════════════════════════════════════════════════════

📝 Generated .env Configuration:
[Arquivo .env será exibido]

✅ Environment ready for PHASE 1 validation!
```

---

## 🔧 Passo 4: Configurar Projeto

### Instalar Dependências Backend

```bash
cd backend
composer install
```

### Instalar Dependências Frontend

```bash
cd frontend
npm install
```

### Revisar Configuração .env

```bash
# Ver arquivo .env
cat backend/.env

# Editar se necessário
nano backend/.env
```

### Executar Migrations

```bash
cd backend
php -r "require 'vendor/autoload.php'; \$m = new \App\Database\Migration(); \$m->run();"
```

---

## 🚀 Passo 5: Iniciar Desenvolvimento

### Terminal 1: Backend PHP Server

```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public
```

Esperado:
```
Development Server running on http://127.0.0.1:8000
```

### Terminal 2: Frontend Next.js Server

```bash
cd /home/edblack/projetos/blog/frontend
npm run dev
```

Esperado:
```
> next dev
  ▲ Next.js 14.0.0
  ✓ Ready in 2.5s
  ✓ Compiled client and server successfully
  ○ Listening on http://localhost:3000
```

### Terminal 3: Monitorar Logs (Opcional)

```bash
# Monitorar conexões Redis
redis-cli MONITOR

# Ou verificar PostgreSQL
psql -h [REDIS_HOST] -U [DB_USER] -d blog_platform -c "SELECT * FROM users LIMIT 1;"
```

---

## 🧪 Validação NEXT_STEPS.md

Após iniciar os servidores, valide as funcionalidades descritas em NEXT_STEPS.md:

### 1. Health Check API

```bash
curl http://localhost:8000/api/v1/health
```

Resposta esperada:
```json
{
  "status": "ok",
  "timestamp": "2026-02-22T15:56:03.526Z"
}
```

### 2. Teste de Autenticação

```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "name": "Test User",
    "password": "testpass123",
    "role": "author"
  }'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "testpass123"
  }'
```

### 3. Frontend Access

```bash
# Abrir no navegador
http://localhost:3000
```

### 4. Cache Validation

```bash
# Verificar operações de cache Redis
redis-cli
MONITOR  # Monitorar em tempo real
```

---

## ⚠️ Troubleshooting

### Erro: "PHP: command not found"

```bash
# Instalar PHP
sudo apt-get install -y php8.1-cli php8.1-pdo php8.1-pgsql

# Verificar
php -v
```

### Erro: "PDO PostgreSQL extension not found"

```bash
# Instalar extensão
sudo apt-get install -y php8.1-pgsql

# Habilitar em php.ini
sudo phpenmod pdo_pgsql

# Verificar
php -m | grep pgsql
```

### Erro: "Cannot connect to PostgreSQL"

```bash
# Verificar host/porta do Docker
docker ps
docker inspect [POSTGRES_CONTAINER] | grep IPAddress

# Testar conectividade
psql -h [HOST] -U [USER] -d [DB] -c "SELECT 1;"
```

### Erro: "Cannot connect to Redis"

```bash
# Testar conectividade
redis-cli -h [HOST] -p [PORT] ping

# Verificar Redis status
docker exec [REDIS_CONTAINER] redis-cli ping
```

### Erro: "Port 8000/3000 already in use"

```bash
# Encontrar processo usando porta
lsof -i :8000
lsof -i :3000

# Matar processo
kill -9 [PID]

# Ou usar porta diferente
php -S localhost:8001 -t public
```

---

## 📊 Verificação de Saúde do Ambiente

Execute este script para verificar o ambiente completo:

```bash
#!/bin/bash

echo "🔍 Environment Health Check"
echo ""

echo "PHP:"
php -v | head -1

echo ""
echo "PHP Modules:"
php -r "echo extension_loaded('pdo_pgsql') ? '✓ PDO PostgreSQL' : '✗ PDO PostgreSQL'; echo PHP_EOL;"
php -r "echo extension_loaded('redis') ? '✓ Redis' : '✗ Redis'; echo PHP_EOL;"

echo ""
echo "Composer:"
composer --version | head -1

echo ""
echo "Node.js:"
node -v

echo ""
echo "npm:"
npm -v

echo ""
echo "Project Files:"
test -f backend/composer.json && echo "✓ backend/composer.json" || echo "✗ backend/composer.json"
test -d backend/vendor && echo "✓ backend/vendor" || echo "✗ backend/vendor"
test -f frontend/package.json && echo "✓ frontend/package.json" || echo "✗ frontend/package.json"
test -d frontend/node_modules && echo "✓ frontend/node_modules" || echo "✗ frontend/node_modules"

echo ""
echo "✅ Health check complete"
```

---

## 📚 Documentação Relacionada

- **NEXT_STEPS.md** - Próximas ações para Phase 1
- **docs/POSTGRESQL_REDIS_SETUP.md** - Setup PostgreSQL + Redis
- **docs/DATABASE_SCHEMA.md** - Schema PostgreSQL
- **README.md** - Visão geral do projeto

---

## 🆘 Suporte

Se encontrar problemas:

1. 📖 Consulte a documentação em `docs/`
2. 🔍 Verifique os logs do Backend: `backend/storage/logs/`
3. 📊 Valide conexões: `php validate-connections.php`
4. 💻 Verifique Docker remoto para PostgreSQL/Redis

---

**Status**: ✅ Pronto para PHASE 1 Validation  
**Última Atualização**: 22 de Fevereiro de 2026
