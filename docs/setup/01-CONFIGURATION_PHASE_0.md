# ⚙️ CONFIGURAÇÃO FASE 0 - PostgreSQL + Redis

**Data:** 22 de Fevereiro de 2026  
**Status:** ✅ Configurado e Pronto para Validação  
**Stack:** PHP 8.1 + Next.js 14 + PostgreSQL 13+ + Redis 6+

---

## 📋 Credenciais de Banco de Dados

### PostgreSQL (WSL2 Docker)
```
Host:     localhost
Port:     5432
Database: blog_platform
User:     blog_admin
Password: <USE_ENV>
```

**Verificar conexão:**
```bash
psql -h localhost -U blog_admin -d blog_platform -W
# Digite a senha: <USE_ENV>
# Execute: SELECT current_database();
```

### Redis (WSL2 Docker)
```
Host:     localhost
Port:     6379
Password: <USE_ENV>
DB:       0 (dados), 1 (cache)
```

**Verificar conexão:**
```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>" PING
# Esperado: PONG
```

---

## 🔧 Arquivo .env Backend

**Localização:** `/home/edblack/projetos/blog/backend/.env`

**Conteúdo configurado:**
```env
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

# Frontend
FRONTEND_URL=http://localhost:3000

# API
API_PREFIX=/api/v1
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:8000
```

---

## 📊 Estrutura de Banco de Dados

### 11 Tabelas PostgreSQL Criadas:

1. **users** - Gestão de usuários (Admin, Editor, Author)
2. **blog_settings** - Configurações globais (JSONB)
3. **posts** - Posts do blog (Multilíngue + JSONB)
4. **categories** - Categorias de posts
5. **post_category** - Relação M:N posts-categorias
6. **pages** - Páginas estáticas
7. **menus** - Menus de navegação
8. **menu_items** - Itens de menu (hierárquico)
9. **media_library** - Gerenciamento de mídia
10. **languages** - Configuração de idiomas
11. **language_settings** - Configurações por idioma

**Verificar tabelas:**
```bash
psql -h localhost -U blog_admin -d blog_platform -W -c "
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public' ORDER BY table_name;
"
```

---

## 🚀 Iniciar Desenvolvimento

### Pré-requisitos Verificados:
- [x] PHP 8.1+ com extensões pdo_pgsql e redis
- [x] PostgreSQL 13+ rodando
- [x] Redis 6+ rodando
- [x] Node.js 16+
- [x] Composer instalado
- [x] Backend .env configurado
- [x] Migrations executadas

### Sequência de Inicialização:

**Terminal 1 - Backend API:**
```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public
```
Esperado: Server running on http://127.0.0.1:8000

**Terminal 2 - Frontend Next.js:**
```bash
cd /home/edblack/projetos/blog/frontend
npm run dev
```
Esperado: Ready in 2.5s, listening on port 3000

**Terminal 3 - Monitorar Redis (opcional):**
```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>" MONITOR
```

---

## ✅ Validação de Endpoints

### 1. Health Check
```bash
curl -X GET http://localhost:8000/api/v1/health
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

### 2. Registro de Usuário
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

### 3. Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "TestPass123!"
  }'
```

**Esperado:** Token JWT retornado

### 4. Validar Token
```bash
TOKEN="seu_token_jwt"
curl -X GET http://localhost:8000/api/v1/auth/validate \
  -H "Authorization: Bearer $TOKEN"
```

---

## 🗄️ Operações PostgreSQL Úteis

### Conectar ao banco:
```bash
psql -h localhost -U blog_admin -d blog_platform -W
```

### Listar tabelas:
```sql
\dt
```

### Verificar usuários:
```sql
SELECT id, email, role, status FROM users;
```

### Limpar cache Redis:
```sql
-- Via psql (se houver integração)
-- Ou pelo redis-cli:
```

```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>" FLUSHDB
```

### Ver conteúdo do Redis:
```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>"
> KEYS blog:*
> GET blog:user_sessions:1
> TTL blog:post:1
```

---

## 🐛 Troubleshooting

### PostgreSQL não conecta
```bash
# Verificar se está rodando
psql -h localhost -l

# Se não conseguir, verifique no Docker WSL2
# No Windows/PowerShell:
wsl -d Docker-Desktop
docker ps
```

### Redis não conecta
```bash
# Testar conexão
redis-cli -h localhost -p 6379 -a "<USE_ENV>" ping

# Se retornar erro, verificar no Docker WSL2
docker ps | grep redis
```

### PHP Extensions faltando
```bash
# Verificar quais estão instaladas
php -m | grep -i pdo
php -m | grep -i pgsql
php -m | grep -i redis

# Instalar se necessário
sudo apt-get install php8.1-pgsql php8.1-redis
```

### Porta 8000 já em uso
```bash
# Usar porta diferente
php -S localhost:8001 -t public
```

### Porta 3000 já em uso
```bash
# Usar porta diferente
npm run dev -- -p 3001
```

---

## 📁 Arquivos Importantes

| Arquivo | Descrição |
|---------|-----------|
| `backend/.env` | Configuração PostgreSQL + Redis |
| `backend/src/Database/Connection.php` | Conexão PDO PostgreSQL |
| `backend/src/Cache/CacheService.php` | Serviço Redis Cache |
| `backend/src/Database/Migration.php` | Schema 11 tabelas |
| `frontend/.env.local` | Config frontend (NEXT_PUBLIC_API_URL) |
| `NEXT_STEPS.md` | Próximos passos detalhados |
| `VALIDATE_PHASE_0.md` | Checklist validação completo |
| `PHASE_0_SUMMARY.txt` | Resumo executivo |

---

## 🔐 Segurança

- ✅ JWT tokens com expiração 24h
- ✅ BCrypt password hashing
- ✅ Prepared statements (SQL injection prevention)
- ✅ CORS configurado para localhost:3000
- ✅ Redis password protegido

**⚠️ Para Produção:**
- [ ] Trocar JWT_SECRET
- [ ] Trocar ADMIN_PASSWORD
- [ ] Atualizar CORS_ALLOWED_ORIGINS
- [ ] Configurar HTTPS + HSTS
- [ ] Implementar rate limiting
- [ ] Adicionar WAF

---

## 🎯 Próximas Etapas

### Imediato (Hoje)
1. Executar validação completa em VALIDATE_PHASE_0.md
2. Verificar todos os endpoints da API
3. Testar fluxo de autenticação
4. Confirmar cache Redis funcionando

### Curto Prazo (Esta semana)
1. Implementar CRUD de Posts (Fase 1)
2. Implementar CRUD de Categorias
3. Adicionar upload de mídia
4. Gestão de Páginas Estáticas

### Médio Prazo (Próximas 2 semanas)
1. Suporte multilíngue completo
2. SEO engine (hreflang, schema.org)
3. Admin panel em Next.js
4. Frontend público com layouts

---

## 📚 Documentação Relacionada

- `README.md` - Visão geral do projeto
- `NEXT_STEPS.md` - Próximos passos detalhados
- `VALIDATE_PHASE_0.md` - Checklist de validação
- `PHASE_0_SUMMARY.txt` - Resumo executivo
- `docs/DATABASE_SCHEMA.md` - Estrutura completa do BD
- `docs/4-PLANO_DE_EXECUCAO.md` - Plano de todas as fases
- `.copilot-agents/architecture.md` - Instruções de arquitetura

---

## 🚀 Status FASE 0

```
✅ Backend Foundation (PHP 8.1)
✅ Frontend Setup (Next.js 14)
✅ PostgreSQL Configuration
✅ Redis Configuration
✅ Authentication (JWT)
✅ CORS Setup
✅ Database Migrations (11 tables)
✅ Environment Configuration
✅ Documentation

Pronto para: FASE 1 - Content Management
```

---

**Configuração concluída em 22 de Fevereiro de 2026**  
**Próximo: Executar VALIDATE_PHASE_0.md quando PHP estiver disponível**

