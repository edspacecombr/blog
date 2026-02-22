## 4. PLANO DE EXECUÇÃO

> **Nota sobre a nova stack:** A arquitetura desacoplada (PHP API + Next.js) divide o trabalho em dois projetos paralelos com fronteiras bem definidas. As fases 0–2 focam no backend (Laravel API), as fases 3–5 no frontend (Next.js), e as fases 6–8 na integração, performance e deploy dos dois projetos juntos.

### 4.1 Visão por Fases

```
Fase 0 → Fundação — Dois Projetos + Banco + Auth (2 semanas)
Fase 1 → Core API Backend (Laravel) (3 semanas)
Fase 2 → Multilíngue + SEO Engine no Backend (3 semanas)
Fase 3 → Mídia API + Editor GrapesJS (Next.js Admin) (2 semanas)
Fase 4 → Painel Admin em Next.js (3 semanas)
Fase 5 → Frontend Público Next.js (SSR/ISR + 3 layouts) (3 semanas)
Fase 6 → API REST Pública + Automação (1 semana)
Fase 7 → AdSense + Conformidade + Performance (2 semanas)
Fase 8 → Testes, Deploy e Documentação (2 semanas)
─────────────────────────────────────────────────────────
Total estimado: 21 semanas (solo developer)
MVP (Fase 0 + 1 + 2 + 4 + 5): ~13 semanas
```

---

### FASE 0 — Fundação (Dois Projetos + Banco + Auth)
**Critério de conclusão:** `blog-api` respondendo com JSON em `/api/v1/health`, `blog-frontend` renderizando página vazia em `localhost:3000`, banco migrado, CORS funcional, login via Sanctum retornando token.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F0.1 | Setup `blog-api` (Laravel) | `laravel new blog-api --api`, `.env` com PostgreSQL + Redis, `APP_URL` | — |
| F0.2 | Migrations base | Todas as tabelas do modelo de dados (seção 3) | F0.1 |
| F0.3 | Autenticação API (Sanctum) | `laravel/sanctum`, endpoint `POST /api/v1/auth/login`, roles (superadmin, admin, editor), geração de `api_token` | F0.1 |
| F0.4 | CORS configurado | `config/cors.php`: orıgens restritas (`FRONTEND_URL`), headers `Authorization` e `Content-Type` liberados | F0.1 |
| F0.5 | Setup `blog-frontend` (Next.js) | `npx create-next-app@latest blog-frontend --typescript --app --tailwind`, configurar `NEXT_PUBLIC_API_URL` | — |
| F0.6 | Cliente API no Next.js | `lib/api.ts`: wrapper `fetch` com interceptor de token, tipagens TypeScript dos recursos | F0.3, F0.5 |
| F0.7 | Auth guard Admin (Next.js) | `middleware.ts` redireciona `/admin/*` para `/admin/login` se não autenticado; cookies httpOnly via Sanctum SPA | F0.5, F0.3 |
| F0.8 | Middleware `EnsureSetupComplete` | Bloqueia todos endpoints exceto `/api/v1/setup/*` até `setup_completed = true` no `site_settings` | F0.2 |

---

### FASE 1 — Core API Backend (Laravel)
**Critério de conclusão:** Todos os endpoints CRUD de posts, páginas, categorias e autores respondendo corretamente com paginação, validação e API Resources.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F1.1 | API Resource base | `PostResource`, `PageResource`, `CategoryResource` com campos SEO e translatable | F0.2 |
| F1.2 | CRUD Posts (API) | `PostController@index,show,store,update,destroy`, FormRequest, filtros por status/idioma/categoria | F1.1 |
| F1.3 | CRUD Páginas Estáticas (API) | Similar ao F1.2, tipos especiais (system pages: privacy, terms, about, contact) | F1.1 |
| F1.4 | CRUD Categorias (API) | Com hierarquia (`parent_id`), translatable via `post_translations` pattern | F1.1 |
| F1.5 | Gestão de Autores (API) | Perfil, bio JSON multilíngue, avatar (path), social_links, schema_same_as | F0.3, F0.2 |
| F1.6 | CRUD Menus + MenuItems (API) | Endpoints para menus e itens, suporte polimórfico (`linkable_type/id`), reordenação | F1.2, F1.3, F1.4 |
| F1.7 | Agendamento de Posts | Job `PublishScheduled`, `schedule()` no Kernel, campo `scheduled_at`, `POST /api/v1/posts/{id}/schedule` | F1.2 |
| F1.8 | API Resources com paginação | Paginação consistente em todos os `index` endpoints, `meta.total`, `links.next` | F1.2–F1.5 |

---

### FASE 2 — Multilíngue + SEO Engine no Backend
**Critério de conclusão:** API retornando dados de hreflang, schema JSON-LD e sitemaps XML por idioma; endpoint de robots.txt funcionando.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F2.1 | CRUD Idiomas (API) | `GET/POST/PATCH /api/v1/languages`, definir padrão, ativar/desativar | F0.2 |
| F2.2 | Suporte a traduções na API de Posts | `POST /api/v1/posts/{id}/translations`, update parcial por idioma, `post_relations` opcional | F1.2, F2.1 |
| F2.3 | SeoService | `buildHreflangPayload()`, `buildCanonicalUrl()`, `buildOpenGraph()` — retornados como objeto no JSON do post | F2.1 |
| F2.4 | SchemaService | `buildArticleSchema()`, `buildBreadcrumbSchema()`, `buildAuthorSchema()`, `buildOrgSchema()` — retornados no JSON | F1.5, F2.3 |
| F2.5 | SitemapService | `GenerateSitemap` job: gera `/public/sitemap.xml` (index) + `/public/sitemap-{lang}.xml` por idioma; disparado no publish | F2.1, F2.3 |
| F2.6 | Endpoint `GET /sitemap.xml` | Serves sitemap gerado ou force-regenera se desatualizado | F2.5 |
| F2.7 | Endpoint `GET /robots.txt` | Gerado dinamicamente a partir de `site_settings.robots_txt_content` | F0.2 |
| F2.8 | API de Configurações Globais SEO | `GET/PATCH /api/v1/settings` com grupo `seo`: title pattern, site name, default og:image | F0.2 |

---

### FASE 3 — Mídia API + Editor GrapesJS (Next.js Admin)
**Critério de conclusão:** Upload de imagem via API PHP retornando URLs WebP/AVIF; componente GrapesJS salvando `body` HTML sanitizado e `grapesjs_data` JSON na API.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F3.1 | Upload de mídia (API PHP) | `POST /api/v1/media`, validação MIME real, storage local ou S3, retorna `{id, url, webp_url}` | F0.3 |
| F3.2 | Conversão WebP/AVIF (Job) | `spatie/image` ou `Intervention Image`, job assíncrono pós-upload, atualiza `webp_path`/`avif_path` | F3.1 |
| F3.3 | Alt text multilíngue (API) | `PATCH /api/v1/media/{id}`: atualiza `alt_text` JSON por idioma | F3.1, F2.1 |
| F3.4 | Componente GrapesJS (Next.js) | `components/admin/GrapesJSEditor.tsx` (`'use client'`), plugins drag-and-drop, salva `body` + `grapesjs_data` via `lib/api.ts` | F1.2, F0.5 |
| F3.5 | Sanitização no save (PHP) | `HTMLPurifier` aplicado no `PostController@store/update` antes de persistir `body` | F3.4, F1.2 |
| F3.6 | MediaPicker modal (Next.js) | `components/admin/MediaPicker.tsx`: grid de mídia, pesquisa por nome/tag, URL inserida no GrapesJS | F3.1, F3.4 |

---

### FASE 4 — Painel Admin em Next.js
**Critério de conclusão:** Todas as telas do admin funcionando (CRUD posts, páginas, categorias, autores, menus, mídias, configurações) com GrapesJS integrado e Setup Wizard completo.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F4.1 | Setup Wizard 5 passos (Next.js) | `/admin/setup/`: coleta info do blog → identidade visual → idiomas → nicho/layout → confirmação; chama `POST /api/v1/setup/complete` | F0.7, F2.1 |
| F4.2 | Listagem e CRUD de Posts (Admin) | `/admin/posts/`: tabela com filtros, `/admin/posts/new` e `/admin/posts/[id]/edit` com GrapesJS + abas por idioma | F3.4, F1.2, F2.2 |
| F4.3 | CRUD Páginas Estáticas (Admin) | `/admin/pages/`: similar ao F4.2, com indicador de tipo (system page) | F1.3, F3.4 |
| F4.4 | CRUD Categorias (Admin) | `/admin/categories/`: tabela hierárquica, formulário com campos translatable por idioma | F1.4 |
| F4.5 | Gestão de Autores (Admin) | `/admin/authors/`: formulário com bio multilíngue, upload de avatar via MediaPicker | F1.5, F3.6 |
| F4.6 | Gestão de Menus (Admin) | `/admin/menus/`: `@hello-pangea/dnd` ou `dnd-kit` para drag-and-drop, vinculação polimórfica | F1.6 |
| F4.7 | Biblioteca de Mídia (Admin) | `/admin/media/`: grid, upload direto para API PHP, edição de alt text por idioma | F3.1, F3.6 |
| F4.8 | Configurações Gerais (Admin) | `/admin/settings/`: grupos general, seo, appearance (layout ativo), monetization (AdSense ID por idioma) | F2.8 |
| F4.9 | Gestão de Idiomas (Admin) | `/admin/languages/`: CRUD, definir padrão, ativar/desativar | F2.1 |

---

### FASE 5 — Frontend Público Next.js (SSR/ISR + Layouts)
**Critério de conclusão:** 3 layouts renderizando no frontend público com SSR, hreflang correto no `<head>`, schema JSON-LD, ISR com revalidation, lazy loading e paginação SEO-friendly.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F5.1 | Configurar `next-intl` + App Router | `i18n.ts`, `middleware.ts`, `[locale]` routing, fallback de idioma, `generateStaticParams` | F0.5, F2.1 |
| F5.2 | `generateMetadata()` global | `lib/seo.ts`: monta `title`, `description`, `canonical`, `alternates.languages` (hreflang), `openGraph`, `twitter` | F2.3, F5.1 |
| F5.3 | JSON-LD Server Components | `lib/schema.ts` + `<script type="application/ld+json">`: Article, BreadcrumbList, Author, Organization | F2.4, F5.1 |
| F5.4 | Layout 1: Editorial Clean | `components/layouts/Layout1Clean.tsx`: Header, Hero, Grid de cards 3 colunas, Sidebar, Footer | F5.1 |
| F5.5 | Layout 2: Magazine | `components/layouts/Layout2Magazine.tsx`: Multi-coluna, featured post em destaque, grid secundário | F5.1 |
| F5.6 | Layout 3: Minimal Blog | `components/layouts/Layout3Minimal.tsx`: lista simples, foco em leitura, sem sidebar | F5.1 |
| F5.7 | Componentes reutilizáveis | `PostCard`, `CategoryBadge`, `AuthorBox`, `Breadcrumb`, `Pagination` | F5.4–F5.6 |
| F5.8 | Rotas de conteúdo | `[locale]/page.tsx` (home ISR), `[locale]/[category]/[slug]/page.tsx` (post ISR), `[locale]/[...slug]/page.tsx` (páginas) | F5.1–F5.3 |
| F5.9 | ISR e revalidação | `revalidate: 3600` em páginas de post; `revalidatePath` disparado via webhook do Laravel ao publicar | F5.8, F1.7 |
| F5.10 | Lazy loading e `<picture>` | `next/image` com `sizes`, `placeholder="blur"`, formatos WebP/AVIF via `formats` do Next.js | F5.7 |
| F5.11 | Paginação SEO-friendly | `rel="canonical"` por página, `noindex` em profundidade > 5, query param `?page=N` | F5.8 |
| F5.12 | Troca de layout via setting | `SiteSetting.active_layout` lido em Server Component root — sem perda de conteúdo | F4.8, F5.4–F5.6 |

---

### FASE 6 — API REST Pública + Automação
**Critério de conclusão:** Endpoints de automação documentados e testados com n8n; webhook de revalidação Next.js funcional.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F6.1 | Endpoint externo: Criar post | `POST /api/v1/posts` com Bearer token de automação, idioma, conteúdo, status | F1.2, F0.3 |
| F6.2 | Endpoint externo: Atualizar post | `PUT /api/v1/posts/{id}`, update parcial por idioma | F6.1 |
| F6.3 | Endpoint externo: Publicar post | `PATCH /api/v1/posts/{id}/publish` → dispara `GenerateSitemap` + webhook revalidação | F6.1, F5.9 |
| F6.4 | Endpoint externo: Upload mídia | `POST /api/v1/media` com Bearer token | F3.1, F0.3 |
| F6.5 | Webhook de revalidação ISR | `POST /api/v1/revalidate` no Next.js (com `REVALIDATE_SECRET`) disparado pelo Laravel ao publicar | F5.9, F6.3 |
| F6.6 | Rate limiting + throttle | `throttle:60,1` nas rotas de API pública, `throttle:10,1` no upload | F0.3 |
| F6.7 | Documentação da API | `openapi.yaml` ou `docs/api.md` com exemplos para n8n/Make | F6.1–F6.5 |

---

### FASE 7 — AdSense + Conformidade + Performance
**Critério de conclusão:** Core Web Vitals aprovados localmente (Lighthouse ≥ 90), páginas essenciais geradas, blocos AdSense configuráveis por idioma, HTTPS enforçado.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F7.1 | Gerador de páginas essenciais | Seed via `SetupService`: cria `pages` do tipo privacy, terms, about, contact com templates i18n | F1.3, F2.1 |
| F7.2 | Formulário de contato (Next.js) | Componente React com honeypot anti-spam, chama `POST /api/v1/contact` no Laravel (envia e-mail via SMTP) | F1.3 |
| F7.3 | Componente AdSense (Next.js) | `components/public/AdBlock.tsx` (`'use client'`): carrega `adsbygoogle` por idioma/posição, configurável via `SiteSetting` | F5.4–F5.6 |
| F7.4 | Build e otimização Next.js | `next build` com análise de bundle (`@next/bundle-analyzer`), remoção de imports desnecessários | F5.1–F5.12 |
| F7.5 | Compressão Gzip/Brotli | Configuração Nginx no VPS; Vercel faz automaticamente | F0.5 |
| F7.6 | Auditoria Core Web Vitals | Lighthouse CI no GitHub Actions: LCP < 2.5s, CLS < 0.1, INP < 200ms | F5.1–F5.12 |
| F7.7 | HTTPS + HSTS (Laravel API) | Redirect HTTP → HTTPS, header `Strict-Transport-Security`, `ForceScheme` middleware | F0.1 |
| F7.8 | Segurança API | CORS restrito, CSP headers, Sanctum token rotation, `X-Content-Type-Options`, SQL via Eloquent | F0.4 |

---

### FASE 8 — Testes, Deploy e Documentação
**Critério de conclusão:** `blog-api` deployado em shared hosting ou VPS, `blog-frontend` deployado na Vercel ou VPS Node.js, testes passando, documentação entregue.

| # | Tarefa | Subtarefas | Dependências |
|---|---|---|---|
| F8.1 | Testes unitários (PHP) | PHPUnit: `SeoService`, `SchemaService`, `SitemapService`, `MediaService` | F2.3–F2.5 |
| F8.2 | Testes de feature (PHP) | Laravel Feature Tests: todos os endpoints CRUD, auth, rate limit | F1.2–F1.8, F6.1–F6.5 |
| F8.3 | Testes Next.js | Jest + React Testing Library: componentes de layout, `generateMetadata`, schema helpers | F5.2–F5.8 |
| F8.4 | GitHub Actions — CI | Lint + tests na PR: `phpunit` para API, `jest` para Next.js | F8.1–F8.3 |
| F8.5 | Deploy `blog-api` (Shared Hosting) | rsync/FTP, `public/` como webroot PHP, cron `schedule:run`, migrations `--force` | F8.4 |
| F8.6 | Deploy `blog-api` (VPS alternativo) | Nginx + PHP-FPM, Supervisor para queues, Let's Encrypt, Redis | F8.4 |
| F8.7 | Deploy `blog-frontend` (Vercel) | `vercel --prod`, variáveis `NEXT_PUBLIC_API_URL`, `REVALIDATE_SECRET` | F8.4 |
| F8.8 | Deploy `blog-frontend` (VPS alternativo) | PM2 + Nginx reverse proxy para Node.js porta 3000 | F8.4 |
| F8.9 | Documentação técnica | `README.md` (ambos projetos), guia de configuração, `docs/api.md` | All |

---