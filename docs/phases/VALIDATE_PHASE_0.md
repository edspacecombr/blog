# 🔍 VALIDAÇÃO FASE 0 - PostgreSQL + Redis

**Data:** 22 de Fevereiro de 2026  
**Status:** Pronto para Validação  
**Database:** PostgreSQL 13.0+ + Redis 6.0+

---

## 📋 Checklist de Validação

### 1️⃣ Pré-requisitos do Sistema

- [ ] PHP 8.1 ou superior instalado
- [ ] Composer instalado
- [ ] Node.js 16+ instalado
- [ ] PostgreSQL 13+ rodando em localhost:5432
- [ ] Redis 6+ rodando em localhost:6379

**Verificar com:**
```bash
php -v
composer --version
node -v
npm -v
psql --version
redis-cli --version
```

---

### 2️⃣ Validação do PostgreSQL

#### Conectar ao PostgreSQL:
```bash
psql -h localhost -U blog_admin -d blog_platform
```

**Senha:** `<USE_ENV>`

#### Verificar conexão bem-sucedida:
```sql
-- Verificar banco de dados
SELECT datname FROM pg_database WHERE datname = 'blog_platform';

-- Verificar usuário
SELECT usename FROM pg_user WHERE usename = 'blog_admin';

-- Verificar tabelas criadas
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public';

-- Esperado: 11 tabelas
```

**Tabelas esperadas:**
```
users
blog_settings
posts
categories
post_category
pages
menus
menu_items
media_library
languages
language_settings
```

---

### 3️⃣ Validação do Redis

#### Conectar ao Redis:
```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>"
```

#### Verificar conexão:
```bash
# Dentro do redis-cli
PING
# Esperado: PONG

INFO server
# Verificar versão e status

CONFIG GET requirepass
# Deve retornar a senha

QUIT
```

---

### 4️⃣ Setup do Backend (PHP)

#### Navegar para o backend:
```bash
cd backend
```

#### Verificar arquivo .env:
```bash
cat .env | grep -E "DB_|REDIS_"
```

**Deve conter:**
```
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_admin
DB_PASSWORD=<USE_ENV>
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=<USE_ENV>
```

#### Instalar dependências:
```bash
composer install
```

#### Verificar composer.json (deve ter pdo_pgsql + redis):
```bash
cat composer.json | grep -A 5 "require"
```

---

### 5️⃣ Validação das Migrations (PostgreSQL)

#### Executar migrations:
```bash
php -r "
  require 'vendor/autoload.php';
  \$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  \$dotenv->load();
  \$migration = new \App\Database\Migration();
  \$migration->run();
"
```

**Esperado:** Tabelas criadas no PostgreSQL

#### Verificar tabelas criadas:
```bash
psql -h localhost -U blog_admin -d blog_platform -c "
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public' ORDER BY table_name;
"
```

---

### 6️⃣ Validação da API Backend

#### Iniciar servidor (Terminal 1):
```bash
cd backend
php -S localhost:8000 -t public
```

**Esperado:** API rodando em http://localhost:8000

#### Testar health check (Terminal 2):
```bash
curl -X GET http://localhost:8000/api/v1/health \
  -H "Content-Type: application/json"
```

**Esperado:**
```json
{
  "status": "ok",
  "timestamp": "2026-02-22T16:30:00Z",
  "database": "connected",
  "redis": "connected"
}
```

---

### 7️⃣ Validação da Autenticação

#### Registrar novo usuário:
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "TestPass123!",
    "password_confirmation": "TestPass123!"
  }'
```

**Esperado:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "role": "author"
  }
}
```

#### Fazer login:
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "TestPass123!"
  }'
```

**Esperado:**
```json
{
  "success": true,
  "message": "Login successful",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "role": "author"
  }
}
```

#### Validar token JWT:
```bash
TOKEN="seu_token_aqui"
curl -X GET http://localhost:8000/api/v1/auth/validate \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
```

**Esperado:**
```json
{
  "success": true,
  "message": "Token is valid",
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "role": "author"
  }
}
```

---

### 8️⃣ Validação do Redis Cache

#### Verificar CacheService (Terminal 3):
```bash
cd backend
php -r "
  require 'vendor/autoload.php';
  \$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  \$dotenv->load();
  
  \$cache = new \App\Cache\CacheService('blog:');
  
  // Escrever no cache
  \$cache->set('test_key', ['data' => 'value123'], 3600);
  echo 'Cache set' . PHP_EOL;
  
  // Ler do cache
  \$value = \$cache->get('test_key');
  echo 'Cache get: ' . json_encode(\$value) . PHP_EOL;
  
  // Verificar existência
  \$exists = \$cache->exists('test_key');
  echo 'Cache exists: ' . (\$exists ? 'true' : 'false') . PHP_EOL;
"
```

**Esperado:**
```
Cache set
Cache get: {"data":"value123"}
Cache exists: true
```

#### Monitorar Redis em tempo real:
```bash
redis-cli -a "<USE_ENV>" MONITOR
```

---

### 9️⃣ Setup do Frontend (Next.js)

#### Navegar para o frontend (Terminal 4):
```bash
cd frontend
npm install
```

#### Verificar package.json:
```bash
cat package.json | grep -A 3 "dependencies"
```

#### Configurar NEXT_PUBLIC_API_URL:
```bash
cat .env.local 2>/dev/null || cat .env.example | head -5
```

**Deve conter:**
```
NEXT_PUBLIC_API_URL=http://localhost:8000
```

---

### 🔟 Iniciar Frontend

#### Iniciar servidor Next.js (Terminal 4):
```bash
npm run dev
```

**Esperado:** Frontend rodando em http://localhost:3000

#### Testar acesso:
```bash
curl -s http://localhost:3000 | head -20
```

---

### 1️⃣1️⃣ Validação Integrada

#### Testar fluxo completo:

**Terminal 1:** Backend API
```bash
cd backend && php -S localhost:8000 -t public
```

**Terminal 2:** Frontend
```bash
cd frontend && npm run dev
```

**Terminal 3:** Redis Monitor
```bash
redis-cli -a "<USE_ENV>" MONITOR
```

#### No navegador:
1. Acessar http://localhost:3000
2. Clicar em "Sign Up"
3. Registrar novo usuário
4. Fazer login
5. Acessar dashboard protegido
6. Verificar token JWT nos cookies/localStorage

#### No terminal (validação API):
```bash
# Obter token
TOKEN=$(curl -s -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"TestPass123!"}' | grep -o '"token":"[^"]*' | cut -d'"' -f4)

# Verificar token
curl -X GET http://localhost:8000/api/v1/auth/validate \
  -H "Authorization: Bearer $TOKEN"
```

---

## 📊 Resultados Esperados da FASE 0

### ✅ Backend
- [x] PHP 8.1+ configurado com PDO PostgreSQL + Redis extension
- [x] API respondendo em /api/v1/health com status "ok"
- [x] Autenticação JWT funcionando (register, login, validate)
- [x] PostgreSQL conectado com 11 tabelas criadas
- [x] Redis conectado e funcionando como cache layer
- [x] CORS configurado para frontend
- [x] Error handling implementado

### ✅ Frontend
- [x] Next.js 14 com TypeScript
- [x] Página de login e registro funcionando
- [x] Dashboard protegido (requer token)
- [x] Integração com API backend
- [x] Tailwind CSS estilizado

### ✅ Database
- [x] PostgreSQL com 11 tabelas
- [x] Migrations executadas com sucesso
- [x] Foreign keys configuradas
- [x] Indexes criados
- [x] UTF-8 encoding padrão

### ✅ Cache
- [x] Redis operacional
- [x] CacheService funcionando
- [x] TTL configurado
- [x] Namespace "blog:" ativo

---

## 🚨 Troubleshooting

### PostgreSQL não conecta
```bash
# Verificar se PostgreSQL está rodando
sudo systemctl status postgresql

# Iniciar PostgreSQL se necessário
sudo systemctl start postgresql

# Testar conexão
psql -h localhost -U blog_admin -d blog_platform -W
```

### Redis não conecta
```bash
# Verificar se Redis está rodando
redis-cli ping

# Iniciar Redis se necessário
redis-server --requirepass "<USE_ENV>"

# Ou no docker
docker exec -it redis redis-cli ping
```

### PHP extensions não encontradas
```bash
# Verificar extensões instaladas
php -m | grep -i pdo
php -m | grep -i pgsql
php -m | grep -i redis

# Instalar se necessário
sudo apt-get install php8.1-pgsql php8.1-redis
```

### Composer install com erro
```bash
# Limpar cache
composer clear-cache

# Reinstalar
composer install -vvv
```

---

## 📝 Próximos Passos Após Validação

Quando todos os checks passarem:

1. **Fase 1: Content Management**
   - Implementar CRUD de Posts
   - Implementar CRUD de Categorias
   - Upload de Mídia
   - Gestão de Páginas Estáticas

2. **Fase 2: Multilíngue + SEO**
   - Suporte a múltiplos idiomas
   - Hreflang automático
   - Schema.org markup
   - Sitemap generation

3. **Fase 3: Admin + Frontend Público**
   - Admin panel em Next.js
   - Frontend público com 3 layouts
   - GrapesJS editor

---

## ✅ Checklist de Validação Completa

```
SISTEMA:
[ ] PHP 8.1+ instalado
[ ] PostgreSQL 13+ conectado
[ ] Redis 6+ conectado
[ ] Node.js 16+ instalado

BACKEND:
[ ] composer install executado
[ ] .env configurado com credenciais
[ ] Migrations rodadas
[ ] API health check respondendo
[ ] Autenticação (register/login/validate) funcionando
[ ] CacheService testado
[ ] CORS configurado

FRONTEND:
[ ] npm install executado
[ ] Servidor Next.js iniciado
[ ] Página de login acessível
[ ] Página de registro acessível
[ ] API integration funcionando

DATABASE:
[ ] 11 tabelas criadas
[ ] Foreign keys intactas
[ ] Indexes criados
[ ] Dados de teste inseridos

INTEGRAÇÕES:
[ ] Token JWT gerado corretamente
[ ] Token validado na API
[ ] Cache invalidação funcionando
[ ] CORS permitindo requisições do frontend
```

---

## 📞 Suporte

Se encontrar problemas durante a validação:

1. Consulte `docs/` para guias detalhados
2. Verifique os logs em `backend/storage/logs`
3. Monitore Redis com `redis-cli MONITOR`
4. Use browser DevTools para verificar requisições

---

**FASE 0 está pronto para validação! 🚀**

Próximo: Executar este checklist com PostgreSQL + Redis rodando

Criado: 22 de Fevereiro de 2026
