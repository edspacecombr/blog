_# ESCOPO DO PROJETO (v2.0): Blog Profissional Multilíngue com Foco em SEO e AdSense

## 1. Visão Geral do Projeto

Este documento define o escopo para o desenvolvimento de um sistema de blog profissional, projetado para maximizar o tráfego orgânico e a monetização através de otimização técnica e editorial de SEO. O sistema será construído com suporte nativo a múltiplos idiomas, permitindo uma distribuição global de conteúdo. Uma característica fundamental será a pré-instalação inteligente, que configura o blog automaticamente com base no nicho e idioma selecionados, e a capacidade de automação de publicação. O sistema deve ser flexível visualmente e operar eficientemente em ambientes de hospedagem compartilhada ou VPS, sem depender de plataformas externas.

## 2. Objetivo Principal

O objetivo primordial é desenvolver uma plataforma de blog que seja intrinsecamente otimizada para SEO, tanto em nível local quanto internacional. A plataforma deve ser focada no crescimento orgânico sustentável e na monetização global do conteúdo. A capacidade multilíngue será um diferencial, permitindo a gestão de conteúdo em diversos idiomas de forma opcional por projeto. A configuração inicial será automatizada e baseada no nicho e idioma, garantindo facilidade de administração, escalabilidade e automação. Além disso, o sistema será preparado para integrações via API, como n8n.

## 3. Arquitetura Geral Proposta

A arquitetura do sistema será concebida para ser robusta e flexível. Será adotada uma abordagem de **backend desacoplado ou monolito leve**, visando a performance e a manutenibilidade. O **frontend será baseado em templates reutilizáveis**, facilitando a customização e a consistência visual. Para persistência de dados, será utilizado um **banco de dados relacional**. O sistema será implementado com backend em **PHP 8**  e frontend  em **nextjs**, e o deploy será simplificado para ambientes de **shared hosting ou VPS**. A estrutura será preparada para **internacionalização (i18n)** desde o início.

## 4. Requisitos Funcionais Detalhados

### 4.1. Pré-instalação (Setup Inicial Guiado)

Um fluxo inicial obrigatório guiará o usuário na instalação do blog, coletando as seguintes informações:

*   **Informações do Blog:** Nome, descrição curta (para SEO), nicho principal, idioma principal, idiomas secundários (opcional), país/localização principal.
*   **Identidade Visual:** Upload de logo e favicon, definição de paleta de cores (primária, secundária, destaque) e fonte principal (opcional).
*   **Configuração por Nicho e Idioma:** O sistema sugerirá automaticamente layouts ideais (entre 2 ou 3 opções), estrutura de homepage por idioma, tipos de artigo comuns no nicho, categorias iniciais e estrutura de URLs (localizada ou global). O administrador poderá alterar layout e idioma após a instalação.

### 4.2. Sistema de Multilinguagem (Core)

*   Suporte nativo a múltiplos idiomas, com um idioma principal obrigatório e idiomas adicionais opcionais.
*   Estrutura de URL por idioma (ex: `/pt/`, `/en/`, `/es/`).
*   Conteúdo independente por idioma, permitindo artigos traduzidos manualmente ou artigos exclusivos por idioma.
*   **SEO Internacional:** Implementação automática de `hreflang`, sitemap por idioma e meta tags localizadas.
*   Layout e identidade visual compartilhados ou customizáveis por idioma.

### 4.3. Sistema de Layouts

*   Mínimo de 2 a 3 layouts disponíveis, projetados para SEO, UX e conversão.
*   Layout configurável globalmente ou por idioma.
*   Componentes reutilizáveis: Header, Footer, Sidebar, Cards de artigos.
*   Troca de layout sem perda de conteúdo.

### 4.4. Painel Administrativo

#### 4.4.1. Gestão de Conteúdo

*   Funcionalidades CRUD (Criar, Ler, Atualizar, Excluir) para artigos.
*   Definição de idioma do artigo e possibilidade de relacionar artigos entre idiomas (opcional).
*   Editor de conteúdo com suporte a headings (H1–H6), imagens, galeria reutilizável e blocos customizados.
*   Agendamento de posts e controle de status (rascunho, publicado).

#### 4.4.2. Gestão de Páginas e Menus

*   **Gestão de Páginas Estáticas:**
    *   Funcionalidades CRUD para criação e edição de páginas estáticas (ex: "Sobre Nós", "Política de Privacidade", "Contato").
    *   Editor de conteúdo para páginas, com suporte a texto, imagens e blocos.
    *   Definição de URL amigável (slug) para cada página.
    *   Suporte multilíngue para o conteúdo das páginas.
*   **Gestão de Menus de Navegação:**
    *   Criação e edição de múltiplos menus (ex: menu principal, rodapé).
    *   Interface intuitiva para adicionar, remover e reordenar itens de menu (arrastar e soltar).
    *   **Vinculação de Itens de Menu:** Possibilidade de vincular itens de menu a:
        *   Páginas estáticas existentes.
        *   Categorias de posts.
        *   Posts específicos.
        *   URLs externas personalizadas.
    *   Suporte multilíngue para os títulos dos itens de menu.

#### 4.4.3. Editor Visual GrapesJS para Conteúdo

*   Integração de um editor web HTML visual (PCFGrapesJSEditor) para a criação e edição de posts e páginas.
*   **Funcionalidades do Editor:**
    *   Interface de arrastar e soltar para componentes (texto, imagens, seções, botões).
    *   Edição WYSIWYG (What You See Is What You Get) para fácil manipulação do layout e conteúdo.
    *   Biblioteca de componentes reutilizáveis e blocos pré-definidos.
    *   Suporte a templates para agilizar a criação de conteúdo.
    *   Edição de código-fonte HTML/CSS diretamente no editor (opcional).
    *   Exportação e importação de layouts HTML.
*   **Benefícios:** Facilita a criação de conteúdo visualmente rico e complexo por usuários sem conhecimento técnico em HTML/CSS, garantindo flexibilidade e agilidade na publicação.

#### 4.4.4. SEO Avançado

*   Campos para Title e Meta Description por post e idioma.
*   Slug customizável por idioma.
*   Open Graph localizado.
*   Schema básico (Article, BlogPosting).
*   Sitemap automático por idioma.
*   Robots.txt configurável.

#### 4.4.5. Biblioteca de Mídia

*   Upload centralizado de imagens.
*   Organização por pastas/tags.
*   Reutilização de imagens em múltiplos artigos.
*   Otimização básica (nome, alt por idioma, tamanho).

### 4.6. API & Automação

*   API REST ou GraphQL para integração externa.
*   Endpoints mínimos: Criar artigo (com idioma), Atualizar artigo, Publicar artigo, Upload de imagem.
*   Autenticação via token.
*   Compatibilidade com ferramentas como n8n, Make e scripts externos.
*   Preparado para pipelines de conteúdo multilíngue.

## 5. Requisitos Não Funcionais

### 5.1. SEO Técnico (Obrigatório)

*   URLs limpas e localizadas.
*   Performance otimizada.
*   Design Mobile-first.
*   HTML semântico.
*   Lazy loading de imagens.
*   Foco nas Core Web Vitals.
*   Implementação de Breadcrumbs.
*   Paginação SEO-friendly.
*   SEO internacional (hreflang + sitemaps múltiplos).

### 5.2. Escalabilidade

*   Suporte a múltiplos blogs no futuro.
*   Reaproveitamento de templates.
*   Código organizado para evolução.
*   Arquitetura preparada para expansão internacional.
*   Documentação mínima do sistema.

### 5.3. Monetização (Preparação)

*   Estrutura compatível com Google AdSense (por país/idioma) e programas de afiliados internacionais.
*   Blocos de anúncios configuráveis por idioma.
*   CTAs localizados para maior conversão.
*   Estratégia preparada para SEO internacional.

## 6. Requisitos Avançados de SEO e Conformidade AdSense (2026)

Para garantir a aprovação no Google AdSense e um posicionamento de destaque nos resultados de busca, o sistema deverá incorporar nativamente os seguintes requisitos, baseados nas diretrizes mais recentes do Google Search Essentials e políticas do AdSense.

### 6.1. Conformidade para Google AdSense

O sistema facilitará a criação e manutenção de um site em conformidade com as políticas do Google AdSense, incluindo:

*   **Geração de Páginas Essenciais:** O sistema incluirá templates e um gerador para as páginas obrigatórias:
    *   **Política de Privacidade:** Em conformidade com o GDPR, detalhando o uso de cookies e dados do usuário.
    *   **Termos de Serviço:** Definindo as regras de uso do site.
    *   **Sobre Nós (About Us):** Uma página para apresentar a equipe, o autor ou a empresa, reforçando os princípios de E-E-A-T (Experience, Expertise, Authoritativeness, Trustworthiness).
    *   **Contato:** Um formulário de contato funcional ou informações de contato claras.
*   **Navegação Clara:** A estrutura de menus e o rodapé devem ser de fácil acesso e conter links para todas as páginas legais.
*   **Experiência do Usuário (UX):** Os layouts padrão serão projetados para serem limpos, com foco na legibilidade e sem elementos intrusivos como pop-ups excessivos, em linha com as recomendações para uma boa experiência do usuário.

### 6.2. SEO Técnico e Editorial Avançado (Google Search Essentials)

Além dos requisitos básicos de SEO, o sistema terá funcionalidades avançadas para atender às diretrizes de "conteúdo útil, confiável e focado nas pessoas":

*   **Foco em E-E-A-T:**
    *   **Perfis de Autor:** O sistema permitirá a criação de biografias detalhadas para cada autor, com links para redes sociais e outras publicações, que poderão ser exibidas nos artigos.
    *   **Citação de Fontes:** O editor de conteúdo facilitará a citação de fontes externas, um fator importante para a credibilidade.
*   **Core Web Vitals:** A arquitetura e os templates serão otimizados para atingir as métricas de Core Web Vitals (LCP < 2.5s, FID < 100ms, CLS < 0.1).
*   **Schema Markup Avançado:** O sistema gerará automaticamente dados estruturados (JSON-LD) para:
    *   `Article` e `BlogPosting`
    *   `BreadcrumbList`
    *   `Author`
    *   `Organization`
*   **Otimização de Performance:**
    *   **Compressão de Imagens:** Suporte nativo para formatos modernos como WebP e AVIF.
    *   **Minificação de Ativos:** Minificação automática de arquivos CSS e JavaScript.
*   **Segurança:** Implementação de HTTPS por padrão e mecanismos de proteção contra spam nos formulários de comentários.

## 7. Resultado Esperado

O resultado esperado é um blog profissional, rápido e altamente otimizado para SEO local e internacional. Ele deve oferecer suporte robusto a múltiplos idiomas, ser fácil de administrar e estar pronto para automação de conteúdo e monetização global. A inteligência da pré-instalação por nicho e idioma garantirá uma configuração eficiente, e a independência de plataformas externas proporcionará maior controle e flexibilidade ao usuário. A incorporação dos requisitos avançados de SEO e conformidade com o AdSense visa garantir uma base sólida para o sucesso do blog a longo prazo.
