# 📰 Blog Profissional Multilíngue com PHP e Next.js

Uma plataforma de blog profissional, escalável e totalmente em português, construída com **PHP 8.3 REST API** e **Next.js 14 Frontend**, pronta para SEO, múltiplos idiomas e monetização via AdSense.

## 🚀 Status Atual

**Fases Completas:** 0, 1, 2 ✅

```
████████████████░░░░░░░░░░░░░░  37% (3 de 8 fases)
Próximo: Fase 3 - Mídia API + Editor GrapesJS
```

[📊 Versão Detalhada de Status](./docs/Fases_Implementadas.md)

## 🔧 Stack Técnico

### Backend
- **PHP 8.3** - REST API pura (sem framework)
- **PostgreSQL 15** - Banco de dados robusto
- **Redis** - Cache e sessões
- **Composer** - Gerenciador de pacotes

### Frontend
- **Next.js 14** - App Router + TypeScript
- **Tailwind CSS** - Estilização
- **React 18** - UI components

### DevOps
- **Docker** - Containers para PostgreSQL e Redis
- **GitHub Actions** - CI/CD

## 📋 Começar Rapidamente

### Pré-requisitos
```bash
PHP 8.3+
Node.js 22+
PostgreSQL 15 (Docker ou local)
Redis (Docker ou local)
```

### Instalação

1. **Backend**
```bash
cd backend
php ../composer.phar install
# Configurar .env com credenciais PostgreSQL + Redis
php -S localhost:8000 -t public
```

2. **Frontend**
```bash
cd frontend
npm install
npm run dev
```

3. **Validar Conexões**
```bash
curl http://localhost:8000/api/v1/health
curl http://localhost:3000
```

✅ **Esperado:**
- Backend: `{"status":"ok","timestamp":"..."}`
- Frontend: Página renderizando normalmente

## 📖 Documentação

| Documento | Descrição |
|-----------|-----------|
| [📚 Índice de Docs](./docs/INDEX.md) | Navegação de toda documentação |
| [🎯 Próximos Passos](./docs/setup/NEXT_STEPS.md) | O que fazer agora (Fase 3) |
| [📊 Fases Implementadas](./docs/Fases_Implementadas.md) | Status detalhado de cada fase |
| [📐 Plano de Execução](./docs/4-PLANO_DE_EXECUCAO.md) | Roadmap completo (8 fases) |
| [📊 Modelo de Dados](./docs/database/) | Schema do banco de dados |

## 📊 Fases do Projeto

### ✅ Fase 0 - Fundação (2 semanas)
- [x] Setup duplo: Laravel API + Next.js Frontend
- [x] Banco de dados PostgreSQL migrado
- [x] Autenticação com Sanctum
- [x] CORS configurado
- [x] Redis conectando

### ✅ Fase 1 - Core API Backend (3 semanas)
- [x] CRUD Posts, Páginas, Categorias, Autores, Menus
- [x] API Resources com paginação
- [x] Validações estruturadas
- [x] Agendamento de posts

### ✅ Fase 2 - Multilíngue + SEO (3 semanas)
- [x] Gerenciamento de idiomas
- [x] Traduções de conteúdo
- [x] SEO Services (hreflang, schema JSON-LD)
- [x] Geração de sitemaps

### ⏳ Fases 3-8 (Veja [Plano Completo](./docs/4-PLANO_DE_EXECUCAO.md))
- Fase 3: Mídia API + GrapesJS Editor
- Fase 4: Admin Dashboard em Next.js
- Fase 5: Frontend Público (3 layouts)
- Fase 6: API Pública + Automação
- Fase 7: AdSense + Performance
- Fase 8: Testes, Deploy e Documentação

## 🏗️ Estrutura do Projeto

```
blog/
├── backend/                    # PHP API Rest
│   ├── src/
│   │   ├── Controllers/       # Endpoints
│   │   ├── Models/            # Entidades
│   │   ├── Resources/         # API Responses
│   │   ├── Database/          # Conexão
│   │   └── Services/          # Lógica
│   ├── public/index.php       # Entry point
│   ├── .env                   # Configuração
│   └── composer.json
│
├── frontend/                   # Next.js Frontend
│   ├── src/
│   │   ├── app/               # Pages e Layouts
│   │   ├── components/        # React Components
│   │   ├── lib/               # Utilities
│   │   └── styles/            # Tailwind
│   ├── .env.local             # Configuração
│   └── package.json
│
├── docs/                       # Documentação
│   ├── phases/                # Fases do projeto
│   ├── setup/                 # Setup e configuração
│   ├── guides/                # Guias técnicos
│   ├── architecture/          # Arquitetura
│   └── database/              # DB docs
│
└── docker-compose.yml         # Docker setup
```

## 🔐 Configuração de Ambiente

### Backend (backend/.env)
```bash
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_admin
DB_PASSWORD=b4m@#62P (use seu próprio)
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=d4m@!62R (use seu próprio)
APP_ENV=development
APP_URL=http://localhost:8000
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

### Frontend (frontend/.env.local)
```bash
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
```

## ✅ Endpoints Principais

### Fase 0 - Health Check
| Método | Endpoint | Status |
|--------|----------|--------|
| GET | `/api/v1/health` | ✅ |
| POST | `/api/v1/auth/login` | ✅ |

### Fase 1 - Posts, Páginas, Categorias, Autores, Menus
| Método | Endpoint | Status |
|--------|----------|--------|
| GET | `/api/v1/posts` | ✅ |
| POST | `/api/v1/posts` | ✅ |
| GET | `/api/v1/posts/:id` | ✅ |
| PATCH | `/api/v1/posts/:id` | ✅ |
| DELETE | `/api/v1/posts/:id` | ✅ |
| GET | `/api/v1/categories` | ✅ |
| GET | `/api/v1/authors` | ✅ |
| GET | `/api/v1/menus` | ✅ |

### Fase 2 - SEO e Multilíngue
| Método | Endpoint | Status |
|--------|----------|--------|
| GET | `/api/v1/languages` | ✅ |
| GET | `/sitemap.xml` | ✅ |
| GET | `/robots.txt` | ✅ |
| GET | `/api/v1/settings` | ✅ |

## 🧪 Validar Ambiente Local

```bash
# 1. PostgreSQL conectando
curl http://localhost:8000/api/v1/health

# 2. Redis funcionando
curl http://localhost:8000/api/v1/health

# 3. Frontend renderizando
curl http://localhost:3000 | grep "<title>"

# 4. CORS funcionando
curl -H "Origin: http://localhost:3000" http://localhost:8000/api/v1/health
```

## 🚀 Como Iniciar Desenvolvimento

### Terminal 1: Backend
```bash
cd backend
php -S localhost:8000 -t public
```

### Terminal 2: Frontend
```bash
cd frontend
npm run dev
```

### Terminal 3: Monitorar Banco (Opcional)
```bash
# PostgreSQL
psql -h localhost -U blog_admin -d blog_platform

# Redis
redis-cli -h localhost -p 6379 -a 'password'
```

## 📚 Próximas Tarefas

1. ✅ **Fase 0 Completa** - Fundação pronta
2. ✅ **Fase 1 Completa** - CRUD API Backend
3. ✅ **Fase 2 Completa** - Multilíngue + SEO
4. 👉 **Iniciar Fase 3** - Mídia API + GrapesJS
   - Upload de mídia
   - Conversão WebP/AVIF
   - Editor GrapesJS no admin

Veja [NEXT_STEPS.md](./docs/setup/NEXT_STEPS.md) para instruções detalhadas.

## 🆘 Troubleshooting

| Problema | Solução |
|----------|---------|
| Conexão PostgreSQL falha | Verificar `.env`: DB_HOST, DB_PORT, DB_USER, DB_PASSWORD |
| CORS error no frontend | Verificar CORS_ALLOWED_ORIGINS em backend/.env |
| Frontend não conecta API | Verificar NEXT_PUBLIC_API_URL em frontend/.env.local |
| Redis não conectando | Verificar REDIS_HOST, REDIS_PORT, REDIS_PASSWORD |

## 📄 Licença

MIT

## 👤 Autor

Desenvolvido por Equipe de Desenvolvimento

---

**Última Atualização:** 2026-02-23  
**Status:** ✅ Fases 0, 1, 2 Completas (37%)  
**Próximo:** 👉 Fase 3 (Mídia API + Editor GrapesJS)

[📊 Ver Status Detalhado](./docs/Fases_Implementadas.md) | [🎯 Ver Próximos Passos](./docs/setup/NEXT_STEPS.md)
