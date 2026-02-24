# 🚀 Próximos Passos - Fase 3 (Mídia API + Editor GrapesJS)

## ✅ Fases Concluídas

### Fase 0: Fundação ✅
- ✅ Backend PHP rodando em `localhost:8000`
- ✅ Frontend Next.js rodando em `localhost:3000`
- ✅ PostgreSQL conectando em `localhost:5432`
- ✅ Redis conectando em `localhost:6379`
- ✅ CORS funcional
- ✅ Autenticação com Sanctum

### Fase 1: Core API Backend ✅
- ✅ CRUD Posts, Pages, Categories, Authors, Menus
- ✅ Paginação e filtros em todos endpoints
- ✅ API Resources estruturadas
- ✅ Agendamento de Posts
- ✅ Validações com FormRequest

### Fase 2: Multilíngue + SEO Engine ✅
- ✅ CRUD de Idiomas
- ✅ Suporte a traduções de Posts
- ✅ SeoService com hreflang, canonical, OpenGraph
- ✅ SchemaService com JSON-LD (Article, Breadcrumb, Author, Organization)
- ✅ SitemapService gerando sitemaps.xml por idioma
- ✅ Endpoints /sitemap.xml e /robots.txt
- ✅ API de Configurações Globais SEO

---

## 🎯 Fase 3: Mídia API + Editor GrapesJS (Next.js Admin)

### Visão Geral
Implementar sistema completo de mídia com upload de imagens, conversão automática para WebP/AVIF, e integração com o editor GrapesJS para o painel admin.

### Duração Estimada
2 semanas

### Tarefas

#### 1️⃣ **F3.1 - Upload de Mídia (API PHP)** (~3 dias)
```php
// Criar em backend/src/Controllers/
- MediaController.php
  ├── index()   - Listar mídias com paginação
  ├── show()    - Obter mídia
  ├── store()   - Upload de novo arquivo
  └── destroy() - Deletar mídia
```

**Endpoints:**
```
GET    /api/v1/media                    - Listar mídias (pagina, limit, tipo)
GET    /api/v1/media/:id                - Obter mídia
POST   /api/v1/media                    - Upload de mídia
DELETE /api/v1/media/:id                - Deletar mídia
```

**Requisitos:**
- Validação de MIME types (image/jpeg, image/png, image/webp, image/gif, video/mp4)
- Tamanho máximo: 10MB para imagens, 100MB para vídeos
- Storage local em `/storage/uploads/` com estrutura por mês
- Retornar: `{id, url, webp_url, avif_url, mime_type, size, created_at}`

**Checklist:**
- [ ] Validação de arquivo MIME real (não apenas extensão)
- [ ] Criar pasta de upload se não existir
- [ ] Gerar nome único (uuid + ext)
- [ ] Retornar MediaResource
- [ ] Testar upload de múltiplos formatos
- [ ] Testar tamanho máximo

**Validação:**
```bash
# Upload de imagem
curl -X POST http://localhost:8000/api/v1/media \
  -F "file=@/tmp/image.jpg" \
  -H "Accept: application/json"

# Deve retornar
{
  "data": {
    "id": 1,
    "url": "/storage/uploads/2026/02/uuid.jpg",
    "webp_url": "/storage/uploads/2026/02/uuid.webp",
    "mime_type": "image/jpeg",
    "size": 2048000
  }
}
```

---

#### 2️⃣ **F3.2 - Conversão WebP/AVIF (Job)** (~3 dias)
```php
// Criar em backend/src/Jobs/
- ConvertMediaFormatsJob.php
  - Disparado após upload
  - Processa conversão WebP
  - Processa conversão AVIF
  - Atualiza caminhos na DB
```

**Requisitos:**
- Usar `spatie/image` ou `intervention/image`
- Processar em background (queue)
- Atualizar campos `webp_path` e `avif_path` após conclusão
- Manter original + WebP + AVIF

**Checklist:**
- [ ] Instalar dependência de processamento de imagem
- [ ] Implementar job ConvertMediaFormatsJob
- [ ] Disparar job após upload (observer ou evento)
- [ ] Atualizar model Media com campos webp_path, avif_path
- [ ] Testar conversão
- [ ] Testar fila de jobs

**Validação:**
```bash
# Upload de imagem
curl -X POST http://localhost:8000/api/v1/media \
  -F "file=@/tmp/image.jpg"

# Aguardar 5 segundos (processamento)
sleep 5

# Verificar
curl http://localhost:8000/api/v1/media/1
# Deve retornar webp_url e avif_url preenchidos
```

---

#### 3️⃣ **F3.3 - Alt Text Multilíngue (API)** (~1 dia)
```php
// Endpoints para alt text por idioma
```

**Endpoints:**
```
PATCH  /api/v1/media/:id/alt-text       - Atualizar alt text por idioma
```

**Requisitos:**
- Campo `alt_text` como JSON: `{"pt": "Descrição", "en": "Description"}`
- Suportar idiomas definidos no sistema
- Validar idiomas existentes

**Checklist:**
- [ ] Adicionar campo alt_text JSON
- [ ] Criar FormRequest de validação
- [ ] Testar atualização por idioma
- [ ] Retornar alt_text estruturado

**Validação:**
```bash
curl -X PATCH http://localhost:8000/api/v1/media/1/alt-text \
  -H "Content-Type: application/json" \
  -d '{"pt":"Imagem de teste","en":"Test image"}'
```

---

#### 4️⃣ **F3.4 - Componente GrapesJS (Next.js)** (~4 dias)
```tsx
// Criar em frontend/src/components/admin/
- GrapesJSEditor.tsx
  - 'use client' component
  - Editor WYSIWYG
  - Drag-and-drop de blocos
  - Salva body + grapesjs_data
```

**Requisitos:**
- Instalação: `npm install grapesjs`
- Props: `initialContent`, `onChange`, `onSave`
- Salvar dois campos: `body` (HTML sanitizado) e `grapesjs_data` (JSON)
- Plugins: predefinidos, grid, tipografia

**Checklist:**
- [ ] Instalar grapesjs e plugins
- [ ] Criar componente com inicialização
- [ ] Implementar eventos onChange/onSave
- [ ] Testar drag-and-drop
- [ ] Integrar com MediaPicker (próxima tarefa)
- [ ] Validar saída HTML

**Validação:**
```bash
# No admin, criar novo post
# Drag-and-drop blocos no GrapesJS
# Salvar post
# Verificar: POST /api/v1/posts/1 contém body + grapesjs_data
```

---

#### 5️⃣ **F3.5 - Sanitização no Save (PHP)** (~1 dia)
```php
// Em backend/src/Controllers/PostController.php
- Aplicar HTMLPurifier ao salvar body
```

**Requisitos:**
- Instalar: `composer require ezyang/htmlpurifier`
- Sanitizar antes de persistir no DB
- Remover scripts, eventos inline, estilos perigosos
- Manter tags seguras: p, div, h1-h6, img, a, ul, ol, li, etc

**Checklist:**
- [ ] Instalar HTMLPurifier
- [ ] Criar service SanitizationService
- [ ] Aplicar em PostController@store e @update
- [ ] Testar remoção de <script>
- [ ] Testar preservação de <img>, <a>, <p>

**Validação:**
```bash
# Tentar salvar com script
curl -X POST http://localhost:8000/api/v1/posts \
  -H "Content-Type: application/json" \
  -d '{"title":"Test","body":"<p>Safe</p><script>alert(1)</script>"}'

# Recuperar post e verificar que script foi removido
curl http://localhost:8000/api/v1/posts/1
# Deve conter apenas: <p>Safe</p>
```

---

#### 6️⃣ **F3.6 - MediaPicker Modal (Next.js)** (~2 dias)
```tsx
// Criar em frontend/src/components/admin/
- MediaPicker.tsx
  - Grid de mídias
  - Busca por nome/tag
  - Upload via drag-and-drop
  - Seleção e inserção no GrapesJS
  - Integração com GrapesJSEditor
```

**Requisitos:**
- Modal com grid de mídias (3 colunas)
- Pesquisa em tempo real
- Upload de arquivo
- Preview de imagem
- Botão "Inserir" para adicionar ao GrapesJS

**Checklist:**
- [ ] Criar componente MediaPicker
- [ ] Conectar com API /api/v1/media
- [ ] Implementar upload
- [ ] Implementar busca
- [ ] Integrar com GrapesJS (inserir imagem)
- [ ] Testar flow completo

---

## 📋 Checklist de Validação - Fase 3

### Backend
- [ ] Upload de mídia funcionando
- [ ] Conversão WebP/AVIF funcionando
- [ ] Alt text multilíngue funcionando
- [ ] Validação de MIME types
- [ ] Sanitização HTML funcionando
- [ ] Erros retornando estruturados

### Frontend
- [ ] GrapesJS renderizando corretamente
- [ ] Drag-and-drop funcionando
- [ ] MediaPicker modal aparecendo
- [ ] Upload de mídia via MediaPicker
- [ ] Inserção de imagem no editor
- [ ] Salvamento do conteúdo

### Integração
- [ ] Upload via MediaPicker → API
- [ ] Conversão acontecendo em background
- [ ] Imagem inserida no GrapesJS com URL correta
- [ ] Salvamento do post com body + grapesjs_data
- [ ] Recuperação do conteúdo preservando estrutura

---

## 🧪 Como Testar

```bash
# 1. Backend rodando
cd backend && php -S localhost:8000 -t public

# 2. Frontend rodando
cd frontend && npm run dev

# 3. Testar upload via curl
curl -X POST http://localhost:8000/api/v1/media \
  -F "file=@/caminho/para/imagem.jpg"

# 4. No admin (localhost:3000/admin)
# Criar novo post
# Usar GrapesJS para editar
# Abrir MediaPicker
# Fazer upload de imagem
# Inserir no editor
# Salvar post

# 5. Verificar post criado
curl http://localhost:8000/api/v1/posts/1
```

---

## 📝 Dependências Necessárias

### Backend
```bash
# Processamento de imagem
composer require intervention/image

# Sanitização HTML
composer require ezyang/htmlpurifier

# Validação de MIME type (se não incluído)
composer require fideloper/proxy
```

### Frontend
```bash
# Editor GrapesJS
npm install grapesjs grapesjs-preset-webpage

# Upload de arquivo
# (já incluído no Next.js)
```

---

## 📝 Estrutura de Arquivos

```
backend/src/
├── Controllers/
│   ├── MediaController.php         [F3.1]
│   └── PostController.php          [modificado - F3.5]
├── Resources/
│   └── MediaResource.php           [F3.1]
├── Requests/
│   ├── StoreMediaRequest.php       [F3.1]
│   └── UpdateAltTextRequest.php    [F3.3]
├── Jobs/
│   └── ConvertMediaFormatsJob.php  [F3.2]
├── Services/
│   └── MediaService.php            [F3.1]
└── Models/
    └── Media.php                   [novo]

frontend/src/components/admin/
├── GrapesJSEditor.tsx              [F3.4]
└── MediaPicker.tsx                 [F3.6]
```

---

## ⚠️ Dicas Importantes

1. **Conversão de Imagens:** Use jobs/queues para não bloquear request
2. **Armazenamento:** Usar `/storage/uploads/` local. Preparar para S3 depois
3. **Segurança:** Validar MIME types real, não confiar em extensão
4. **Sanitização:** Ser rigoroso com HTMLPurifier para evitar XSS
5. **Performance:** Lazy-load imagens no GrapesJS com `<picture>` e `sizes`
6. **SEO:** Manter alt text para todas imagens

---

## 🎯 Quando Fase 3 Está Completa?

Todos os critérios devem ser atendidos:
- ✅ Upload de mídia funcionando
- ✅ Conversão automática WebP/AVIF
- ✅ Editor GrapesJS no admin
- ✅ MediaPicker integrado
- ✅ Sanitização HTML segura
- ✅ Alt text multilíngue
- ✅ Testes cobrindo cenários principais
- ✅ Documentação atualizada

---

**Iniciar:** Leia este documento completamente antes de começar  
**Referência:** Veja [PLANO_DE_EXECUCAO.md](../../4-PLANO_DE_EXECUCAO.md)  
**Status:** Consulte [Fases_Implementadas.md](../../Fases_Implementadas.md)
