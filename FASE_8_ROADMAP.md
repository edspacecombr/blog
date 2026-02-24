# 🎯 FASE 8 - TESTES + DEPLOY - ROADMAP

**Status:** Pronto para iniciar  
**Estimativa:** 2 semanas  
**Deadline:** 2026-03-17  
**Progresso:** 0% → 100%

---

## Visão Geral

A Fase 8 é a fase final de testes, integração e deployment. O projeto está 87.5% completo e pronto para os testes e deploy em produção.

---

## F8.1 - Testes Unitários (PHP/Backend)

### O que testar

```php
// Unit Tests
- SeoService
- SchemaService
- SitemapService
- MediaService
- HtmlSanitizationService
- NextjsRevalidationService
```

### Estrutura

```
backend/tests/Unit/
├── Services/
│   ├── SeoServiceTest.php
│   ├── SchemaServiceTest.php
│   ├── SitemapServiceTest.php
│   └── NextjsRevalidationServiceTest.php
└── Models/
    ├── PostTest.php
    └── MediaTest.php
```

### Executar

```bash
./vendor/bin/phpunit tests/Unit
```

### Coverage

- Mínimo 80%
- Foco em: validação, sanitização, geração de URLs

---

## F8.2 - Testes de Feature (PHP/Backend)

### O que testar

```
Feature Tests:
- POST /api/v1/posts (criar)
- PUT /api/v1/posts/{id} (atualizar)
- PATCH /api/v1/posts/{id}/publish
- DELETE /api/v1/posts/{id}
- POST /api/v1/media (upload)
- POST /api/v1/contact (formulário)
- Rate limiting
- Autenticação (Sanctum)
```

### Estrutura

```
backend/tests/Feature/
├── PostControllerTest.php
├── MediaControllerTest.php
├── ContactControllerTest.php
├── AuthControllerTest.php
├── RateLimitTest.php
└── WebhookRevalidationTest.php
```

### Executar

```bash
./vendor/bin/phpunit tests/Feature
```

### Coverage

- Todos endpoints retornam status correto
- Validações funcionam
- Erros são tratados
- Rate limiting está ativo

---

## F8.3 - Testes Frontend (Jest)

### O que testar

```
Jest Tests:
- Components (PostCard, ContactForm, AdBlock)
- Hooks (usePostForm, useSetupWizard)
- Utilities (seo.ts, schema.ts, revalidate.ts)
- Pages (home, post, category)
```

### Estrutura

```
frontend/__tests__/
├── components/
│   ├── PostCard.test.tsx
│   ├── ContactForm.test.tsx
│   └── AdBlock.test.tsx
├── hooks/
│   ├── usePostForm.test.ts
│   └── useSetupWizard.test.ts
└── lib/
    ├── seo.test.ts
    └── schema.test.ts
```

### Executar

```bash
npm run test -- --coverage
```

### Coverage

- Mínimo 70%
- Foco em: rendering, validação, eventos

---

## F8.4 - GitHub Actions CI/CD

### Workflow: Test & Build

```yaml
name: CI/CD
on: [push, pull_request]

jobs:
  backend-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: php-actions/composer@v6
      - run: ./vendor/bin/phpunit

  frontend-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
      - run: npm ci && npm run test
      - run: npm run build

  frontend-build:
    needs: frontend-tests
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: vercel/action@latest
        with:
          vercel-token: ${{ secrets.VERCEL_TOKEN }}
```

### Deploy Automático

- [x] PR: Run tests + build
- [x] Main: Deploy em staging
- [x] Release tag: Deploy em produção

---

## F8.5-F8.6 - Deploy Backend

### Option 1: Shared Hosting (cPanel)

```bash
1. SSH para servidor
2. rsync --exclude='.env' -av . user@host:/public_html/blog-api
3. Execute migrations: php artisan migrate --force
4. Cache: php artisan config:cache
5. Cron: * * * * * cd /path && php artisan schedule:run
```

### Option 2: VPS (Ubuntu + Nginx + PHP-FPM)

```bash
1. Clone repo
2. Install PHP 8.2, Nginx, PostgreSQL
3. Setup .env com credenciais produção
4. composer install --no-dev
5. php artisan migrate --force
6. Setup Nginx vhost
7. Certbot SSL: certbot certonly --nginx
8. Supervisor para queues
9. Health check: curl https://api.example.com/api/v1/health
```

### Environment

```env
# Production .env
APP_ENV=production
APP_DEBUG=false
DB_HOST=prod-db.example.com
DB_DATABASE=blog_prod
API_URL=https://api.example.com
FRONTEND_URL=https://example.com
REVALIDATE_SECRET=$(openssl rand -hex 32)
MAIL_FROM_ADDRESS=noreply@example.com
```

---

## F8.7 - Deploy Frontend (Vercel)

### Setup Vercel

```bash
1. npm i -g vercel
2. vercel login
3. vercel
4. Setup environment variables:
   - NEXT_PUBLIC_API_URL=https://api.example.com
   - REVALIDATE_SECRET=<secret>
5. Deploy: vercel --prod
```

### Alternativa: VPS Node.js

```bash
1. SSH para VPS
2. git clone repo
3. npm install --production
4. npm run build
5. PM2: pm2 start npm --name "blog-frontend" -- start
6. Nginx reverse proxy para :3000
7. SSL via Certbot
```

---

## F8.8-F8.9 - Documentação & Validação

### Documentação

```
README.md (root)
backend/README.md
frontend/README.md
DEPLOYMENT.md
ARCHITECTURE.md
API.md
TROUBLESHOOTING.md
```

### Validação Produção

- [ ] Health check: `/api/v1/health` retorna 200
- [ ] Frontend carrega e renderiza
- [ ] Admin login funciona
- [ ] Criar/editar/publicar post
- [ ] Upload de imagem
- [ ] Formulário de contato
- [ ] Paginação funciona
- [ ] Idiomas funcionam
- [ ] SEO: Verifique hreflang, schema.org
- [ ] Performance: Lighthouse 90+
- [ ] Security: SSL, HSTS, CSP

---

## Checklist Final

### Código
- [ ] PHPUnit tests 80%+ coverage
- [ ] Jest tests 70%+ coverage
- [ ] Todos testes passando
- [ ] GitHub Actions workflow ativo

### Deploy
- [ ] Backend em produção (health check OK)
- [ ] Frontend em produção (Vercel ou VPS)
- [ ] Database migrada
- [ ] SSL/TLS configurado
- [ ] HTTPS enforced

### Monitoramento
- [ ] Sentry integrado (error tracking)
- [ ] Analytics configurado
- [ ] Log rotation setup
- [ ] Backup diário

### Documentação
- [ ] README completo
- [ ] API documented
- [ ] Deployment guide
- [ ] Troubleshooting guide

---

## Timeline Sugerida

```
Dia 1-2:   F8.1 + F8.2 (Testes backend)
Dia 3-4:   F8.3 (Testes frontend)
Dia 5-6:   F8.4 (GitHub Actions CI/CD)
Dia 7-10:  F8.5-F8.6 (Deploy backend)
Dia 11-12: F8.7 (Deploy frontend)
Dia 13-14: F8.8-F8.9 (Documentação + validação)
```

---

## Próximas Ações

1. **Agora:**
   - [ ] Criar testes unitários básicos
   - [ ] Setup GitHub Actions
   - [ ] Preparar VPS/Vercel

2. **Amanhã:**
   - [ ] Deploy backend em staging
   - [ ] Deploy frontend em staging
   - [ ] Testes de integração

3. **Próxima semana:**
   - [ ] Deploy em produção
   - [ ] Monitoramento setup
   - [ ] Documentação final

---

## Recursos

- PHPUnit: https://phpunit.de/
- Jest: https://jestjs.io/
- GitHub Actions: https://github.com/features/actions
- Vercel Deploy: https://vercel.com/docs
- Nginx: https://nginx.org/en/docs/
- Docker: https://docs.docker.com/

---

**Status:** 🚀 Pronto para Fase 8!

Desenvolvido por: GitHub Copilot CLI
Data: 2026-02-24 22:00 UTC
