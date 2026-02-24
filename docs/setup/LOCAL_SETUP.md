# 🚀 Setup Local - FASE 0

## Pré-requisitos

- ✅ PHP 8.1+ com extensões `pdo_pgsql` e `redis`
- ✅ Node.js 18+ com npm
- ✅ PostgreSQL 13+ rodando em localhost:5432
- ✅ Redis 6.0+ rodando em localhost:6379

## Instalação Rápida

```bash
cd /home/edblack/projetos/blog

# Executar script de setup
bash setup-phase0.sh
```

## Instalação Manual

### 1. Backend (PHP)

```bash
cd backend

# Instalar dependências
composer install
# ou: php composer.phar install

# Verificar .env
cat .env
# Deve conter:
# DB_HOST=localhost
# DB_USER=blog_admin
# DB_PASSWORD=<USE_ENV>
# REDIS_HOST=localhost
# REDIS_PASSWORD=<USE_ENV>

# Iniciar servidor
php -S localhost:8000 -t public/
```

### 2. Frontend (Next.js)

```bash
cd frontend

# Instalar dependências
npm install

# Verificar .env.local
cat .env.local
# Deve conter:
# NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1

# Iniciar desenvolvimento
npm run dev
```

## Validação

### Health Check - Backend

```bash
curl http://localhost:8000/api/v1/health
# Resposta esperada:
# {"status":"ok","timestamp":"2026-02-22T23:25:00+00:00"}
```

### Frontend

```bash
# Acesso no navegador
http://localhost:3000
```

### Banco de Dados

#### PostgreSQL
```bash
# Verificar porta
nc -zv localhost 5432
# Connected to localhost (127.0.0.1) port 5432 [tcp/postgresql] succeeded!
```

#### Redis
```bash
# Conectar e testar
redis-cli -a "<USE_ENV>" ping
# PONG
```

## Estrutura de Diretórios

```
blog/
├── backend/
│   ├── src/
│   │   ├── App.php
│   │   ├── Controllers/
│   │   │   └── AuthController.php
│   │   ├── Auth/
│   │   │   ├── AuthService.php
│   │   │   └── JWTAuth.php
│   │   ├── Database/
│   │   │   └── Connection.php
│   │   ├── Cache/
│   │   │   └── CacheService.php
│   │   └── Middleware/
│   ├── public/
│   │   └── index.php
│   ├── vendor/
│   ├── .env
│   └── composer.json
├── frontend/
│   ├── src/
│   │   ├── app/
│   │   │   ├── page.tsx
│   │   │   ├── layout.tsx
│   │   │   └── (pages)
│   │   ├── components/
│   │   └── lib/
│   ├── public/
│   ├── node_modules/
│   ├── .env.local
│   ├── package.json
│   └── next.config.js
├── docs/
│   ├── INDEX.md
│   ├── 4-PLANO_DE_EXECUCAO.md
│   ├── 3-MODELO_DE_DADOS.md
│   ├── setup/
│   ├── database/
│   ├── api/
│   └── guide/
└── docker-compose.yml
```

## Variáveis de Ambiente

### Backend (.env)

```env
# Server
APP_NAME="Professional Multilingual Blog"
APP_ENV=development
APP_DEBUG=true
APP_PORT=8000
APP_URL=http://localhost:8000

# Database (PostgreSQL)
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_admin
DB_PASSWORD=<USE_ENV>
DB_SSLMODE=prefer

# Redis (Cache & Session)
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=<USE_ENV>
REDIS_DB=0
REDIS_CACHE_DB=1

# JWT
JWT_SECRET=your_jwt_secret_key_change_me_in_production_2026
JWT_ALGORITHM=HS256
JWT_EXPIRATION=86400

# Admin
ADMIN_EMAIL=admin@blog.local
ADMIN_PASSWORD=admin_password_change_me

# Frontend
FRONTEND_URL=http://localhost:3000

# API
API_PREFIX=/api/v1
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:8000
```

### Frontend (.env.local)

```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
```

## Troubleshooting

### Porta 8000 já em uso

```bash
# Encontrar processo
lsof -i :8000

# Matar processo
kill -9 <PID>

# Usar outra porta
php -S localhost:8001 -t public/
```

### Conexão PostgreSQL falha

```bash
# Testar conexão
php validate-db-connection.php

# Saída esperada:
# ✅ PostgreSQL conectado
# ✅ Redis conectado
```

### Node modules corrompidos

```bash
cd frontend
rm -rf node_modules package-lock.json
npm install
```

## Próxima Fase

Após validar setup local:

1. Revisar [NEXT_STEPS_PHASE0.md](../NEXT_STEPS_PHASE0.md)
2. Consultar [Plano de Execução FASE 1](../4-PLANO_DE_EXECUCAO.md)
3. Iniciar implementação de migrations

## Referências

- [PostgreSQL Setup](../database/POSTGRESQL.md)
- [Redis Setup](../database/REDIS.md)
- [Documentação Index](../INDEX.md)
