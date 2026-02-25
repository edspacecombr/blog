# Blog Profissional Multilíngue - Documentação Técnica Completa

## 📚 Índice

1. [Visão Geral da Arquitetura](#visão-geral)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Requisitos do Sistema](#requisitos)
4. [Instalação e Configuração](#instalação)
5. [Estrutura do Projeto](#estrutura)
6. [API Reference](#api-reference)
7. [Deployment](#deployment)
8. [Testes](#testes)
9. [Troubleshooting](#troubleshooting)

---

## <a name="visão-geral"></a>1. Visão Geral da Arquitetura

### Arquitetura Desacoplada

O projeto segue uma arquitetura moderna separada em **dois projetos independentes**:

```
┌─────────────────────────────────────────────────────────────────┐
│                    BLOG PROFISSIONAL V1.0                        │
├────────────────────────┬────────────────────────────────────────┤
│  BACKEND (Laravel API) │  FRONTEND (Next.js + Vercel)           │
├────────────────────────┼────────────────────────────────────────┤
│ - REST API v1          │ - Public Website (SSR/ISR)             │
│ - PostgreSQL           │ - Admin Panel                          │
│ - Redis Cache          │ - Multilingual Support                 │
│ - JWT Auth             │ - SEO Optimized                        │
│ - Media Upload         │ - Performance Optimized                │
│ - Webhooks             │ - AdSense Integration                  │
├────────────────────────┼────────────────────────────────────────┤
│ http://api.domain.com  │ https://domain.com                     │
└────────────────────────┴────────────────────────────────────────┘
```

### Diagrama de Fluxo

```
User → Frontend (Next.js)
       ↓
       [API Client]
       ↓
Backend (Laravel)
       ↓
[PostgreSQL] ← [Redis Cache]
       ↓
Response JSON → Frontend → User
```

---

## <a name="stack-tecnológico"></a>2. Stack Tecnológico

### Backend
- **Framework:** Laravel 11 (PHP 8.1+)
- **Database:** PostgreSQL 14+
- **Cache:** Redis 7+
- **Auth:** Laravel Sanctum
- **API:** REST JSON v1
- **Testing:** PHPUnit 10
- **CI/CD:** GitHub Actions
- **Deployment:** Nginx + PHP-FPM ou Shared Hosting

### Frontend
- **Framework:** Next.js 14
- **Runtime:** Node.js 18+
- **Language:** TypeScript 5
- **Styling:** Tailwind CSS 4
- **State:** Zustand + React Context
- **Forms:** React Hook Form + Zod
- **Testing:** Jest + React Testing Library
- **Deployment:** Vercel ou PM2
- **i18n:** next-intl

### DevOps
- **Containerization:** Docker Compose
- **CI/CD:** GitHub Actions
- **Version Control:** Git
- **Package Manager:** npm (frontend), Composer (backend)

---

## <a name="requisitos"></a>3. Requisitos do Sistema

### Para Desenvolvimento Local

**Mínimo:**
- PHP 8.1+
- Node.js 18+
- PostgreSQL 14+
- Redis 7+
- Git
- 4GB RAM
- 10GB Disk Space

**Recomendado:**
- PHP 8.3
- Node.js 20 LTS
- PostgreSQL 16
- Redis 7.2
- 8GB RAM
- SSD com 20GB espaço

### Para Produção (Backend VPS)

**Especificações mínimas:**
- CPU: 2 cores
- RAM: 2GB (4GB recomendado)
- Disk: 20GB SSD
- Banda: 1Mbps+
- Ubuntu 22.04 LTS

**Serviços necessários:**
- Nginx 1.24+
- PHP-FPM 8.3
- PostgreSQL 16
- Redis 7.2
- Supervisor
- Let's Encrypt SSL

---

## <a name="instalação"></a>4. Instalação e Configuração

### 4.1 Clone do Repositório

```bash
git clone https://github.com/seu-usuario/blog-profissional.git
cd blog-profissional
```

### 4.2 Setup Backend

```bash
cd backend

# Copy environment file
cp .env.example .env

# Install dependencies
composer install

# Generate database
php migrate.php

# Seed initial data
php seed.php

# Start server (development)
php -S localhost:3001
```

**Configuração .env mínima:**

```env
APP_NAME="Blog Profissional"
APP_URL=http://localhost:3001
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=blog_db
DB_USERNAME=postgres
DB_PASSWORD=postgres

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

JWT_SECRET=your-secret-key-here
FRONTEND_URL=http://localhost:3000
```

### 4.3 Setup Frontend

```bash
cd ../frontend

# Install dependencies
npm install

# Copy environment file
cp .env.example .env.local

# Development
npm run dev

# Build for production
npm run build
npm start
```

**Configuração .env.local:**

```env
NEXT_PUBLIC_API_URL=http://localhost:3001/api/v1
NEXT_PUBLIC_SITE_NAME=Blog Profissional
NEXTAUTH_SECRET=your-secret-here
REVALIDATE_SECRET=webhook-secret-here
```

### 4.4 Setup com Docker Compose

```bash
# Na raiz do projeto
docker-compose up -d

# Aguarde a inicialização dos serviços (30-60s)

# Acesse:
# - Backend API: http://localhost:3001
# - Frontend: http://localhost:3000
# - PostgreSQL: localhost:5432
# - Redis: localhost:6379
```

---

## <a name="estrutura"></a>5. Estrutura do Projeto

### Backend (`/backend/src`)

```
src/
├── Controllers/
│   ├── AuthController.php
│   ├── PostController.php
│   ├── PageController.php
│   ├── CategoryController.php
│   ├── AuthorController.php
│   ├── MediaController.php
│   ├── MenuController.php
│   ├── LanguageController.php
│   ├── SettingsController.php
│   └── ContactController.php
├── Models/
│   ├── Post.php
│   ├── Page.php
│   ├── Category.php
│   ├── Author.php
│   ├── Media.php
│   ├── Menu.php
│   ├── MenuItem.php
│   ├── Language.php
│   ├── SiteSetting.php
│   └── User.php
├── Services/
│   ├── SeoService.php
│   ├── SchemaService.php
│   ├── SitemapService.php
│   ├── MediaService.php
│   ├── HtmlSanitizationService.php
│   └── NextjsRevalidationService.php
├── Requests/
│   ├── StorePostRequest.php
│   ├── UpdatePostRequest.php
│   ├── StoreMediaRequest.php
│   └── ...
├── Resources/
│   ├── PostResource.php
│   ├── PageResource.php
│   ├── CategoryResource.php
│   ├── AuthorResource.php
│   └── ...
├── Jobs/
│   ├── ConvertMediaToWebpJob.php
│   ├── PublishScheduledPostsJob.php
│   └── GenerateSitemapJob.php
├── Middleware/
│   ├── AuthenticateWithToken.php
│   ├── EnsureSetupComplete.php
│   ├── SecurityHeaders.php
│   └── RateLimit.php
├── Database/
│   ├── migrations/
│   └── seeders/
└── routes/
    └── api.php
```

### Frontend (`/frontend/src`)

```
src/
├── app/
│   ├── (admin)/
│   │   ├── admin/
│   │   │   ├── page.tsx
│   │   │   ├── login/page.tsx
│   │   │   ├── setup/page.tsx
│   │   │   ├── posts/page.tsx
│   │   │   ├── pages/page.tsx
│   │   │   ├── categories/page.tsx
│   │   │   ├── authors/page.tsx
│   │   │   ├── media/page.tsx
│   │   │   ├── menus/page.tsx
│   │   │   ├── settings/page.tsx
│   │   │   └── languages/page.tsx
│   │   └── layout.tsx
│   ├── (public)/
│   │   ├── [locale]/
│   │   │   ├── page.tsx (home)
│   │   │   ├── [category]/[slug]/page.tsx
│   │   │   ├── [...slug]/page.tsx (páginas)
│   │   │   └── layout.tsx
│   │   └── layout.tsx
│   ├── api/
│   │   ├── revalidate/route.ts
│   │   └── contact/route.ts
│   └── layout.tsx
├── components/
│   ├── admin/
│   │   ├── AdminLayout.tsx
│   │   ├── SetupWizard.tsx
│   │   ├── PostTable.tsx
│   │   ├── MediaPicker.tsx
│   │   └── ...
│   └── public/
│       ├── PostCard.tsx
│       ├── Pagination.tsx
│       ├── ContactForm.tsx
│       ├── AdBlock.tsx
│       └── layouts/
│           ├── Layout1Clean.tsx
│           ├── Layout2Magazine.tsx
│           └── Layout3Minimal.tsx
├── lib/
│   ├── api.ts
│   ├── seo.ts
│   ├── schema.ts
│   ├── validators/
│   │   └── index.ts
│   └── store/
│       └── auth.ts
├── hooks/
│   ├── usePostForm.ts
│   ├── useSetupWizard.ts
│   └── useAuth.ts
└── __tests__/
    ├── components/
    ├── hooks/
    └── lib/
```

---

## <a name="api-reference"></a>6. API Reference

### Autenticação

#### POST `/api/v1/auth/login`

```bash
curl -X POST http://localhost:3001/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "email": "admin@example.com",
    "name": "Admin User",
    "role": "admin",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

### Posts

#### GET `/api/v1/posts`

```bash
curl http://localhost:3001/api/v1/posts?page=1&limit=10&status=published&language=en
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Hello World",
      "slug": "hello-world",
      "excerpt": "First post",
      "status": "published",
      "featured_image": "/uploads/image.jpg",
      "seo": {
        "hreflang": [...],
        "canonical": "https://example.com/en/hello-world",
        "og": {...}
      },
      "schema": {...}
    }
  ],
  "meta": {
    "total": 42,
    "page": 1,
    "limit": 10
  }
}
```

#### POST `/api/v1/posts`

```bash
curl -X POST http://localhost:3001/api/v1/posts \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "New Post",
    "slug": "new-post",
    "excerpt": "Post excerpt",
    "body": "<p>Post body</p>",
    "language": "en",
    "category_id": 1,
    "author_id": 1,
    "status": "draft"
  }'
```

#### PUT `/api/v1/posts/{id}`

```bash
curl -X PUT http://localhost:3001/api/v1/posts/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Title",
    "status": "published"
  }'
```

#### PATCH `/api/v1/posts/{id}/publish`

```bash
curl -X PATCH http://localhost:3001/api/v1/posts/1/publish \
  -H "Authorization: Bearer YOUR_TOKEN"
```

Dispara webhook de revalidação para o Next.js.

#### DELETE `/api/v1/posts/{id}`

```bash
curl -X DELETE http://localhost:3001/api/v1/posts/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Media

#### POST `/api/v1/media`

```bash
curl -X POST http://localhost:3001/api/v1/media \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "file=@image.jpg"
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "image.jpg",
    "url": "/uploads/image.jpg",
    "webp_url": "/uploads/image.webp",
    "avif_url": "/uploads/image.avif",
    "size": 102400,
    "mime_type": "image/jpeg"
  }
}
```

#### PATCH `/api/v1/media/{id}`

```bash
curl -X PATCH http://localhost:3001/api/v1/media/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "alt_text": {
      "en": "Image description",
      "pt": "Descrição da imagem"
    }
  }'
```

### Configurações

#### GET `/api/v1/settings`

```bash
curl http://localhost:3001/api/v1/settings
```

#### PATCH `/api/v1/settings`

```bash
curl -X PATCH http://localhost:3001/api/v1/settings \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "site_name": "My Blog",
    "site_url": "https://example.com",
    "primary_color": "#3B82F6",
    "secondary_color": "#1F2937"
  }'
```

### Webhook de Revalidação

#### POST `/api/revalidate`

Disparado pelo backend quando um post é publicado:

```bash
curl -X POST http://localhost:3000/api/revalidate \
  -H "Content-Type: application/json" \
  -d '{
    "secret": "webhook-secret",
    "paths": [
      "/en",
      "/en/hello-world",
      "/sitemap.xml"
    ]
  }'
```

---

## <a name="deployment"></a>7. Deployment

### Deploy Backend (VPS/Shared Hosting)

#### Opção 1: Shared Hosting com cPanel

```bash
# 1. Via FTP/SSH
# - Upload files to public_html
# - Criar arquivo .env com configurações

# 2. Configurar PHP
# - PHP 8.1+ com extensões: pdo_pgsql, redis, gd

# 3. Setup Database
# - Criar banco PostgreSQL via painel
# - Rodar migrations

# 4. Cron jobs
# - Adicionar ao cron: * * * * * php /home/user/public_html/schedule.php
```

#### Opção 2: VPS com Nginx

```bash
#!/bin/bash
# Executar como root

# Atualizar sistema
apt-get update && apt-get upgrade -y

# Instalar PHP
apt-get install -y php8.3-fpm php8.3-cli php8.3-pgsql php8.3-redis php8.3-gd php8.3-curl

# Instalar Nginx
apt-get install -y nginx

# Instalar PostgreSQL
apt-get install -y postgresql-16

# Instalar Redis
apt-get install -y redis-server

# Instalar Supervisor
apt-get install -y supervisor

# Instalar Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clonar projeto
cd /var/www
git clone https://github.com/seu-usuario/blog-profissional.git
cd blog-profissional/backend

# Setup aplicação
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Editar .env com configurações reais
php migrate.php --force
php seed.php

# Setup Nginx
sudo cp deploy/nginx.conf /etc/nginx/sites-available/blog-api
sudo ln -s /etc/nginx/sites-available/blog-api /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx

# Setup Supervisor para queue
sudo cp deploy/supervisor.conf /etc/supervisor/conf.d/blog-api.conf
sudo supervisorctl reread
sudo supervisorctl update

# SSL com Let's Encrypt
sudo apt-get install -y certbot python3-certbot-nginx
sudo certbot certonly --nginx -d api.your-domain.com
```

**Arquivo de configuração Nginx (`deploy/nginx.conf`):**

```nginx
server {
    listen 80;
    listen 443 ssl http2;
    server_name api.your-domain.com;

    ssl_certificate /etc/letsencrypt/live/api.your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.your-domain.com/privkey.pem;

    root /var/www/blog-profissional/backend/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    client_max_body_size 100M;
}
```

### Deploy Frontend (Vercel)

```bash
# 1. Conectar repositório GitHub ao Vercel
# - Autorizar Vercel
# - Selecionar repositório
# - Configurar build settings

# 2. Variáveis de ambiente
NEXT_PUBLIC_API_URL=https://api.your-domain.com/api/v1
REVALIDATE_SECRET=seu-webhook-secret
NEXTAUTH_SECRET=seu-secret-aleatorio

# 3. Build e Deploy
# - Vercel faz automaticamente no push para main
```

### Deploy Frontend (VPS com PM2)

```bash
#!/bin/bash

# Instalar Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# Instalar PM2 globalmente
sudo npm install -g pm2

# Clonar projeto
cd /var/www
git clone https://github.com/seu-usuario/blog-profissional.git
cd blog-profissional/frontend

# Instalar e build
npm install
npm run build

# Iniciar com PM2
pm2 start npm --name "blog-frontend" -- start
pm2 save

# Setup Nginx como reverse proxy
sudo cp deploy/nginx-frontend.conf /etc/nginx/sites-available/blog-frontend
sudo ln -s /etc/nginx/sites-available/blog-frontend /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx

# SSL
sudo certbot certonly --nginx -d your-domain.com

# Autostart no boot
sudo pm2 startup
```

---

## <a name="testes"></a>8. Testes

### Testes Backend (PHPUnit)

```bash
cd backend

# Todos os testes
npm run test

# Apenas Unit Tests
npm run test:unit

# Apenas Feature Tests
npm run test:feature

# Com cobertura
npm run test:coverage
```

**Exemplo de teste unitário:**

```php
<?php
namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\SeoService;

class SeoServiceTest extends TestCase
{
    public function testBuildHreflangPayload()
    {
        $service = new SeoService();
        $post = ['id' => 1, 'slug' => 'test'];
        
        $result = $service->buildHreflangPayload($post, 'en');
        
        $this->assertIsArray($result);
    }
}
```

### Testes Frontend (Jest)

```bash
cd frontend

# Todos os testes
npm run test

# Com cobertura
npm run test:coverage

# Modo watch
npm run test -- --watch
```

**Exemplo de teste de componente:**

```typescript
import { render, screen } from '@testing-library/react';
import PostCard from '@/components/PostCard';

describe('PostCard', () => {
  it('renders post title', () => {
    render(<PostCard title="Test" excerpt="Test" />);
    expect(screen.getByText('Test')).toBeInTheDocument();
  });
});
```

### CI/CD Pipeline (GitHub Actions)

O projeto inclui workflows automáticos:

1. **test.yml** - Executa testes em cada push/PR
2. **build.yml** - Verifica compilação
3. **deploy.yml** - Deploy automático para main

Visualize em: `.github/workflows/`

---

## <a name="troubleshooting"></a>9. Troubleshooting

### Backend

**Erro: CORS não funciona**
```
Solução: Verificar config/cors.php - FRONTEND_URL deve estar correto
```

**Erro: Banco não conecta**
```
Solução: Verificar .env - DB_HOST, DB_PORT, credenciais PostgreSQL
```

**Erro: Redis não conecta**
```
Solução: Verificar se Redis está rodando: redis-cli ping
```

### Frontend

**Erro: API retorna 401**
```
Solução: Token expirado - fazer login novamente
```

**Erro: Build falha**
```
Solução: npm ci && npm run build (limpar node_modules se necessário)
```

**Erro: Próxima renderização quebrada**
```
Solução: Verificar NEXT_PUBLIC_API_URL no .env.local
```

---

## 📞 Suporte

- **Documentação:** [/docs](./docs)
- **Issues:** GitHub Issues
- **Wiki:** GitHub Wiki

---

**Versão:** 1.0  
**Última atualização:** 2026-02-24  
**Status:** ✅ Pronto para produção
