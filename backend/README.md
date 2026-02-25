# 🔧 Backend API - Blog Profissional Multilíngue

**Status:** ✅ 100% Completo | **Fases:** 0-3 + 6 | **Endpoints:** 50+ | **Testes:** 42 PHPUnit ✅

---

## 📋 Início Rápido

### Pré-requisitos
- PHP 8.1+
- PostgreSQL 14+
- Redis
- Composer

### Instalação (5 minutos)

```bash
cd backend
composer install
cp .env.example .env
# Editar .env com credenciais DB
php migrate.php
php seed.php
php -S localhost:8000 -t public
# ✅ Verificar: curl http://localhost:8000/api/v1/health
```

---

## 🔑 Endpoints Principais

```http
# Autenticação
POST   /api/v1/auth/login
POST   /api/v1/auth/logout

# Posts (CRUD)
GET    /api/v1/posts                    # Listar
GET    /api/v1/posts/{id}              # Detalhe
POST   /api/v1/posts                   # Criar
PUT    /api/v1/posts/{id}              # Atualizar
PATCH  /api/v1/posts/{id}              # Parcial
DELETE /api/v1/posts/{id}              # Deletar

# Página, Categorias, Autores, Mídia (CRUD)
GET/POST/PATCH/DELETE /api/v1/pages | categories | authors | media

# Configurações
GET    /api/v1/settings
PATCH  /api/v1/settings

# Público
GET    /api/v1/health
GET    /sitemap.xml
GET    /robots.txt
```

---

## 🧪 Testes (42 tests ✅)

```bash
./vendor/bin/phpunit
# Unit: SeoService, SchemaService, MediaService
# Feature: PostController, MediaController, AuthController
```

---

## 🚀 Deployment

```bash
./scripts/deploy-backend.sh shared user@hosting.com
./scripts/deploy-backend.sh vps root@seu-vps.com
```

---

📖 [Documentação Completa](../README_MAIN.md) | 🔐 [API Docs](../docs/api/API_FASE_6.md) | 🎯 [Setup](../docs/setup/LEIA_PRIMEIRO.md)
