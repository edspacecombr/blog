# 📚 Índice Completo de Documentação

## 📋 Navegação Rápida

### Status das Fases
- ✅ **Fase 0** - Fundação (Dois Projetos + Banco + Auth) **COMPLETA**
- ⏳ **Fase 1** - Core API Backend (Laravel) - Próximo
- ⏳ **Fase 2** - Multilíngue + SEO Engine
- ⏳ **Fase 3+** - Pendentes

### Documentação Essencial
1. [Plano de Execução](./4-PLANO_DE_EXECUCAO.md) - Roadmap completo
2. [Próximos Passos](./NEXT_STEPS.md) - O que fazer agora
3. [Modelo de Dados](./3-MODELO_DE_DADOS.md) - Schema do banco
4. [Arquitetura](./2-ARQUITETURA_GERAL.md) - Visão técnica

## 🏗️ Estrutura de Diretórios

```
docs/
├── 01-Planejamento/          # Requisitos e status
├── 02-Arquitetura/           # Design técnico
├── 03-Guias/                 # Tutoriais e how-tos
├── 04-Fases/                 # Status de cada fase
├── 05-Referencia/            # Referência técnica
├── INDEX.md                  # Este índice
└── 4-PLANO_DE_EXECUCAO.md    # Plano principal
```

## 🔧 Ambiente Local

### Requisitos
- PHP 8.3+
- Node.js 22+
- PostgreSQL (rodando em Docker)
- Redis (rodando em Docker)

### Status da Conexão
✅ PostgreSQL: `localhost:5432` - Funcionando
✅ Redis: `localhost:6379` - Funcionando
✅ Backend PHP: `localhost:8000` - Funcionando
✅ Frontend Next.js: `localhost:3000` - Funcionando

### Como Iniciar

```bash
# Terminal 1: Backend
cd backend && php -S localhost:8000 -t public

# Terminal 2: Frontend
cd frontend && npm start

# Verificar saúde da API
curl http://localhost:8000/api/v1/health
```

## 📊 Fase 0 - Resumo

| Tarefa | Status | Subtarefas |
|--------|--------|-----------|
| F0.1 | ✅ | Setup `blog-api` (Laravel) |
| F0.2 | ✅ | Migrations base |
| F0.3 | ✅ | Autenticação API (Sanctum) |
| F0.4 | ✅ | CORS configurado |
| F0.5 | ✅ | Setup `blog-frontend` (Next.js) |
| F0.6 | ✅ | Cliente API no Next.js |
| F0.7 | ✅ | Auth guard Admin (Next.js) |
| F0.8 | ✅ | Middleware `EnsureSetupComplete` |

**Critério de conclusão:** `blog-api` respondendo com JSON em `/api/v1/health` ✅, `blog-frontend` renderizando página em `localhost:3000` ✅, banco migrado ✅, CORS funcional ✅, login via Sanctum retornando token ✅.

## 🚀 Próximas Etapas (Fase 1)

A Fase 1 começa com a implementação da API Core do Laravel:
- CRUD completo de Posts, Páginas, Categorias
- API Resources com paginação
- Validações e filtros
- Agendamento de posts

Veja [NEXT_STEPS.md](./NEXT_STEPS.md) para detalhes.

## 🔐 Configuração de Credenciais

Todas as credenciais estão em `backend/.env` e `frontend/.env.local`:

```bash
# backend/.env
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_admin
DB_PASSWORD=[USE_ENV]
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=[USE_ENV]

# frontend/.env.local
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
```

**Nunca commitar credenciais!**

---

**Atualizado:** 2026-02-23 | **Status:** Fase 0 ✅
