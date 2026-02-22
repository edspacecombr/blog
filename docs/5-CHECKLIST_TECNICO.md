## 5. CHECKLIST TÉCNICO

### 5.1 Backend (Laravel 11 — API Headless)
- [ ] PHP >= 8.2 com extensões: `intl`, `gd`/`imagick`, `redis`, `mbstring`
- [ ] Laravel Sanctum configurado: `stateful_domains`, `session.domain`, cookie `httponly`
- [ ] `spatie/laravel-translatable` ou colunas JSON nativas para campos curtos
- [ ] `spatie/image` para conversão WebP/AVIF assíncrona
- [ ] `HTMLPurifier` para sanear output do GrapesJS antes de persistir
- [ ] `config/cors.php`: `allowed_origins` com URL exata do Next.js (não `*`)
- [ ] Filas configuradas (Redis + Horizon em VPS, database driver em shared hosting)
- [ ] Cron: `* * * * * php artisan schedule:run`
- [ ] Rate limiting: `throttle:60,1` nas rotas de API, `throttle:10,1` no upload
- [ ] `AuthToken` em tabela separada, hash SHA-256, rotação via `PATCH /api/v1/auth/rotate`
- [ ] Todos os endpoints retornam `Content-Type: application/json`
- [ ] Logging estruturado (Laravel Telescope em dev, stack log em prod)
- [ ] `.env` com `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` correto

### 5.2 Frontend (Next.js 15 + TypeScript)
- [ ] Next.js 15 com App Router e TypeScript estrito (`strict: true`)
- [ ] `next-intl` configurado: `[locale]` routing, `middleware.ts`, `i18n/messages/pt.json`, `en.json`...
- [ ] `generateMetadata()` em todas as rotas públicas: `title`, `description`, `canonical`, `alternates.languages`, `openGraph`, `twitter`
- [ ] JSON-LD em `<script type="application/ld+json">` via Server Components (sem `'use client'`)
- [ ] GrapesJS instanciado apenas em `'use client'` components dentro de `/admin` — nunca importado em Server Components
- [ ] `next/image` com `sizes`, `placeholder="blur"`, `formats: ['image/avif', 'image/webp']` no `next.config.ts`
- [ ] `loading="lazy"` implícito via `next/image`; hero image com `priority={true}`
- [ ] ISR com `revalidate` adequado por rota; webhook `revalidatePath` chamado pelo Laravel ao publicar
- [ ] `@hello-pangea/dnd` ou `dnd-kit` para drag-and-drop de menus no admin
- [ ] Tailwind CSS 4 com `purge` configurado, `font-display: swap` na fontface
- [ ] `middleware.ts`: locale detection + redirect `/admin` para login se sem cookie de sessão Sanctum
- [ ] Bundle analyzer: `@next/bundle-analyzer` validado pré-deploy
- [ ] `NEXT_PUBLIC_API_URL` e `REVALIDATE_SECRET` como variáveis de ambiente (não hardcoded)

### 5.3 Banco de Dados
- [ ] PostgreSQL >= 14 (recomendado 15+) — `jsonb` para campos translatable, `citext` para slugs
- [ ] Extensões recomendadas: `pg_trgm`, `unaccent`, `citext`
- [ ] UTF-8 (padrão PostgreSQL) em todas as bases
- [ ] Índices em: `(slug, language_code)` (use `citext` para case-insensitive), `status`, `published_at`, `author_id`
- [ ] Full-text search via `tsvector` + GIN index em `post_translations.title` e `post_translations.body` (futuro)
- [ ] Backups automáticos diários via `pg_dump`/dump strategy configurados

### 5.4 SEO
- [ ] `<html lang="{locale}">` dinâmico
- [ ] `<link rel="canonical" href="...">` em toda página
- [ ] `<link rel="alternate" hreflang="...">` para cada idioma disponível
- [ ] Meta robots `noindex` em: paginação profunda, drafts, busca interna
- [ ] Schema JSON-LD: `Article`, `BreadcrumbList`, `Author`, `Organization`
- [ ] Sitemap index: `/sitemap.xml` → `/sitemap-pt.xml`, `/sitemap-en.xml`...
- [ ] Sitemap com `<lastmod>`, `<changefreq>`, `<priority>`
- [ ] Open Graph: `og:title`, `og:description`, `og:image`, `og:locale`
- [ ] Twitter Cards: `twitter:card`, `twitter:title`, `twitter:image`
- [ ] Paginação: `<link rel="prev/next">` (ou `rel="canonical"` por page)
- [ ] Breadcrumbs visíveis + schema BreadcrumbList
- [ ] URLs: sem trailing slash duplo, sem parâmetros desnecessários, minúsculas

### 5.5 Performance (Core Web Vitals)
- [ ] LCP < 2.5s: imagem hero com `loading="eager"` e `fetchpriority="high"`
- [ ] CLS < 0.1: dimensões explícitas em todos os `<img>` e `<video>`
- [ ] FID/INP < 100ms: sem JS pesado no critical path do frontend público
- [ ] TTFB < 600ms: cache de resposta HTTP para páginas públicas
- [ ] Gzip/Brotli habilitado no servidor
- [ ] CSS crítico inline ou deferido
- [ ] JS diferido com `defer` ou carregado no final do `<body>`
- [ ] Font display: `font-display: swap`
- [ ] Imagens com dimensões explícitas (width/height attributes)

### 5.6 Segurança
- [ ] HTTPS obrigatório + HSTS (`Strict-Transport-Security`)
- [ ] CSRF protection (nativo Laravel) em todos os formulários
- [ ] Content Security Policy (CSP) header
- [ ] X-Frame-Options: SAMEORIGIN
- [ ] X-Content-Type-Options: nosniff
- [ ] Input sanitization em todos os campos
- [ ] Prepared statements (Eloquent ORM, sem raw queries não sanitizadas)
- [ ] Honeypot no formulário de contato
- [ ] Uploads: validação de tipo MIME real (não apenas extensão), armazenar fora do webroot
- [ ] `.env` nunca commitado, `.env.example` bem documentado
- [ ] Permissões de arquivo: `storage/` e `bootstrap/cache/` com 755

### 5.7 Deploy
**`blog-api` (Laravel PHP):**
- [ ] Shared hosting: `public/` como webroot PHP, `.htaccess` correto, cron `schedule:run` via cPanel
- [ ] VPS: Nginx + PHP-FPM 8.3, Supervisor para queues, Let's Encrypt via Certbot
- [ ] `php artisan config:cache && route:cache && view:cache` em produção
- [ ] `storage:link` executado após cada deploy
- [ ] Migrations com `--force` em produção
- [ ] Variáveis de ambiente definidas no servidor (não no repositório)
- [ ] `FRONTEND_URL` no `.env` apontando para a URL do Next.js (usado no CORS)

**`blog-frontend` (Next.js):**
- [ ] Vercel (recomendado): `vercel --prod`, variáveis `NEXT_PUBLIC_API_URL`, `REVALIDATE_SECRET` no painel
- [ ] VPS alternativo: Node.js >= 20, PM2 (`pm2 start npm -- start`), Nginx reverse proxy porta 3000
- [ ] `next build` sem erros de TypeScript e sem warnings de lint
- [ ] `NEXT_PUBLIC_API_URL` deve apontar para a URL pública do `blog-api`
- [ ] Caching headers configurados no Nginx/Vercel para assets estáticos (`/_next/static/*`)
- [ ] Monitoramento básico de uptime configurado (ambos os projetos)

---