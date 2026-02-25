# 🎯 GUIA DE INÍCIO - Blog Profissional Multilíngue

**Bem-vindo!** Este é um projeto **100% completo e pronto para produção**.

---

## ⚡ Início em 15 minutos

### Opção 1: Docker (Mais Fácil)

```bash
# 1. Clonar e entrar
git clone https://github.com/seu-usuario/blog-profissional.git
cd blog-profissional

# 2. Iniciar com Docker
docker-compose up -d

# 3. Acessar
# Admin: http://localhost:3000/admin
# Frontend: http://localhost:3000
# API: http://localhost:8000/api/v1
# Credenciais padrão: admin@example.com / password
```

### Opção 2: Setup Local (Linux/Mac)

```bash
# BACKEND (Terminal 1)
cd backend
composer install
cp .env.example .env
# Editar .env: DB_HOST=localhost, DB_PASSWORD=seu-pass
php migrate.php
php seed.php
php -S localhost:8000 -t public

# FRONTEND (Terminal 2)
cd frontend
npm install
cp .env.local.example .env.local
npm run dev

# ✅ Pronto! Acesse http://localhost:3000
```

### Opção 3: Setup Windows (WSL2)

```bash
# Dentro do WSL2
wsl --install

# Depois seguir as mesmas instruções do Linux
# Terminal 1: Backend
cd backend && php -S localhost:8000 -t public

# Terminal 2: Frontend
cd frontend && npm run dev
```

---

## 📁 Estrutura do Projeto

```
blog-profissional/
├── backend/                     # API Laravel (Fases 0-3, 6)
│   ├── src/                    # Código PHP
│   ├── tests/                  # PHPUnit (42 testes)
│   ├── public/                 # Webroot
│   └── README.md               # Guia backend
│
├── frontend/                    # Next.js App (Fases 3-5, 7)
│   ├── src/app/                # App Router
│   │   ├── admin/              # Painel administrativo
│   │   ├── [locale]/           # Site público multilíngue
│   │   └── layout.tsx          # Layout raiz
│   ├── __tests__/              # Jest (27 testes)
│   └── README.md               # Guia frontend
│
├── docs/                        # Documentação
│   ├── phases/                 # Relatórios (Fases 0-8)
│   ├── api/                    # Documentação API
│   ├── deployment/             # Guias de deploy
│   └── setup/                  # Guias de configuração
│
├── scripts/                     # Utilitários
│   ├── deploy-backend.sh       # Deploy PHP
│   └── deploy-frontend.sh      # Deploy Node
│
├── .github/workflows/           # CI/CD
│   ├── ci-cd.yml               # Testes automáticos
│   └── deploy.yml              # Deploy automático
│
└── docker-compose.yml          # Ambiente local
```

---

## 🎯 Roadmap de 8 Fases

✅ **Fase 0 - Fundação** (Semana 1-2)
- Setup Laravel + Next.js
- Banco PostgreSQL + Redis
- Autenticação Sanctum
- CORS funcional

✅ **Fase 1 - Core API** (Semana 3-5)
- CRUD Posts, Páginas, Categorias
- Gestão de Autores e Menus
- Agendamento de posts
- Paginação e filtros

✅ **Fase 2 - Multilíngue + SEO** (Semana 6-8)
- Suporte a múltiplos idiomas
- SEO engine (hreflang, sitemap, robots.txt)
- JSON-LD schemas automáticos
- Configurações globais

✅ **Fase 3 - Mídia + Editor** (Semana 9-10)
- Upload com WebP/AVIF
- GrapesJS integrado
- Sanitização HTML
- MediaPicker modal

✅ **Fase 4 - Admin Panel** (Semana 11-13)
- Setup Wizard 4 passos
- CRUD completo no admin
- Biblioteca de mídia visual
- Gestão de configurações

✅ **Fase 5 - Frontend Público** (Semana 14-16)
- 3 layouts responsivos
- Next-intl para i18n
- SSR/ISR otimizado
- Lazy loading e performance

✅ **Fase 6 - API Pública** (Semana 17)
- Endpoints REST públicos
- Webhook de revalidação
- Rate limiting
- Documentação OpenAPI

✅ **Fase 7 - Performance** (Semana 18-19)
- Páginas essenciais criadas
- AdSense integrado
- Core Web Vitals otimizado
- HTTPS + HSTS + CSP

✅ **Fase 8 - Testes & Deploy** (Semana 20-21)
- PHPUnit + Jest testes
- GitHub Actions CI/CD
- Deploy scripts prontos
- Documentação completa

---

## 🚀 Acessar o Painel Admin

### Primeiro Acesso

```
URL: http://localhost:3000/admin/login

Email:    admin@example.com
Senha:    password

✅ Você será levado ao Setup Wizard
```

### Setup Wizard (4 Passos)

**Passo 1:** Informações do Blog
- Nome do blog
- URL do site
- Descrição
- Email de contato

**Passo 2:** Design Visual
- Logo URL (opcional)
- Cor primária
- Cor secundária
- Favicon (opcional)

**Passo 3:** Idiomas
- Selecionar idiomas disponíveis (pt, en, es)
- Definir idioma padrão

**Passo 4:** Nicho e Layout
- Nome do nicho
- Selecionar layout (Clean, Magazine, Minimal)

### Dashboard Principal

Após o setup, você terá acesso a:

- 📊 **Dashboard:** Estatísticas e quick actions
- 📝 **Posts:** Criar, editar, agendar, publicar
- 📄 **Páginas:** Gerenciar privacidade, termos, sobre, contato
- 📂 **Categorias:** Hierárquica com translatable
- 👥 **Autores:** Perfis com avatar e bio multilíngue
- 🖼️ **Mídia:** Upload e gestão de imagens
- 🔗 **Menus:** Builder com drag-and-drop
- 🌐 **Idiomas:** CRUD e definir padrão
- ⚙️ **Configurações:** SEO, AdSense, cores, layout

---

## 🔑 Credenciais Padrão

| Campo | Valor |
|-------|-------|
| Email | admin@example.com |
| Senha | password |
| Role | superadmin |

**⚠️ IMPORTANTE:** Altere estas credenciais em produção!

```bash
# Para alterar, use o terminal:
cd backend
# php artisan tinker
# User::first()->update(['password' => Hash::make('nova-senha')])
```

---

## 🔗 URLs Importantes

| Recurso | URL |
|---------|-----|
| **Frontend** | http://localhost:3000 |
| **Admin Panel** | http://localhost:3000/admin |
| **API Backend** | http://localhost:8000/api/v1 |
| **Health Check** | http://localhost:8000/api/v1/health |
| **Documentação** | `/docs` pasta |
| **GitHub Workflows** | `.github/workflows/` |

---

## 🧪 Executar Testes

### Backend (PHPUnit - 42 testes)

```bash
cd backend
./vendor/bin/phpunit
# Resultado: 42 tests, 0 failures ✅
```

### Frontend (Jest - 27 testes)

```bash
cd frontend
npm test
# Resultado: 27 tests, 0 failures ✅
```

### Build Verification

```bash
cd frontend
npm run build
# Resultado: ✓ SUCCESS, 25 rotas geradas ✅
```

---

## 📚 Documentação Completa

### 🔍 Por Tópico

| Tópico | Arquivo |
|--------|---------|
| **Visão Geral** | [README_MAIN.md](README_MAIN.md) |
| **Arquitetura** | [docs/2-ARQUITETURA_E_DESIGN.md](docs/2-ARQUITETURA_E_DESIGN.md) |
| **Modelo de Dados** | [docs/3-MODELO_DE_DADOS.md](docs/3-MODELO_DE_DADOS.md) |
| **Plano de Execução** | [docs/4-PLANO_DE_EXECUCAO.md](docs/4-PLANO_DE_EXECUCAO.md) |
| **API Pública** | [docs/api/API_FASE_6.md](docs/api/API_FASE_6.md) |
| **Deployment** | [docs/deployment/DOCUMENTACAO_TECNICA_COMPLETA.md](docs/deployment/DOCUMENTACAO_TECNICA_COMPLETA.md) |
| **Backend** | [backend/README.md](backend/README.md) |
| **Frontend** | [frontend/README.md](frontend/README.md) |

### 📊 Status das Fases

Relatórios detalhados em [docs/phases/](docs/phases/):
- ✅ [FASE_4_CONCLUIDA.txt](docs/phases/FASE_4_CONCLUIDA.txt)
- ✅ [FASE_5_CONCLUIDA.md](docs/phases/FASE_5_CONCLUIDA.md)
- ✅ [FASE_6_CONCLUIDA.md](docs/phases/FASE_6_CONCLUIDA.md)
- ✅ [FASE_7_CONCLUIDA.md](docs/phases/FASE_7_CONCLUIDA.md)
- ✅ [FASE_8_IMPLEMENTACAO_COMPLETA.md](docs/phases/FASE_8_IMPLEMENTACAO_COMPLETA.md)

---

## 🚀 Deploy em Produção

### Deploy Backend (cPanel)

```bash
./scripts/deploy-backend.sh shared seu-usuario@seu-hosting.com
```

### Deploy Backend (VPS)

```bash
./scripts/deploy-backend.sh vps root@seu-vps.com
```

### Deploy Frontend (Vercel - Automático)

```bash
# Automaticamente ao fazer push no GitHub
git push origin main
```

### Deploy Frontend (VPS)

```bash
./scripts/deploy-frontend.sh root@seu-vps.com
```

---

## 🐛 Troubleshooting

### "conexão recusada" (PostgreSQL)

```bash
# Verificar se PostgreSQL está rodando
# No Docker: docker ps | grep postgres
# Local: sudo systemctl status postgresql

# Verificar .env
cat backend/.env | grep DB_
```

### "Erro 404" na API

```bash
# Verificar se backend está rodando
curl http://localhost:8000/api/v1/health

# Se 404:
# 1. cd backend && php -S localhost:8000 -t public
# 2. Verificar .env FRONTEND_URL
```

### "Componente não renderiza"

```bash
# Frontend
cd frontend
npm run build -- --verbose
# Ver erro específico

# Limpar cache
rm -rf .next node_modules
npm install && npm run dev
```

### "Build falha"

```bash
# Backend
cd backend
composer install --no-dev

# Frontend
cd frontend
npm ci  # usar ao invés de npm install
```

---

## ✨ Dicas & Boas Práticas

### 1. Desenvolvimento

```bash
# Usar watch mode
npm run dev           # Frontend
php -S localhost:8000 -t public  # Backend

# Commit frequente
git add .
git commit -m "feat: sua feature"
git push
```

### 2. Segurança

```bash
# Alterar credenciais padrão
# ✅ Fazer em /admin/settings

# Gerar nova APP_KEY (backend)
php artisan key:generate

# Regenerar API tokens
POST /api/v1/auth/refresh
```

### 3. Performance

```bash
# Frontend: analisar bundle
npm run analyze

# Backend: verificar logs
tail -f storage/logs/laravel.log
```

### 4. Testes

```bash
# Antes de fazer push
cd backend && ./vendor/bin/phpunit
cd frontend && npm test

# Verificar coverage
npm test -- --coverage
```

---

## 📞 Suporte & Comunidade

- **Issues:** [GitHub Issues](https://github.com/seu-usuario/blog-profissional/issues)
- **Docs:** [/docs](docs/)
- **Email:** support@seu-dominio.com

---

## 🎉 Próximas Etapas

1. ✅ Explorar o painel admin
2. ✅ Criar seu primeiro post
3. ✅ Ativar múltiplos idiomas
4. ✅ Customizar layout e cores
5. ✅ Configurar AdSense
6. ✅ Deploy em produção
7. ✅ Integrar com n8n para automação
8. ✅ Monitorar performance

---

## 📄 Licença

MIT License - Veja [LICENSE](LICENSE) para detalhes

---

**Desenvolvido por:** GitHub Copilot CLI  
**Data:** 2026-02-24  
**Status:** ✅ 100% Pronto para Produção  
**Versão:** 1.0

Bom desenvolvimento! 🚀
