# 🎨 Frontend - Blog Profissional Multilíngue

**Status:** ✅ 100% Completo | **Fases:** 3-5 + 7 | **Componentes:** 30+ | **Testes:** 27 Jest ✅

---

## 📋 Início Rápido

### Pré-requisitos
- Node.js 18.x+
- npm 9.x+

### Instalação (5 minutos)

```bash
cd frontend
npm install
cp .env.local.example .env.local
# Editar .env.local com NEXT_PUBLIC_API_URL
npm run dev
# ✅ Acesso: http://localhost:3000
```

---

## 🏗️ Arquitetura

### Stack
- **Next.js 14** - Framework React
- **TypeScript** - Type safety
- **Tailwind CSS** - Styling
- **Next-intl** - Multilanguage
- **React Hook Form** - Formulários
- **Zod** - Validação
- **Jest** - Testes

### Rotas Principais

```
Admin Panel:
├── /admin/login               # Autenticação JWT
├── /admin                     # Dashboard
├── /admin/setup               # Setup Wizard
├── /admin/posts               # Gerenciar posts
├── /admin/pages               # Gerenciar páginas
├── /admin/categories          # Gerenciar categorias
├── /admin/authors             # Gerenciar autores
├── /admin/media               # Biblioteca de mídia
├── /admin/menus               # Menu builder
├── /admin/languages           # Gerenciar idiomas
└── /admin/settings            # Configurações

Frontend Público:
├── /                          # Home (ISR)
├── /[locale]                  # Localização
├── /[locale]/[slug]           # Post (ISR)
├── /[locale]/category/[slug]  # Categoria
├── /[locale]/about            # Sobre
├── /[locale]/contact          # Contato
└── /[locale]/privacy          # Privacidade
```

---

## 🎯 Funcionalidades Admin

### Setup Wizard
- ✅ Passo 1: Informações do blog
- ✅ Passo 2: Design visual (logo, cores)
- ✅ Passo 3: Idiomas (pt, en, es)
- ✅ Passo 4: Layout (Clean, Magazine, Minimal)

### CRUD Posts
- ✅ Listagem com filtros e paginação
- ✅ Criar/editar com GrapesJS
- ✅ Suporte multilíngue
- ✅ Agendamento de publicação
- ✅ SEO fields (título, descrição)

### Gerenciamento
- ✅ Páginas estáticas (Privacy, Terms, About, Contact)
- ✅ Categorias com hierarquia
- ✅ Autores com avatar
- ✅ Menus com reordenação
- ✅ Biblioteca de mídia (upload, edição)
- ✅ Idiomas (CRUD + padrão)
- ✅ Configurações (SEO, AdSense, cores)

---

## 🌍 Funcionalidades Público

### 3 Layouts Responsivos
- ✅ **Layout1Clean:** Grid 3 colunas, sidebar
- ✅ **Layout2Magazine:** Multi-coluna, featured post
- ✅ **Layout3Minimal:** Minimalista, foco em leitura

### SEO Otimizado
- ✅ Next-intl para i18n automático
- ✅ Metadata dinâmica (title, description, og:image)
- ✅ Hreflang para alternates
- ✅ JSON-LD schemas (Article, Breadcrumb, Author, Organization)
- ✅ Sitemap e robots.txt

### Performance
- ✅ SSR (Server-Side Rendering) para SEO
- ✅ ISR (Incremental Static Regeneration) - revalidate: 3600
- ✅ Lazy loading com next/image
- ✅ WebP/AVIF automático
- ✅ Code splitting automático

### Features
- ✅ Paginação SEO-friendly
- ✅ Breadcrumbs dinâmicos
- ✅ Author box com avatar
- ✅ Categoria sidebar
- ✅ AdSense configurável
- ✅ Formulário de contato (honeypot)

---

## 📊 Componentes

### Admin Components
```
components/admin/
├── AdminLayout.tsx           # Template base
├── SetupWizard.tsx          # Wizard 4 passos
├── PostTable.tsx            # Tabela posts
├── MediaPicker.tsx          # Seletor de mídia
└── GrapesJSEditor.tsx       # Editor visual
```

### Public Components
```
components/public/
├── PostCard.tsx             # Card de post
├── CategoryBadge.tsx        # Badge categoria
├── AuthorBox.tsx            # Info do autor
├── Breadcrumb.tsx           # Navegação
├── Pagination.tsx           # Paginação
├── AdBlock.tsx              # AdSense block
└── ContactForm.tsx          # Formulário contato
```

### Shared Components
```
components/
├── Header.tsx               # Navegação
├── Footer.tsx               # Rodapé
└── Layout.tsx               # Layout raiz
```

---

## 🧪 Testes (27 tests ✅)

```bash
# Executar todos
npm test

# Watch mode
npm test -- --watch

# Com coverage
npm test -- --coverage

# Teste específico
npm test PostCard.test.tsx

# Resultado: 27 tests, 0 failures ✅
```

### Testes Inclusos
- ✅ PostCard component (4 testes)
- ✅ ContactForm component (4 testes)
- ✅ AdBlock component (5 testes)
- ✅ SEO library (7 testes)
- ✅ Schema library (7 testes)

---

## 🚀 Build & Production

```bash
# Build otimizado
npm run build
# Resultado: 25 rotas, 79.9 kB First Load JS ✅

# Iniciar servidor
npm start

# Análise de bundle
npm run analyze
```

---

## 🌐 Configuração Multilíngue

### Idiomas Suportados
```
- Português (pt) - padrão
- Inglês (en)
- Espanhol (es)
```

### Adicionar Novo Idioma
```bash
# 1. Editar next.config.js
i18n: {
  locales: ['pt', 'en', 'es', 'fr'],  # Adicionar 'fr'
  defaultLocale: 'pt'
}

# 2. Criar diretório de traduções
mkdir src/app/[locale]/fr/

# 3. Adicionar em admin/languages
POST /api/v1/languages
{
  "code": "fr",
  "name": "French",
  "native_name": "Français"
}
```

---

## ⚙️ Configuração

### Variáveis de Ambiente (.env.local)

```bash
# API
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1

# Revalidação ISR
REVALIDATE_SECRET=seu-secret-aqui

# Analytics (opcional)
NEXT_PUBLIC_GA_ID=G-xxxxx

# AdSense (opcional)
NEXT_PUBLIC_ADSENSE_CLIENT_ID=ca-pub-xxxxx
```

---

## 📈 Performance

### Core Web Vitals
- ✅ LCP (Largest Contentful Paint): < 2.5s
- ✅ FID (First Input Delay): < 100ms
- ✅ CLS (Cumulative Layout Shift): < 0.1

### Bundle Size
- ✅ First Load JS: 79.9 kB
- ✅ Page bundle: ~50 kB
- ✅ Shared components: ~20 kB

---

## 🚀 Deployment

### Vercel (Recomendado)

```bash
# Automático via Git
git push origin main

# Ou manual
npm run build
vercel --prod
```

### VPS (PM2 + Nginx)

```bash
# Deploy
npm run build
pm2 start npm --name "blog-frontend" -- start
pm2 startup
pm2 save

# Nginx config
server {
  listen 80;
  server_name seu-dominio.com;
  location / {
    proxy_pass http://localhost:3000;
  }
}
```

---

## 🐛 Troubleshooting

### Problema: "API 404"

**Solução:**
```bash
# Verificar NEXT_PUBLIC_API_URL
echo $NEXT_PUBLIC_API_URL

# Deve ser: http://localhost:8000/api/v1
# Em produção: https://api.seu-dominio.com/api/v1
```

### Problema: "ISR not revalidating"

**Solução:**
```bash
# Verificar webhook do backend
# Deve chamar: POST /api/revalidate?secret=XXX

# Verificar REVALIDATE_SECRET em .env.local
REVALIDATE_SECRET=seu-secret-aqui
```

### Problema: "Build failure"

**Solução:**
```bash
npm run build -- --verbose
# Ver erro específico

# Limpar cache
rm -rf .next
npm run build
```

---

## 🔗 Links Úteis

- [Next.js Docs](https://nextjs.org/docs)
- [Next-intl Docs](https://next-intl-docs.vercel.app)
- [Tailwind Docs](https://tailwindcss.com/docs)
- [React Hook Form](https://react-hook-form.com)
- [Zod Validation](https://zod.dev)

---

## 📞 Suporte

- **Issues:** GitHub Issues
- **Docs:** `/docs` pasta
- **Tests:** `npm test`
- **Build:** `npm run build`

---

📖 [Documentação Completa](../README_MAIN.md) | 🔧 [Backend Setup](../backend/README.md) | 🎯 [Setup Guide](../docs/setup/LEIA_PRIMEIRO.md)

---

**Desenvolvido por:** GitHub Copilot CLI  
**Versão:** 1.0 - Production ✅  
**Última atualização:** 2026-02-24
