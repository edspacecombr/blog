## 6. RECOMENDAÇÃO FINAL

### 6.1 Stack Definitiva (PRD v2.0)

```
┌─────────────────────────────────────────────────────────────────────┐
│                     STACK FINAL — PRD v2.0                          │
├──────────────────────┬──────────────────────────────────────────────┤
│ Backend (blog-api)   │ Laravel 11 — PHP 8.3 (API Headless, REST)    │
│ Auth API             │ Laravel Sanctum (SPA + Bearer token)         │
│ ORM / Banco          │ Eloquent + PostgreSQL 15+                    │
│ Cache / Queue        │ Redis + Horizon (VPS) / DB driver (shared)   │
│ Imagens              │ spatie/image → WebP + AVIF (job assíncrono)  │
│ Sanitização HTML     │ HTMLPurifier (PHP, server-side)              │
│ Sitemaps             │ Gerados pelo Laravel, servidos em /sitemap.xml│
├──────────────────────┼──────────────────────────────────────────────┤
│ Frontend (blog-front)│ Next.js 15 — TypeScript (App Router)         │
│ i18n Routing         │ next-intl ([locale] routing)                 │
│ Estilo               │ Tailwind CSS 4                               │
│ Editor Visual        │ GrapesJS (Client Component, somente /admin)  │
│ Drag-and-drop menus  │ @hello-pangea/dnd ou dnd-kit                 │
│ Imagens              │ next/image (WebP/AVIF automático)            │
│ Build                │ Turbopack (dev) / next build (prod)          │
├──────────────────────┼──────────────────────────────────────────────┤
│ Storage              │ Local (shared hosting) / MinIO ou S3 (VPS)  │
│ Deploy API           │ Shared hosting PHP ou VPS (Nginx + PHP-FPM)  │
│ Deploy Frontend      │ Vercel (recomendado) ou VPS Node.js (PM2)    │
│ CI/CD                │ GitHub Actions: lint + tests + deploy        │
└──────────────────────┴──────────────────────────────────────────────┘
```

---

### 6.2 Melhor Abordagem de Deploy

**`blog-api` — Shared Hosting (mínimo viável):**
```
Domínio API: api.meublog.com → public_html/blog-api/public/
    └─ PHP 8.3 via cPanel
    └─ Cron: */1 * * * * php /home/user/blog-api/artisan schedule:run
    └─ PostgreSQL + Database queue driver (Redis não disponível)
    └─ CORS: FRONTEND_URL=https://meublog.com
    └─ Storage: local disk (imagens públicas via storage:link)
```

**`blog-api` — VPS (recomendado para produção):**
```
Nginx → PHP-FPM 8.3 → Porta 443 (Let's Encrypt via Certbot)
    └─ PostgreSQL 15+ + Redis 7
    └─ Supervisor → Laravel Horizon (queue workers)
    └─ Cron via crontab do sistema
    └─ Backup: `pg_dump` diário via GitHub Actions ou cron
```

**`blog-frontend` — Vercel (recomendado):**
```
Vercel (Edge Network global)
    └─ Deploy automático ao push na branch main
    └─ Variáveis: NEXT_PUBLIC_API_URL, REVALIDATE_SECRET
    └─ ISR: stale-while-revalidate gerenciado pela Vercel
    └─ HTTPS automático + CDN para assets estáticos
```

**`blog-frontend` — VPS Node.js (alternativo):**
```
Nginx reverse proxy → PM2 (Node.js 20) → Porta 3000
    └─ pm2 start npm --name blog-frontend -- start
    └─ Nginx proxy_pass http://localhost:3000
    └─ Let's Encrypt para HTTPS
```

---

### 6.3 Riscos Críticos (Top 4)

| Prioridade | Risco | Ação Imediata |
|---|---|---|
| 🔴 CRÍTICO | CORS bloqueando Next.js → PHP API | Configurar `cors.php` com `allowed_origins` exatos em **F0.4** antes de qualquer desenvolvimento de frontend |
| 🔴 CRÍTICO | GrapesJS gerando HTML não-semântico, quebrando SEO | `HTMLPurifier` obrigatório em **F3.5** no `PostController` antes de qualquer publicação |
| 🔴 CRÍTICO | Next.js em shared hosting (Node.js indisponível) | Frontend **obrigatoriamente** na Vercel ou VPS com Node.js — não há workaround para shared hosting |
| 🟡 ALTO | Hidratação SSR/Client mismatch no Next.js | Separar rigorosamente Server Components e `'use client'` desde **F0.5**; nunca usar `window` em Server Components |

---

### 6.4 Prioridades de Desenvolvimento

```
Semana 1–2:  Fase 0 — Dois projetos + banco + CORS + auth funcionando
Semana 3–4:  Fase 1 (F1.1–F1.5) — Core API: CRUD Posts + Páginas + Autores
Semana 5:    Fase 1 (F1.6–F1.8) + Fase 2 (F2.1–F2.3) — Menus + Idiomas
Semana 6:    Fase 2 (F2.4–F2.8) — SeoService + SchemaService + Sitemaps
Semana 7:    Fase 3 — Mídia API + GrapesJS no Next.js Admin
Semana 8–9:  Fase 4 (F4.1–F4.6) — Painel Admin Next.js (CRUD)
Semana 10:   Fase 4 (F4.7–F4.9) — Mídia, Configurações, Idiomas no admin
Semana 11:   Fase 5 (F5.1–F5.3) — next-intl + generateMetadata + JSON-LD
Semana 12–13:Fase 5 (F5.4–F5.12) — 3 layouts + ISR + lazy loading
Semana 14:   Fase 6 — API REST pública + webhook revalidação ISR
Semana 15:   Fase 7 (F7.1–F7.4) — AdSense + páginas essenciais + contato
Semana 16:   Fase 7 (F7.5–F7.8) — Performance + Core Web Vitals + Segurança
Semana 17–18:Fase 8 — Testes + CI/CD + Deploy + Docs

MVP (F0 + F1 + F2 + F4 + F5 parcial): ~10–11 semanas
MVP completo publicável: ~13 semanas
```

---

### 6.5 Recomendações Adicionais

1. **CORS é a fundação da arquitetura desacoplada:** Resolver CORS correto em F0.4 antes de qualquer linha de frontend — uma configuração errada bloqueia 100% das chamadas.
2. **Nunca carregar GrapesJS fora de `'use client'`:** Um import acidental em Server Component quebra o build do Next.js. Usar `dynamic(() => import('./GrapesJSEditor'), { ssr: false })`.
3. **Webhook de revalidação ISR é o "cache invalidation" do sistema:** Implementar em F5.9 e F6.5 — sem isso, o conteúdo publicado pode demorar horas para aparecer.
4. **`next/image` substitui toda a lógica de lazy loading manual:** Usar `priority` na imagem hero, `sizes` adequados e confiar no otimizador embutido.
5. **Sitemaps XML no Laravel, não no Next.js:** O Laravel tem acesso direto ao banco e gera os sitemaps de forma assíncrona — evita re-queries desnecessárias pelo Next.js.
6. **API versionada em `/api/v1/` desde o início:** Necessário para manter compatibilidade com n8n/Make ao evoluir a API.
7. **Dois `.env.example` documentados:** Um em `blog-api/` e um em `blog-frontend/` — inclui todas as variáveis obrigatórias com comentários.

---

*Documento revisado pelo Agente de Arquitetura e Planejamento de Execução.*
*PRD v2.0 → Stack: PHP 8 (Laravel 11) API + Next.js 15 | Repositório: edspacecombr/blog | Branch: feature/arquitetura | Data: 2026-02-21*