# Blog Profissional - Frontend

Next.js 14 frontend para blog multilíngue com painel administrativo e site público otimizado para SEO.

## 🚀 Quick Start

### Pré-requisitos
- Node.js 18+
- npm 9+
- Git

### Instalação Local

```bash
# Clone o repositório
git clone https://github.com/seu-usuario/blog-profissional.git
cd blog-profissional/frontend

# Instale dependências
npm install

# Configure ambiente
cp .env.example .env.local
# Edite .env.local com suas configurações

# Inicie desenvolvimento
npm run dev

# Acesse
# - Admin: http://localhost:3000/admin
# - Site: http://localhost:3000
```

## 📋 Funcionalidades

### 🔐 Painel Administrativo
- ✅ Setup Wizard (4 passos)
- ✅ Dashboard com estatísticas
- ✅ CRUD Posts com GrapesJS editor
- ✅ CRUD Páginas estáticas
- ✅ Gestão de categorias (hierárquica)
- ✅ Gestão de autores com avatar
- ✅ Menu builder com drag-and-drop
- ✅ Biblioteca de mídia com upload
- ✅ Configurações globais (SEO, AdSense, aparência)
- ✅ Gestão de idiomas

### 🌍 Site Público
- ✅ 3 Layouts responsivos (Clean, Magazine, Minimal)
- ✅ SSR + ISR (Incremental Static Regeneration)
- ✅ i18n com next-intl (3+ idiomas)
- ✅ SEO completo:
  - hreflang automático
  - Canonical URLs
  - JSON-LD schemas
  - Sitemap dinâmico
  - Meta tags OG/Twitter
- ✅ Lazy loading de imagens
- ✅ Paginação SEO-friendly
- ✅ Breadcrumbs dinâmicos
- ✅ Formulário de contato
- ✅ AdSense integrado

## 🛠️ Desenvolvimento

### Scripts

```bash
# Desenvolvimento
npm run dev              # Servidor de desenvolvimento

# Build
npm run build           # Build para produção
npm run start           # Executar build produção local

# Testes
npm run test            # Jest com watch
npm run test:ci         # CI mode com coverage
npm run test:coverage   # Relatório de cobertura

# Validação
npm run lint            # ESLint
npm run type-check      # TypeScript check
```

### Estrutura de Pastas

```
src/
├── app/
│   ├── (admin)/        # Rotas administrativas
│   │   ├── admin/
│   │   │   ├── dashboard
│   │   │   ├── posts
│   │   │   ├── pages
│   │   │   ├── categories
│   │   │   ├── authors
│   │   │   ├── media
│   │   │   ├── menus
│   │   │   ├── settings
│   │   │   ├── languages
│   │   │   └── setup
│   │   └── login/
│   ├── (public)/       # Rotas públicas
│   │   ├── [locale]/   # i18n
│   │   │   ├── page (home ISR)
│   │   │   ├── [category]/[slug]/page (post ISR)
│   │   │   └── [...slug]/page (páginas estáticas)
│   │   └── layout
│   └── api/            # API routes
│       ├── revalidate/ # Webhook ISR
│       └── contact/    # Contact form
├── components/
│   ├── admin/          # Componentes admin
│   ├── public/         # Componentes públicos
│   └── layouts/        # Layouts de tema
├── lib/
│   ├── api.ts          # Cliente API
│   ├── seo.ts          # Helpers SEO
│   ├── schema.ts       # JSON-LD helpers
│   ├── validators/     # Zod schemas
│   └── store/          # Zustand stores
├── hooks/              # Custom hooks
└── __tests__/          # Testes Jest
```

## 🎨 Layouts

### Layout 1: Editorial Clean
Grid 3 colunas com sidebar, ideal para blogs profissionais.

### Layout 2: Magazine
Multi-coluna com featured post em destaque, otimizado para grande volume.

### Layout 3: Minimal Blog
Lista simples focada em leitura, minimalista.

Todos com suporte a:
- Responsividade mobile-first
- Dark mode ready
- Acessibilidade (WCAG 2.1)
- Lazy loading de imagens
- Core Web Vitals otimizados

## 📱 Responsividade

```css
/* Mobile First */
- Mobile: 320px+
- Tablet: 768px+
- Desktop: 1024px+
- Wide: 1440px+
```

## 🌐 i18n (next-intl)

Suporta múltiplos idiomas:

```typescript
// app/[locale]/page.tsx
import { useTranslations } from 'next-intl';

export default function Home() {
  const t = useTranslations('home');
  return <h1>{t('title')}</h1>;
}
```

Adicione mensagens em: `public/locales/[locale].json`

## 🔗 Integração API

Cliente configurado em `lib/api.ts`:

```typescript
import { api } from '@/lib/api';

// GET
const posts = await api.get('/posts');

// POST
await api.post('/posts', { title: 'New' });

// PUT
await api.put('/posts/1', { title: 'Updated' });

// DELETE
await api.delete('/posts/1');
```

Inclui:
- Interceptor de token JWT
- Tratamento de erros
- Retry automático
- Timeout

## 🧪 Testes

```bash
# Executar testes
npm run test

# Com cobertura
npm run test:coverage

# Modo watch
npm run test -- --watch
```

Estrutura de testes:

```
__tests__/
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

## 📊 SEO

### Meta Tags
```typescript
export const metadata = generateMetadata({
  title: 'Post Title',
  description: 'Post description',
  canonicalUrl: 'https://example.com/post',
  image: '/image.jpg',
  languages: ['en', 'pt', 'es']
});
```

### JSON-LD Schemas
```typescript
// Article, BreadcrumbList, Author, Organization
export function ArticleSchema({ post }) {
  return (
    <script type="application/ld+json">
      {JSON.stringify(buildArticleSchema(post))}
    </script>
  );
}
```

## 🚀 Deploy

### Vercel (Recomendado)

```bash
# 1. Conectar repositório ao Vercel
# 2. Configurar variáveis:
#    - NEXT_PUBLIC_API_URL
#    - REVALIDATE_SECRET

# 3. Deploy automático no push para main
```

### VPS com PM2

```bash
npm run build
pm2 start npm --name blog-frontend -- start
pm2 save
```

### Docker

```bash
docker build -t blog-frontend .
docker run -p 3000:3000 blog-frontend
```

## 🔍 Performance

**Core Web Vitals:**
- LCP: < 2.5s ✅
- FID: < 100ms ✅
- CLS: < 0.1 ✅

**Otimizações:**
- ✅ Image optimization (WebP/AVIF)
- ✅ Code splitting automático
- ✅ ISR (regeneração estática)
- ✅ Font otimizado
- ✅ Gzip/Brotli compression

## 📚 Stack

- **Framework:** Next.js 14 (App Router)
- **Language:** TypeScript 5
- **Styling:** Tailwind CSS 4
- **UI Components:** Headless UI
- **Forms:** React Hook Form + Zod
- **State:** Zustand + Context
- **i18n:** next-intl
- **Testing:** Jest + React Testing Library
- **Icons:** Lucide React
- **Editor:** GrapesJS (admin)

## 🔐 Segurança

- ✅ HTTPS only
- ✅ CSP headers
- ✅ XSS prevention
- ✅ CSRF token
- ✅ Input validation (Zod)
- ✅ Sanitização HTML (backend)
- ✅ Rate limiting (backend)

## 🚨 Troubleshooting

**API não conecta:**
```
Verificar: NEXT_PUBLIC_API_URL no .env.local
```

**Build falha:**
```
npm ci && npm run build
```

**Testes falham:**
```
npm install
jest --clearCache
npm run test
```

Veja [Documentação Técnica](../docs/DOCUMENTACAO_TECNICA_COMPLETA.md) para mais.

## 📝 Licença

MIT License

## 👨‍💻 Desenvolvimento

- **Version:** 1.0.0
- **Status:** ✅ Produção
- **Last Updated:** 2026-02-24

---

**Próximos passos:** Deploy → Monitoramento → Feedback
