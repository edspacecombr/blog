================================================================================
FASE 4 - PAINEL ADMIN EM NEXT.JS - INÍCIO DE DESENVOLVIMENTO
================================================================================

Data:        2026-02-24
Versão:      0.1 - INICIAL
Status:      🚀 INICIADA

Estimado:    3 semanas
Deadline:    2026-03-17

================================================================================
RESUMO EXECUTIVO FASE 4
================================================================================

A Fase 4 foca na construção do painel administrativo completo em Next.js.

OBJETIVO PRINCIPAL:
Criar um dashboard administrativo funcional com:
  ✅ Setup Wizard para configuração inicial
  ✅ Gestão de Posts com editor GrapesJS
  ✅ Gestão de Páginas Estáticas
  ✅ Gestão de Categorias
  ✅ Gestão de Autores
  ✅ Gestão de Menus com drag-and-drop
  ✅ Biblioteca de Mídia
  ✅ Configurações Globais
  ✅ Gestão de Idiomas

CRITÉRIO DE CONCLUSÃO:
Todas as telas do admin funcionando com CRUD completo e integração com API.

================================================================================
SUBTAREFAS PLANEJADAS (10 TAREFAS)
================================================================================

F4.1 - SETUP WIZARD 5 PASSOS (Next.js)
──────────────────────────────────────
Status: ❌ Não Iniciado
Estimado: 2-3 dias
Prioridade: ⭐⭐⭐ CRÍTICA

Descrição:
  Wizard interativo de 5 passos para configuração inicial do blog.

Passos:
  1. Informações do Blog (nome, URL, descrição)
  2. Identidade Visual (logo, favicon, cores primárias)
  3. Idiomas (selecionado padrão, ativar adicionais)
  4. Nicho e Layout (categoria principal, layout padrão)
  5. Confirmação e Setup Completo (chama POST /api/v1/setup/complete)

Arquivos a Criar:
  ✅ frontend/src/pages/admin/setup/index.tsx
  ✅ frontend/src/components/admin/SetupWizard.tsx
  ✅ frontend/src/hooks/useSetupWizard.ts

Dependências:
  - Backend: API /api/v1/setup/complete
  - PostController sanitizado (F3.5) ✅

Testes:
  - [ ] Página carrega corretamente
  - [ ] Passos navegáveis
  - [ ] Validação de formulário
  - [ ] API chamada ao final

─────────────────────────────────────

F4.2 - LISTAGEM E CRUD DE POSTS (ADMIN)
───────────────────────────────────────
Status: ❌ Não Iniciado
Estimado: 4-5 dias
Prioridade: ⭐⭐⭐ CRÍTICA

Descrição:
  Telas administrativas para criar, editar, listar e deletar posts.

Funcionalidades:
  ✅ Listagem em tabela com filtros
    - Filtrar por status (draft, published, scheduled)
    - Filtrar por idioma
    - Filtrar por categoria
    - Buscar por título
    - Ordenar por data/título

  ✅ Criação de Post (/admin/posts/new)
    - Título, slug, excerpt
    - Editor GrapesJS para conteúdo
    - Seleção de categoria
    - Seleção de autor
    - Upload de featured image via MediaPicker
    - Abas por idioma
    - Preview em tempo real

  ✅ Edição de Post (/admin/posts/[id]/edit)
    - Edição multilíngue
    - Versionamento automático
    - Agendamento de publicação
    - Histórico de alterações

  ✅ Deleção
    - Confirmação com aviso
    - Remoção de mídias associadas

Arquivos a Criar:
  ✅ frontend/src/pages/admin/posts/index.tsx
  ✅ frontend/src/pages/admin/posts/new.tsx
  ✅ frontend/src/pages/admin/posts/[id]/edit.tsx
  ✅ frontend/src/components/admin/PostForm.tsx
  ✅ frontend/src/components/admin/PostTable.tsx
  ✅ frontend/src/components/admin/PostFilters.tsx

Dependências:
  - GrapesJS (F3.4) ✅
  - MediaPicker (F3.6) ✅
  - API /api/v1/posts ✅

Testes:
  - [ ] Listar posts funciona
  - [ ] Filtros funcionam
  - [ ] Criar novo post funciona
  - [ ] Editar post funciona
  - [ ] GrapesJS salva conteúdo
  - [ ] Deletar post funciona

─────────────────────────────────────

F4.3 - CRUD PÁGINAS ESTÁTICAS (ADMIN)
──────────────────────────────────────
Status: ❌ Não Iniciado
Estimado: 2 dias
Prioridade: ⭐⭐ MÉDIA

Descrição:
  Similar ao F4.2 mas para páginas estáticas (privacy, terms, about, contact).

Funcionalidades:
  ✅ Listagem de páginas
  ✅ Indicador de tipo de página
  ✅ Edição com editor GrapesJS
  ✅ Proteção de páginas do sistema

Arquivos a Criar:
  ✅ frontend/src/pages/admin/pages/index.tsx
  ✅ frontend/src/pages/admin/pages/[id]/edit.tsx
  ✅ frontend/src/components/admin/PageForm.tsx
  ✅ frontend/src/components/admin/PageTable.tsx

Dependências:
  - API /api/v1/pages ✅
  - GrapesJS (F3.4) ✅

─────────────────────────────────────

F4.4 - CRUD CATEGORIAS (ADMIN)
──────────────────────────────
Status: ❌ Não Iniciado
Estimado: 1-2 dias
Prioridade: ⭐⭐ MÉDIA

Descrição:
  Gestão de categorias com suporte a hierarquia.

Funcionalidades:
  ✅ Listagem em formato árvore
  ✅ Criação de categoria raiz e subcategorias
  ✅ Edição com campos translatable por idioma
  ✅ Deleção com validação
  ✅ Reordenação via drag-and-drop

Campos Translatable:
  - name
  - slug
  - description

Arquivos a Criar:
  ✅ frontend/src/pages/admin/categories/index.tsx
  ✅ frontend/src/components/admin/CategoryForm.tsx
  ✅ frontend/src/components/admin/CategoryTree.tsx

Dependências:
  - API /api/v1/categories ✅

─────────────────────────────────────

F4.5 - GESTÃO DE AUTORES (ADMIN)
────────────────────────────────
Status: ❌ Não Iniciado
Estimado: 1-2 dias
Prioridade: ⭐ BAIXA

Descrição:
  Gestão de perfis de autores.

Funcionalidades:
  ✅ Listagem de autores
  ✅ Criação de novo autor
  ✅ Edição de perfil
  ✅ Upload de avatar via MediaPicker
  ✅ Bio multilíngue
  ✅ Links sociais

Campos:
  - name
  - email
  - bio (multilíngue JSON)
  - avatar_url
  - social_links (JSON)

Arquivos a Criar:
  ✅ frontend/src/pages/admin/authors/index.tsx
  ✅ frontend/src/components/admin/AuthorForm.tsx
  ✅ frontend/src/components/admin/AuthorTable.tsx

Dependências:
  - API /api/v1/authors ✅
  - MediaPicker (F3.6) ✅

─────────────────────────────────────

F4.6 - GESTÃO DE MENUS (ADMIN)
──────────────────────────────
Status: ❌ Não Iniciado
Estimado: 2-3 dias
Prioridade: ⭐⭐ MÉDIA

Descrição:
  Criação e gerenciamento de menus com suporte a drag-and-drop.

Funcionalidades:
  ✅ Listagem de menus
  ✅ Criação de novo menu
  ✅ Edição de menu items
  ✅ Drag-and-drop para reordenação
  ✅ Vinculação polimórfica (post, page, url)
  ✅ Nesting de itens (submenus)

Biblioteca Recomendada:
  @hello-pangea/dnd (melhor que react-dnd para Next.js)
  OU dnd-kit (alternativa)

Arquivos a Criar:
  ✅ frontend/src/pages/admin/menus/index.tsx
  ✅ frontend/src/components/admin/MenuBuilder.tsx
  ✅ frontend/src/components/admin/MenuItemForm.tsx

Dependências:
  - API /api/v1/menus ✅
  - Biblioteca drag-and-drop

─────────────────────────────────────

F4.7 - BIBLIOTECA DE MÍDIA (ADMIN)
──────────────────────────────────
Status: ❌ Não Iniciado
Estimado: 2 dias
Prioridade: ⭐⭐ MÉDIA

Descrição:
  Interface para gerenciar todas as mídias enviadas.

Funcionalidades:
  ✅ Grid de mídias com pré-visualização
  ✅ Upload direto de novos arquivos
  ✅ Busca por nome/tag
  ✅ Filtro por tipo (imagem/vídeo)
  ✅ Edição de alt text por idioma
  ✅ Deleção com confirmação
  ✅ Informações: tamanho, data, tipo MIME

Arquivos a Criar:
  ✅ frontend/src/pages/admin/media/index.tsx
  ✅ frontend/src/components/admin/MediaLibrary.tsx
  ✅ frontend/src/components/admin/MediaUpload.tsx

Dependências:
  - API /api/v1/media ✅
  - MediaPicker (F3.6) ✅

─────────────────────────────────────

F4.8 - CONFIGURAÇÕES GERAIS (ADMIN)
────────────────────────────────────
Status: ❌ Não Iniciado
Estimado: 2-3 dias
Prioridade: ⭐⭐ MÉDIA

Descrição:
  Painel de configurações globais do blog.

Grupos de Configuração:

  1. GERAL
     - Nome do blog
     - URL do site
     - Descrição
     - Email de contato

  2. SEO
     - Title pattern
     - Meta description pattern
     - Default og:image
     - Robots.txt content

  3. APARÊNCIA
     - Layout ativo (Layout1Clean, Layout2Magazine, Layout3Minimal)
     - Cores primárias/secundárias
     - Logo URL
     - Favicon URL

  4. MONETIZAÇÃO
     - AdSense ID por idioma
     - Posições de anúncios
     - Ativado/Desativado por idioma

Arquivos a Criar:
  ✅ frontend/src/pages/admin/settings/index.tsx
  ✅ frontend/src/components/admin/SettingsForm.tsx
  ✅ frontend/src/components/admin/SettingsTabs.tsx

Dependências:
  - API /api/v1/settings ✅

─────────────────────────────────────

F4.9 - GESTÃO DE IDIOMAS (ADMIN)
────────────────────────────────
Status: ❌ Não Iniciado
Estimado: 1 dia
Prioridade: ⭐ BAIXA

Descrição:
  Interface para gerenciar idiomas do blog.

Funcionalidades:
  ✅ Listagem de idiomas
  ✅ Criação de novo idioma
  ✅ Definição de idioma padrão
  ✅ Ativar/Desativar idiomas
  ✅ Deleção com validação

Campos:
  - code (ex: 'pt', 'en', 'es')
  - name (ex: 'Português', 'English')
  - native_name
  - is_default
  - is_active

Arquivos a Criar:
  ✅ frontend/src/pages/admin/languages/index.tsx
  ✅ frontend/src/components/admin/LanguageForm.tsx

Dependências:
  - API /api/v1/languages ✅

─────────────────────────────────────

F4.10 - INTEGRAÇÃO COMPLETA E TESTES
────────────────────────────────────
Status: ❌ Não Iniciado
Estimado: 2-3 dias
Prioridade: ⭐⭐⭐ CRÍTICA

Descrição:
  Validação, testes e ajustes finais do painel admin.

Testes:
  ✅ Fluxo completo Setup Wizard
  ✅ CRUD Posts com GrapesJS
  ✅ CRUD Páginas
  ✅ CRUD Categorias
  ✅ CRUD Autores
  ✅ CRUD Menus com DND
  ✅ Gestão de Mídia
  ✅ Configurações salvando corretamente
  ✅ Idiomas sendo respeitados

Validações:
  ✅ Todas URLs corretas
  ✅ Permissões de auth funcionando
  ✅ Validação de formulários
  ✅ Mensagens de erro claras
  ✅ Feedback de sucesso

================================================================================
DEPENDÊNCIAS EXTERNAS
================================================================================

GRAPESJS JÁ INSTALADO:
  ✅ grapesjs@^0.21
  ✅ grapesjs-preset-webpage@^1.0

PRECISA INSTALAR (próximo):
  - @hello-pangea/dnd (para drag-and-drop de menus)
  - react-hook-form (para formulários complexos)
  - zod (para validação de schemas)
  - react-toastify (para notificações)
  - date-fns (para manipulação de datas)

BACKEND JÁ PRONTO:
  ✅ Todas APIs de CRUD implementadas
  ✅ Sanitização HTML ativa
  ✅ Upload de mídia funcional
  ✅ Autenticação Sanctum ✅

================================================================================
ARQUITETURA DO PAINEL ADMIN
================================================================================

Estrutura de Pastas:
```
frontend/src/
├── pages/
│   ├── admin/
│   │   ├── index.tsx                 (Dashboard)
│   │   ├── setup/
│   │   │   └── index.tsx            (Setup Wizard) - F4.1
│   │   ├── posts/
│   │   │   ├── index.tsx            (List Posts) - F4.2
│   │   │   ├── new.tsx              (New Post) - F4.2
│   │   │   └── [id]/edit.tsx        (Edit Post) - F4.2
│   │   ├── pages/
│   │   │   ├── index.tsx            (List Pages) - F4.3
│   │   │   └── [id]/edit.tsx        (Edit Page) - F4.3
│   │   ├── categories/
│   │   │   └── index.tsx            (Manage Categories) - F4.4
│   │   ├── authors/
│   │   │   └── index.tsx            (Manage Authors) - F4.5
│   │   ├── menus/
│   │   │   └── index.tsx            (Menu Builder) - F4.6
│   │   ├── media/
│   │   │   └── index.tsx            (Media Library) - F4.7
│   │   ├── settings/
│   │   │   └── index.tsx            (Settings) - F4.8
│   │   ├── languages/
│   │   │   └── index.tsx            (Languages) - F4.9
│   │   └── login.tsx                (Auth - from F0.7)
│   └── admin-test.tsx               (Testing page)
├── components/
│   ├── admin/
│   │   ├── SetupWizard.tsx          - F4.1
│   │   ├── PostForm.tsx             - F4.2
│   │   ├── PostTable.tsx            - F4.2
│   │   ├── PostFilters.tsx          - F4.2
│   │   ├── PageForm.tsx             - F4.3
│   │   ├── PageTable.tsx            - F4.3
│   │   ├── CategoryForm.tsx         - F4.4
│   │   ├── CategoryTree.tsx         - F4.4
│   │   ├── AuthorForm.tsx           - F4.5
│   │   ├── AuthorTable.tsx          - F4.5
│   │   ├── MenuBuilder.tsx          - F4.6
│   │   ├── MenuItemForm.tsx         - F4.6
│   │   ├── MediaLibrary.tsx         - F4.7
│   │   ├── MediaUpload.tsx          - F4.7
│   │   ├── SettingsForm.tsx         - F4.8
│   │   ├── SettingsTabs.tsx         - F4.8
│   │   ├── LanguageForm.tsx         - F4.9
│   │   ├── GrapesJSEditor.tsx       ✅ (F3.4)
│   │   └── MediaPicker.tsx          ✅ (F3.6)
│   └── layouts/
│       └── AdminLayout.tsx          (Layout principal)
├── hooks/
│   ├── useSetupWizard.ts           - F4.1
│   ├── usePostForm.ts              - F4.2
│   ├── useSettings.ts              - F4.8
│   └── ...
└── lib/
    ├── api.ts                      ✅ (existente)
    ├── validators.ts               (schemas Zod)
    └── ...
```

================================================================================
TIMELINE ESTIMADA
================================================================================

SEMANA 1:
  ├─ Dia 1-2: F4.1 Setup Wizard (CRÍTICA)
  ├─ Dia 2-3: F4.2 CRUD Posts (CRÍTICA)
  └─ Dia 3: Testes integração

SEMANA 2:
  ├─ Dia 1-2: F4.3 CRUD Páginas + F4.4 CRUD Categorias
  ├─ Dia 2-3: F4.5 Autores + F4.6 Menus (DND)
  └─ Dia 3-4: F4.7 Biblioteca Mídia + F4.8 Configurações

SEMANA 3:
  ├─ Dia 1: F4.9 Gestão de Idiomas
  ├─ Dia 2-3: Testes e fixes
  ├─ Dia 3-4: Validação final e ajustes
  └─ Dia 4: Documentação e release

Total: ~15-18 dias (2.5 semanas)

================================================================================
CRITÉRIO DE CONCLUSÃO FASE 4
================================================================================

✅ Setup Wizard funciona e salva configurações
✅ Listagem de posts com filtros funcionando
✅ Criação de posts com GrapesJS funcionando
✅ Edição de posts salvando no banco
✅ Deleção de posts com validação
✅ CRUD de páginas completo
✅ CRUD de categorias com hierarquia
✅ CRUD de autores com avatar
✅ Menu builder com drag-and-drop
✅ Biblioteca de mídia funcional
✅ Configurações globais sendo salvas
✅ Gestão de idiomas funcionando
✅ Todos formulários com validação
✅ Todos endpoints da API testados
✅ Autenticação funcionando em todas páginas
✅ Responsivo em mobile/tablet/desktop

================================================================================
PRÓXIMOS PASSOS IMEDIATOS
================================================================================

1. Instalar dependências frontend (drag-and-drop, form helpers)
   npm install @hello-pangea/dnd react-hook-form zod react-toastify date-fns

2. Criar AdminLayout.tsx (template base para admin)

3. Iniciar F4.1 - Setup Wizard (CRÍTICA)

4. Testar conexão com backend API

5. Criar documentação de UI/UX do admin

================================================================================
REFERÊNCIAS
================================================================================

Plano Completo:  /docs/4-PLANO_DE_EXECUCAO.md (seção Fase 4)
Modelo de Dados: /docs/3-MODELO_DE_DADOS.md
Arquitetura:     .copilot-agents/architecture.md
API Backend:     /docs/api-reference/ (será criado)

Documentação GrapesJS:
  https://grapesjs.com/docs/

Documentação hello-pangea/dnd:
  https://docs.google.com/document/d/1yx3_xBZpS2hOlAK_lrp8wnYnBqtjJrKPHZPbMKzLH-0/

================================================================================
STATUS FINAL
================================================================================

FASE 4: 🚀 INICIADA

Versão:     0.1 - Inicial
Status:     Planejamento Completo ✅
Pronto Para: Começar F4.1 imediatamente

Data Início: 2026-02-24 14:55 UTC
Data Esperada: 2026-03-17 (3 semanas)

================================================================================

Próxima Ação: Começar implementação de F4.1 - Setup Wizard

================================================================================
