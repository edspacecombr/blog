# 🧪 Guia de Teste - Fase 4

## Como Testar o Painel Admin Fase 4

### 1. Iniciar o Ambiente

**Terminal 1 - Backend:**
```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public
```

**Terminal 2 - Frontend:**
```bash
cd /home/edblack/projetos/blog/frontend
npm run dev
```

### 2. Acessar o Admin

Abra no navegador:
```
http://localhost:3000/admin/login
```

**Credenciais de Demo:**
```
Email: admin@blog.com
Password: password123
```

### 3. Testar Cada Funcionalidade

#### ✅ F4.1 - Setup Wizard
1. Acesse: `http://localhost:3000/admin/setup`
2. Preencha todos os 4 passos:
   - Passo 1: Nome, URL, Descrição, Email
   - Passo 2: Cores e Logo
   - Passo 3: Selecionar idiomas
   - Passo 4: Nicho e Layout
3. Clique em "Concluir Setup"

#### ✅ F4.2 - Dashboard
1. Após login, você vai para o dashboard
2. Veja as estatísticas:
   - Total de posts
   - Total de páginas
   - Total de categorias
   - Total de autores
3. Clique nos quick actions

#### ✅ F4.2a - CRUD Posts
1. Acesse: `http://localhost:3000/admin/posts`
2. Teste listagem com filtros:
   - Filtrar por status
   - Filtrar por idioma
   - Buscar por título
3. Clique em "Novo Post":
   - Preencha título, slug, resumo
   - Selecione categoria e autor
   - Escolha idioma
   - Adicione conteúdo no GrapesJS
   - Selecione imagem em destaque
   - Preencha SEO fields
4. Teste edição: clique em "Editar" em um post
5. Teste deleção: clique em "Deletar" com confirmação

#### ✅ F4.3 - Páginas Estáticas
1. Acesse: `http://localhost:3000/admin/pages`
2. Veja as páginas pré-definidas
3. Clique em "Editar" em uma página
4. Teste criar nova página: clique em "Nova Página"
5. Preencha o formulário e salve

#### ✅ F4.4 - Categorias
1. Acesse: `http://localhost:3000/admin/categories`
2. Veja a tabela de categorias
3. Crie uma nova categoria no painel lateral:
   - Nome
   - Slug (auto-gerado)
   - Idioma
4. Teste deleção

#### ✅ F4.5 - Autores
1. Acesse: `http://localhost:3000/admin/authors`
2. Veja grid de autores com avatares
3. Clique em "Novo Autor"
4. Preencha:
   - Nome
   - Email
   - Bio (opcional)
   - Avatar URL (opcional)
5. Teste deleção

#### ✅ F4.6 - Menu Builder
1. Acesse: `http://localhost:3000/admin/menus`
2. Selecione um menu na sidebar esquerda
3. Clique em "Adicionar Item"
4. Preencha:
   - Rótulo (ex: Home)
   - URL (ex: /)
5. Veja os itens listados
6. Teste deleção

#### ✅ F4.7 - Biblioteca de Mídia
1. Acesse: `http://localhost:3000/admin/media`
2. Clique em "Upload"
3. Selecione uma imagem do seu computador
4. Veja a imagem aparecer na grid
5. Hover sobre a imagem e:
   - Clique "Copiar" para copiar URL
   - Clique "🗑️" para deletar

#### ✅ F4.8 - Configurações Globais
1. Acesse: `http://localhost:3000/admin/settings`
2. Teste cada seção:
   - Informações Básicas (nome, URL, descrição, email)
   - Design (logo, cores, layout)
   - SEO e Publicidade (AdSense, robots.txt)
3. Veja o preview na direita
4. Clique em "Salvar Configurações"

#### ✅ F4.9 - Gestão de Idiomas
1. Acesse: `http://localhost:3000/admin/languages`
2. Veja tabela de idiomas com status
3. Teste adicionar novo idioma:
   - Código (2 letras)
   - Nome
   - Nome nativo
4. Clique em "Adicionar"
5. Teste marcar como padrão
6. Teste ativar/desativar

#### ✅ F4.10 - Responsividade
1. Abra DevTools (F12)
2. Ative modo responsivo (Ctrl+Shift+M)
3. Teste em diferentes tamanhos:
   - Mobile (375px)
   - Tablet (768px)
   - Desktop (1024px)

### 4. Testes de Validação

#### Validar Formulários
1. Tente enviar formários vazios
2. Veja as mensagens de erro
3. Teste validações:
   - Email inválido
   - URL inválida
   - Campos obrigatórios

#### Testador Autenticação
1. Tente acessar `/admin` sem login
2. Deve redirecionar para `/admin/login`
3. Logout e tente novamente

### 5. Checklist de Teste

- [ ] Setup Wizard funciona com 4 passos
- [ ] Dashboard carrega estatísticas
- [ ] Posts: Criar, editar, deletar, filtrar
- [ ] Páginas: Criar, editar, deletar
- [ ] Categorias: Criar, editar, deletar
- [ ] Autores: Criar, editar, deletar
- [ ] Menus: Criar e deletar itens
- [ ] Mídia: Upload, copiar URL, deletar
- [ ] Configurações: Salvar alterações
- [ ] Idiomas: Criar, ativar, marcar padrão
- [ ] Responsividade em mobile/tablet
- [ ] Validações de formulário
- [ ] Autenticação JWT funciona
- [ ] Toast notifications aparecem
- [ ] Navegação entre páginas ok

### 6. Problemas Conhecidos e Soluções

**Problema:** "API connection refused"
- Solução: Verifique se o backend está rodando em `localhost:8000`

**Problema:** "Token inválido"
- Solução: Faça login novamente em `/admin/login`

**Problema:** "Build com erros TypeScript"
- Solução: Execute `npm install` novamente no frontend

### 7. URLs de Teste Rápido

```
Dashboard:        http://localhost:3000/admin
Setup Wizard:     http://localhost:3000/admin/setup
Posts:            http://localhost:3000/admin/posts
Novo Post:        http://localhost:3000/admin/posts/new
Páginas:          http://localhost:3000/admin/pages
Categorias:       http://localhost:3000/admin/categories
Autores:          http://localhost:3000/admin/authors
Menus:            http://localhost:3000/admin/menus
Mídia:            http://localhost:3000/admin/media
Configurações:    http://localhost:3000/admin/settings
Idiomas:          http://localhost:3000/admin/languages
Login:            http://localhost:3000/admin/login
```

### 8. Dicas de Teste

- ✅ Sempre teste com 2-3 idiomas diferentes
- ✅ Teste com diferentes navegadores (Chrome, Firefox, Safari)
- ✅ Teste em modo offline para ver tratamento de erros
- ✅ Teste o drag-and-drop em menus
- ✅ Teste o color picker para cores

---

**Status:** ✅ Fase 4 Pronta para Teste  
**Build:** SUCCESS  
**Rotas:** 20 geradas  
**Errors:** 0

Bom teste! 🚀
