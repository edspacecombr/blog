# 🚀 QUICK REFERENCE - FASE 0 PostgreSQL + Redis

## 🔑 Credenciais

```
PostgreSQL:
  Host: localhost
  Port: 5432
  DB: blog_platform
  User: blog_admin
  Password: <USE_ENV>

Redis:
  Host: localhost
  Port: 6379
  Password: <USE_ENV>
```

## 📍 URLs

```
Frontend:   http://localhost:3000
Backend:    http://localhost:8000
Health:     http://localhost:8000/api/v1/health
```

## ⚙️ Startup (3 Terminais)

```bash
# Terminal 1: Backend
cd backend
php -S localhost:8000 -t public

# Terminal 2: Frontend
cd frontend
npm run dev

# Terminal 3: Monitor Redis (opcional)
redis-cli -a "<USE_ENV>" MONITOR
```

## 🧪 Validação Rápida

```bash
# Health check
curl http://localhost:8000/api/v1/health

# Registrar usuário
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test",
    "email": "test@example.com",
    "password": "TestPass123!",
    "password_confirmation": "TestPass123!"
  }'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"TestPass123!"}'

# Validar token
TOKEN="seu_jwt_aqui"
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/auth/validate
```

## 🗄️ PostgreSQL

```bash
# Conectar
psql -h localhost -U blog_admin -d blog_platform -W

# Verificar tabelas
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public';

# Ver usuários
SELECT id, email, role FROM users;

# Sair
\q
```

## 💚 Redis

```bash
# Conectar
redis-cli -h localhost -p 6379 -a "<USE_ENV>"

# Test
PING

# Ver cache
KEYS blog:*

# Limpar
FLUSHDB

# Sair
QUIT
```

## 📊 Tabelas (11)

```
users, blog_settings, posts, categories, post_category,
pages, menus, menu_items, media_library, languages,
language_settings
```

## 🔧 Configurações

- `.env` Backend: `/backend/.env`
- `NEXT_PUBLIC_API_URL`: `http://localhost:8000`
- JWT Expiration: 24 horas
- Redis Namespace: `blog:`

## 📚 Documentação

```
README.md                    → Overview
CONFIGURATION_PHASE_0.md     → Setup completo
VALIDATE_PHASE_0.md         → Checklist validação
PHASE_0_COMPLETE.md         → Status final
validate-phase0.sh          → Script teste
NEXT_STEPS.md               → Próximos passos
```

## ✅ Checkpoints

- [ ] Backend running on :8000
- [ ] Frontend running on :3000
- [ ] PostgreSQL connected (11 tables)
- [ ] Redis connected (PING returns PONG)
- [ ] Health check passing
- [ ] Auth endpoints working
- [ ] Scripts executable

## 🚨 Troubleshooting

| Problema | Solução |
|----------|---------|
| Port já em uso | `lsof -i :8000` + matar processo |
| PostgreSQL não conecta | Verificar WSL2 Docker está rodando |
| Redis não conecta | `redis-cli -a "<USE_ENV>" ping` |
| PHP ext missing | `php -m \| grep pdo` |
| Node modules error | `cd frontend && npm install` |

## 🎯 Next Phase

FASE 1: Content Management
- CRUD Posts
- CRUD Categories
- Media Upload
- Page Management

---

**Status:** ✅ PHASE 0 COMPLETE - READY FOR VALIDATION

Criado: 22 de Fevereiro de 2026
