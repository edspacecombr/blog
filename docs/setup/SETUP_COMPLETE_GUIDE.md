# 📚 Guia Completo de Setup - Phase 0 (PostgreSQL + Redis)

**Data:** 22 de Fevereiro de 2026  
**Status:** Phase 0 Foundation Complete - Validação de Conexões  
**Stack:** PHP 8.1 + Next.js 14 + PostgreSQL 13+ + Redis 6+

---

## 🎯 Objetivos da Phase 0

✅ **Implementado:**
- Arquitetura de dois projetos desacoplados (Backend PHP + Frontend Next.js)
- Banco de dados PostgreSQL com 11+ tabelas normalizadas
- Cache com Redis (namespace 'blog:')
- Autenticação JWT com Sanctum
- CORS configurado
- Migrations de banco de dados
- Estrutura de projeto completa

⏳ **Aguardando Validação:**
- Instalação de dependências locais (PHP 8.1 + Composer)
- Teste de conexão PostgreSQL
- Teste de conexão Redis
- Execução de migrations
- Validação de endpoints da API
- Validação do frontend

---

## 🔧 Pré-requisitos de Sistema

### Requerido
- **PostgreSQL 13+** rodando em `localhost:5432`
  - Database: `blog_platform`
  - User: `blog_admin`
  - Password: `<USE_ENV>`
  
- **Redis 6+** rodando em `localhost:6379`
  - Password: `<USE_ENV>`

- **PHP 8.1+** com extensões:
  - pdo_pgsql
  - redis
  - json
  - curl
  - mbstring
  - xml

- **Composer** (gerenciador de pacotes PHP)

- **Node.js 18+** e **npm 9+**

---

## 📦 Instalação de Dependências Locais

### Passo 1: Instalar PHP 8.1 (Ubuntu/Debian/WSL2)

```bash
# Atualizar repositórios
sudo apt-get update

# Instalar PHP 8.1 CLI + extensões
sudo apt-get install -y \
  php8.1-cli \
  php8.1-pdo \
  php8.1-pgsql \
  php8.1-redis \
  php8.1-json \
  php8.1-curl \
  php8.1-mbstring \
  php8.1-xml

# Instalar Composer
sudo apt-get install -y composer

# Verificar instalação
php --version
composer --version
php -m | grep -E "pdo|pgsql|redis"
```

**Esperado:**
```
PHP 8.1.x (cli)
Composer version 2.x
pdo
pdo_pgsql
redis
```

### Passo 2: Instalar Dependências do Backend

```bash
cd /home/edblack/projetos/blog/backend

# Instalar pacotes PHP
composer install --no-interaction

# Verificar
ls -la vendor/
composer show | head -20
```

**Pacotes principais instalados:**
- monolog/monolog (logging)
- vlucas/phpdotenv (variáveis de ambiente)
- predis/predis (cliente Redis)
- illuminate/database (query builder)

### Passo 3: Instalar Dependências do Frontend

```bash
cd /home/edblack/projetos/blog/frontend

# Instalar pacotes npm
npm install

# Verificar
ls -la node_modules/
npm list --depth=0
```

**Pacotes principais instalados:**
- next (framework web)
- react (UI library)
- typescript (type safety)
- tailwindcss (styling)
- axios (HTTP client)

---

## ✅ Testes de Conexão

### Teste 1: PostgreSQL

```bash
# Conectar ao banco
psql -h localhost -U blog_admin -d blog_platform -W
# Password: <USE_ENV>

# Dentro do psql:
# Ver banco atual
SELECT current_database();
# Esperado: blog_platform

# Ver tabelas
\dt
# Esperado: 11+ tabelas (users, posts, categories, etc)

# Ver estrutura de uma tabela
\d users

# Sair
\q
```

**Tabelas esperadas:**
- users
- posts
- categories
- post_categories
- pages
- menus
- menu_items
- languages
- translations
- settings
- media

### Teste 2: Redis

```bash
# Conectar ao Redis
redis-cli -h localhost -p 6379 -a "<USE_ENV>"

# Dentro do redis-cli:
# Verificar conexão
PING
# Esperado: PONG

# Ver configurações
CONFIG GET requirepass
# Esperado: <USE_ENV>

# Monitorar cache
MONITOR

# Ver todas as chaves
KEYS *

# Ver estatísticas
INFO stats

# Sair
quit
```

### Teste 3: Backend API

```bash
# Terminal 1: Iniciar servidor backend
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public

# Terminal 2: Testar endpoint de saúde
curl http://localhost:8000/api/v1/health

# Esperado:
# {"status":"ok","timestamp":"2026-02-22T18:30:00Z"}

# Testar CORS
curl -i -X OPTIONS http://localhost:8000/api/v1/health \
  -H "Origin: http://localhost:3000"

# Esperado: Access-Control-Allow-Origin: http://localhost:3000
```

### Teste 4: Frontend

```bash
# Terminal 3: Iniciar servidor frontend
cd /home/edblack/projetos/blog/frontend
npm run dev

# Acessar no navegador
# http://localhost:3000

# Esperado:
# - Página de login renderiza
# - Next.js dev server em execução
# - TypeScript compila sem erros
```

---

## 🗄️  Executar Migrations

Se as tabelas ainda não existem, executar migrations:

```bash
cd /home/edblack/projetos/blog/backend

# Forma 1: Via PHP direto
php -r "
  require 'vendor/autoload.php';
  \$dotenv = new Dotenv\Dotenv(__DIR__);
  \$dotenv->load();
  \$migration = new \App\Database\Migration();
  \$migration->run();
"

# Forma 2: Via psql (manual)
# Copiar o schema SQL e executar no psql
PGPASSWORD=<USE_ENV> psql -h localhost -U blog_admin -d blog_platform < docs/database-schema.sql
```

---

## 🔐 Teste de Autenticação

### Registrar novo usuário

```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "Test@123456"
  }'

# Esperado:
# {
#   "message": "User registered successfully",
#   "user": {
#     "id": 1,
#     "email": "test@example.com",
#     "name": "Test User"
#   }
# }
```

### Login (obter token)

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "Test@123456"
  }'

# Esperado:
# {
#   "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
#   "expires_in": 86400,
#   "user": {
#     "id": 1,
#     "email": "test@example.com",
#     "name": "Test User"
#   }
# }
```

### Usar token em requisição protegida

```bash
# Substituir TOKEN com o valor obtido no login
TOKEN="eyJ0eXAiOiJKV1QiLCJhbGc..."

curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Authorization: Bearer $TOKEN"

# Esperado:
# {
#   "id": 1,
#   "email": "test@example.com",
#   "name": "Test User"
# }
```

---

## 📊 Validação Completa

Execute o script de validação para verificar tudo de uma vez:

```bash
cd /home/edblack/projetos/blog
chmod +x validate-setup.sh
./validate-setup.sh
```

**Esperado:**
```
✓ PHP installed
✓ Composer installed
✓ Node.js installed
✓ npm installed
✓ PostgreSQL CLI found
✓ Redis CLI found
✓ PHP PDO extension
✓ PHP PostgreSQL extension
✓ PostgreSQL connection successful
✓ Database tables exist
✓ Redis connection successful
✓ Redis password configured
...
✅ All checks passed!
```

---

## 🚀 Iniciar Desenvolvimento (3 Terminais)

### Terminal 1: Backend API

```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public
```

Output esperado:
```
Development Server started
Listening on http://localhost:8000
```

### Terminal 2: Frontend

```bash
cd /home/edblack/projetos/blog/frontend
npm run dev
```

Output esperado:
```
> next dev
  ▲ Next.js 14.0.0
  - Local:        http://localhost:3000
  - Environments: .env.local
```

### Terminal 3: Monitorar Redis (opcional)

```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>" MONITOR
```

---

## 📝 Arquivos de Configuração

### Backend: .env

```
APP_NAME="Professional Multilingual Blog"
APP_ENV=development
APP_DEBUG=true
APP_PORT=8000
APP_URL=http://localhost:8000

DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_admin
DB_PASSWORD=<USE_ENV>
DB_SSLMODE=prefer

REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=<USE_ENV>
REDIS_DB=0
REDIS_CACHE_DB=1

JWT_SECRET=your_jwt_secret_key
JWT_ALGORITHM=HS256
JWT_EXPIRATION=86400

FRONTEND_URL=http://localhost:3000
API_PREFIX=/api/v1
```

### Frontend: .env.local

```
NEXT_PUBLIC_API_URL=http://localhost:8000
NEXT_PUBLIC_API_PREFIX=/api/v1
```

---

## 🐛 Troubleshooting

### Erro: "PHP command not found"
```bash
# Verificar se PHP foi instalado
php --version

# Se não funcionar, instalar:
sudo apt-get install php8.1-cli

# Adicionar ao PATH se necessário
which php
```

### Erro: "Cannot connect to PostgreSQL"
```bash
# Verificar se PostgreSQL está rodando
sudo service postgresql status

# Se não estiver, iniciar:
sudo service postgresql start

# Verificar conexão
psql -h localhost -U blog_admin -d blog_platform
```

### Erro: "Cannot connect to Redis"
```bash
# Verificar se Redis está rodando
redis-cli PING

# Se não estiver, em outro terminal WSL2:
redis-server

# Verificar com autenticação
redis-cli -a "<USE_ENV>" PING
```

### Erro: "Port 8000 already in use"
```bash
# Mudar porta backend:
php -S localhost:8001 -t public

# Ou encontrar processo na porta:
sudo lsof -i :8000
sudo kill -9 <PID>
```

### Erro: "npm install fails"
```bash
# Limpar cache npm
npm cache clean --force

# Reinstalar
npm install
```

---

## 📚 Próximos Passos - Phase 1

Após validar que tudo está funcionando:

1. **Core API Backend** (Laravel-style, mas em PHP puro)
   - CRUD de Posts
   - CRUD de Categorias
   - CRUD de Páginas
   - Gestão de Autores
   - Sistema de Menus

2. **Multilíngue + SEO**
   - Suporte a múltiplos idiomas
   - Geração de sitemaps
   - hreflang tags
   - Schema JSON-LD

3. **Media & Editor**
   - Upload de mídia
   - Conversor WebP/AVIF
   - Editor GrapesJS no Next.js

4. **Admin Panel (Next.js)**
   - Dashboard
   - CRUD UI para conteúdo
   - Setup Wizard

5. **Frontend Público**
   - 3 layouts diferentes
   - SSR/ISR com Next.js
   - Paginação SEO
   - Performance otimizada

---

## 📞 Suporte

Para problemas:
1. Verificar logs: `cat backend/storage/logs/debug.log`
2. Executar validação: `./validate-setup.sh`
3. Consultar documentação: `docs/` diretório

---

**Última atualização:** 22/02/2026  
**Versão:** 0.1.0 (Phase 0)  
**Mantido por:** Equipe de Desenvolvimento

