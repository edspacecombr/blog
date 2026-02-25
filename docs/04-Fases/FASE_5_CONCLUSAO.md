# FASE 5 - CONCLUSÃO - Últimas funcionalidades

## F5.9 - ISR e Revalidação

Implementar revalidação em todas as rotas públicas:
- `revalidate: 3600` em /app/[locale]/page.tsx
- `revalidate: 3600` em /app/[locale]/post/[slug]/page.tsx
- `revalidate: 86400` em páginas estáticas

Webhook para revalidação on-demand via API da Fase 6.

## F5.10 - Lazy Loading e Imagens Otimizadas

- Usar `next/image` em PostCard.tsx
- Adicionar `sizes` responsive
- Usar `placeholder="blur"`
- next/image suporta WebP/AVIF automaticamente

## F5.11 - Paginação SEO-friendly

- Adicionar rel="canonical" por página
- Adicionar `noindex` em profundidade > 5
- Query param `?page=N` em urls de categoria

## F5.12 - Troca de Layout Dinâmica

- Ler `SiteSetting.active_layout` da API
- Renderizar Layout1/Layout2/Layout3 dinamicamente
- Sem perda de conteúdo

---

Estimativa: 4 horas para todas as 4 funcionalidades
Total Fase 5 após: 100% ✅

Próximo: Fase 6 (API REST Pública)
