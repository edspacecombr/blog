# 1. ANÁLISE COMPLETA DO PRD

## 1.1 Complexidade Geral

| Dimensão | Nível | Justificativa |
|---|---|---|
| Backend | **Alta** | CMS próprio, API REST, multilíngue, agendamento, roles |
| Frontend | **Alta** | GrapesJS integrado, drag-and-drop menus, múltiplos layouts |
| SEO/i18n | **Alta** | hreflang, sitemaps por idioma, schema markup JSON-LD |
| DevOps | **Alta** | Dois projetos independentes (PHP API + Next.js), CORS, Node.js em VPS obrigatório |
| Banco de dados | **Alta** | Modelo translatable, relações entre idiomas, hierarquias |

**Classificação geral: Projeto Complexo de Médio-Grande Porte**
Estimativa de desenvolvimento solo (senior): **16–24 semanas**
Estimativa com time de 2–3 devs: **8–12 semanas**

---

## 1.2 Módulos Identificados

1. **Setup Wizard** — pré-instalação guiada com nicho/idioma
2. **Auth & Users** — autenticação, perfis de autor, papéis
3. **Posts & Páginas** — CRUD, editor GrapesJS, status, agendamento
4. **Multilíngue (i18n Core)** — idiomas, slugs por idioma, relações entre traduções
5. **SEO Engine** — meta tags, Open Graph, hreflang, sitemap XML, robots.txt, schema JSON-LD
6. **Mídia Library** — upload, WebP/AVIF, alt por idioma, organização
7. **Menus & Navegação** — menus dinâmicos drag-and-drop, multi-nível
8. **Layouts & Temas** — 3 layouts, troca sem perda de conteúdo
9. **AdSense & Monetização** — blocos de anúncio configuráveis por idioma
10. **API REST** — endpoints externos para n8n/Make/scripts
11. **Conformidade Legal** — gerador de páginas essenciais (Privacidade, ToS, About, Contato)

---

## 1.3 Dependências Críticas entre Módulos

```
Setup Wizard
    └─► Auth & Users
            └─► Posts & Páginas
                    └─► Multilíngue (i18n Core)
                            ├─► SEO Engine
                            ├─► Menus & Navegação
                            └─► Sitemaps
Posts & Páginas
    └─► Editor GrapesJS
            └─► Mídia Library
SEO Engine
    └─► Schema JSON-LD
    └─► Sitemap XML
API REST
    └─► Posts & Páginas
    └─► Auth (token)
```

---

## 1.4 Riscos Identificados

| Código | Risco | Probabilidade | Impacto | Mitigação |
|---|---|---|---|---|
| R01 | Integração GrapesJS com backend pode gerar HTML não-semântico | Alta | Alto (SEO) | Sanitização e validação de output obrigatória |
| R02 | Performance em shared hosting com múltiplos idiomas e sitemaps | Média | Alto | Cache agressivo, sitemaps gerados em fila/background |
| R03 | Complexidade do modelo de dados multilíngue | Alta | Médio | Usar padrão de tabela `_translations` desde o início |
| R04 | Plugin GrapesJS vs. Core Web Vitals (JS pesado) | Média | Alto | GrapesJS apenas no painel admin, nunca no frontend público |
| R05 | Slug duplicado entre idiomas | Média | Médio | Slugs únicos por `(idioma, slug)` a nível de banco |
| R06 | Schema markup incorreto → penalidade Google | Média | Alto | Validação com Google Rich Results Test automatizada |
| R07 | Falta de HTTPS em shared hosting legado | Baixa | Alto | Let's Encrypt obrigatório no setup wizard |
| R08 | Scope creep no editor visual | Alta | Médio | Limitar MVP: GrapesJS básico, expandir depois |
| R09 | CORS mal configurado bloqueando chamadas Next.js → PHP API | Alta | Alto | Configurar `cors.php` no Laravel com origens restritas desde F0.1 |
| R10 | Shared hosting sem suporte a Node.js (Next.js exige runtime Node) | Alta | Alto | Next.js implantado na Vercel (frontend) + PHP API no shared hosting separado |
| R11 | Hidratação incorreta no Next.js (SSR vs. Client mismatch) | Média | Médio | Separar claramente Server Components e Client Components desde o início |

---

## 1.5 Pontos Críticos de SEO, i18n e AdSense

### SEO
- `lang` attribute no `<html>` dinâmico por idioma
- `<link rel="canonical">` obrigatório em todas as páginas
- `<link rel="alternate" hreflang="x">` em todas as páginas com tradução
- Sitemap index + sitemaps individuais por idioma
- Schema `Article` + `BreadcrumbList` + `Author` + `Organization`
- URLs limpas: `/pt/categoria/slug-do-artigo/`

### i18n
- Conteúdo independente por idioma (não forçar tradução)
- Fallback de idioma configurável
- Slugs localizados (ex: `/en/technology/` vs `/pt/tecnologia/`)
- Datas, números e moedas formatados por locale

### AdSense
- Páginas obrigatórias: Privacy Policy, Terms of Service, About, Contact
- Design limpo sem pop-ups intrusivos
- Identificação clara de conteúdo patrocinado
- Blocos de anúncio com `data-ad-*` configuráveis por idioma/país
