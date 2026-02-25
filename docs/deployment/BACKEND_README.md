# Blog Profissional - Backend API

REST API em PHP para gerenciamento de blog multilíngue com suporte a SEO, mídia e automação.

## 🚀 Quick Start

### Pré-requisitos
- PHP 8.1+
- PostgreSQL 14+
- Redis 7+
- Composer
- Git

### Instalação Local

```bash
# Clone o repositório
git clone https://github.com/seu-usuario/blog-profissional.git
cd blog-profissional/backend

# Instale dependências
composer install

# Configure ambiente
cp .env.example .env
# Edite .env com suas configurações

# Execute migrações
php migrate.php

# Execute seeds
php seed.php

# Inicie servidor
php -S localhost:3001
```

## 📋 Funcionalidades

### ✅ Autenticação & Segurança
- JWT via Sanctum
- Rate limiting
- CORS configurável
- Validação de entrada
- HTML sanitization

### ✅ Gerenciamento de Conteúdo
- CRUD completo: Posts, Páginas, Categorias, Autores
- Status workflow: draft → published → scheduled
- Versionamento com `updated_at`
- Suporte a múltiplos idiomas

### ✅ SEO & Performance
- Geração automática de hreflang
- Schema JSON-LD (Article, BreadcrumbList, Author, Organization)
- Sitemaps XML multilíngues
- Canonical URLs
- Open Graph & Twitter Cards

### ✅ Mídia
- Upload com validação MIME
- Conversão WebP/AVIF
- Alt text multilíngue
- Compressão automática

### ✅ Automação
- Jobs assíncronos
- Webhook ISR Next.js
- Agendamento de posts
- Email via SMTP

## 📚 Endpoints Principais

### Autenticação
```
POST   /api/v1/auth/login          Login
GET    /api/v1/auth/me             Perfil atual
POST   /api/v1/auth/logout         Logout
```

### Posts
```
GET    /api/v1/posts               Listar posts
POST   /api/v1/posts               Criar post
GET    /api/v1/posts/{id}          Detalhe post
PUT    /api/v1/posts/{id}          Atualizar post
PATCH  /api/v1/posts/{id}/publish  Publicar
DELETE /api/v1/posts/{id}          Deletar
```

### Mídia
```
GET    /api/v1/media               Listar mídia
POST   /api/v1/media               Upload
PATCH  /api/v1/media/{id}          Atualizar alt text
DELETE /api/v1/media/{id}          Deletar
```

### Configurações
```
GET    /api/v1/settings            Obter configurações
PATCH  /api/v1/settings            Atualizar configurações
```

Veja [Documentação Completa](../docs/DOCUMENTACAO_TECNICA_COMPLETA.md) para detalhes.

## 🧪 Testes

```bash
# Todos os testes
composer run test

# Unit tests
composer run test:unit

# Feature tests
composer run test:feature

# Com cobertura
composer run test:coverage
```

## 🔍 Linting

```bash
# Análise estática
composer run lint
```

## 🌍 Deployment

### Shared Hosting
```bash
./scripts/deploy-backend.sh production your-server.com deploy /var/www/blog
```

### VPS (Nginx + PHP-FPM)
```bash
# Ver scripts/deploy-backend.sh para instruções completas
```

## 📋 Stack

- **Framework:** Symfony HTTP + components
- **Database:** PostgreSQL
- **Cache:** Redis
- **Auth:** JWT + Sanctum-style tokens
- **API:** REST JSON
- **Testing:** PHPUnit 10
- **Linting:** PHPStan

## 🔐 Segurança

- ✅ SQL injection prevention (Prepared statements)
- ✅ XSS prevention (HTMLPurifier)
- ✅ CSRF protection (Token validation)
- ✅ Rate limiting (10-60 req/min)
- ✅ CORS restricts origins
- ✅ Security headers (HSTS, CSP, X-Frame)
- ✅ Dependency scanning (via GitHub)

## 📁 Estrutura

```
src/
├── Controllers/      # API endpoints
├── Models/          # Database models
├── Services/        # Business logic
├── Requests/        # Input validation
├── Resources/       # API responses
├── Jobs/            # Queue jobs
├── Middleware/      # HTTP middleware
├── Database/        # Migrations & seeds
└── routes/          # API routes
```

## 🚨 Troubleshooting

**Erro de conexão com BD:**
```
Verificar: DB_HOST, DB_PORT, credenciais em .env
```

**Redis não conecta:**
```
redis-cli ping (deve retornar PONG)
```

**CORS bloqueando requisições:**
```
Editar: .env → FRONTEND_URL
```

Veja [Documentação Técnica](../docs/DOCUMENTACAO_TECNICA_COMPLETA.md) para mais.

## 📝 Licença

MIT License - veja LICENSE

## 👨‍💻 Desenvolvimento

- **Version:** 1.0.0
- **Status:** ✅ Produção
- **Last Updated:** 2026-02-24
- **Maintainer:** GitHub Copilot CLI

---

**Dúvidas?** Consulte a [Documentação Completa](../docs/DOCUMENTACAO_TECNICA_COMPLETA.md)
