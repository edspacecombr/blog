# 📋 PREPARAÇÃO DO AMBIENTE PHP 8.1 - README

**Data**: 22 de Fevereiro de 2026  
**Objetivo**: Preparar ambiente local PHP 8 para validar NEXT_STEPS.md  
**Banco de Dados**: PostgreSQL 13.0+ + Redis 6.0+ (em Docker, outra WSL2)

---

## 🎯 Situação Atual

```
┌─────────────────────┐              ┌──────────────────────┐
│  WSL2 LOCAL         │   Network    │  WSL2 DOCKER         │
│  (Esta instância)   │────────────→ │  (Outra instância)   │
│                     │              │                      │
│ • PHP 8.1 (novo)    │              │ • PostgreSQL 13+     │
│ • Backend           │              │ • Redis 6.0+         │
│ • Frontend          │              │ • Docker containers  │
│ • Node.js           │              │                      │
│                     │              │                      │
│ localhost:8000      │              │ [IP]:5432 (postgres) │
│ localhost:3000      │              │ [IP]:6379 (redis)    │
└─────────────────────┘              └──────────────────────┘
```

---

## 📦 Scripts e Arquivos Criados

### 1️⃣ **install-php8-env.sh** (Executável)

**Descrição**: Script automático de instalação PHP 8.1

**O que faz**:
- Verifica ferramentas instaladas
- Atualiza apt-get
- Instala PHP 8.1 com extensões
- Instala Composer
- Instala Node.js + npm
- Valida instalação
- Instala dependências do projeto

**Como usar**:
```bash
chmod +x install-php8-env.sh
./install-php8-env.sh
```

**Tempo**: 10-15 minutos

---

### 2️⃣ **validate-connections.php** (Executável)

**Descrição**: Script interativo de validação de conexões

**O que faz**:
- Lê configurações de ambiente ou solicita entrada
- Testa conexão PostgreSQL
- Testa conexão Redis
- Valida schema (11 tabelas)
- Testa operações de cache
- Gera arquivo .env

**Como usar**:
```bash
chmod +x validate-connections.php
php validate-connections.php
```

**Entrada Necessária**:
- Host PostgreSQL (de Docker remoto)
- Port PostgreSQL
- Database, Username, Password
- Host Redis
- Port Redis
- Password Redis (opcional)

**Saída**:
- ✅ Validação de todas as conexões
- 📝 Arquivo backend/.env gerado

---

### 3️⃣ **health-check.sh** (Executável)

**Descrição**: Verificação rápida de saúde do ambiente

**O que valida**:
- PHP 8.1
- Extensões PHP críticas
- Composer
- Node.js + npm
- Ferramentas de banco de dados
- Estrutura do projeto

**Como usar**:
```bash
chmod +x health-check.sh
./health-check.sh
```

**Tempo**: < 1 minuto

---

### 4️⃣ **LOCAL_SETUP_GUIDE.md** (Documentação)

**Descrição**: Guia completo passo-a-passo

**Conteúdo**:
- Pré-requisitos detalhados
- Instalação PHP (automática ou manual)
- Validação de extensões
- Configuração de projeto
- Execução de desenvolvimento
- Validação NEXT_STEPS.md
- Troubleshooting completo
- Health check

---

### 5️⃣ **DOCKER_CONNECTION_GUIDE.md** (Documentação)

**Descrição**: Guia de conexão com Docker remoto

**Conteúdo**:
- Checklist de informações necessárias
- Como obter dados do Docker remoto
- Teste de conectividade
- Cenários comuns
- Troubleshooting de conexão

---

## 🚀 INÍCIO RÁPIDO (5 passos)

### Passo 1: Instalar PHP 8.1
```bash
./install-php8-env.sh
```

### Passo 2: Verificar Saúde
```bash
./health-check.sh
```

### Passo 3: Coletar Credenciais Docker Remoto

Peça informações do Docker remoto:
- PostgreSQL: Host, Port, Database, Username, Password
- Redis: Host, Port, Password (opcional)

### Passo 4: Validar Conexões
```bash
php validate-connections.php
```

Será solicitado que você forneça as credenciais do Docker remoto.

### Passo 5: Iniciar Desenvolvimento

**Terminal 1**:
```bash
cd backend
php -S localhost:8000 -t public
```

**Terminal 2**:
```bash
cd frontend
npm run dev
```

---

## 📊 Estrutura de Diretórios

```
/home/edblack/projetos/blog/
├── install-php8-env.sh          ✅ Script automático
├── validate-connections.php     ✅ Validador de conexões
├── health-check.sh              ✅ Verificação de saúde
│
├── LOCAL_SETUP_GUIDE.md         📖 Guia completo
├── DOCKER_CONNECTION_GUIDE.md   📖 Guia de conexão
├── PREPARATION_README.md        📖 Este arquivo
│
├── backend/
│   ├── composer.json
│   ├── .env                     📝 Gerado por validate-connections.php
│   ├── src/
│   │   ├── Database/
│   │   │   ├── Connection.php   (PostgreSQL DSN)
│   │   │   └── Migration.php    (Schema creation)
│   │   ├── Cache/
│   │   │   └── CacheService.php (Redis abstraction)
│   │   └── ...
│   └── ...
│
├── frontend/
│   ├── package.json
│   ├── src/
│   └── ...
│
└── docs/
    ├── DATABASE_SCHEMA.md
    ├── POSTGRESQL_REDIS_SETUP.md
    └── ...
```

---

## ✅ Verificação de Pré-requisitos

Antes de começar, certifique-se:

- [ ] Ambiente Ubuntu 24.04+ (ou Linux similar)
- [ ] Acesso sudo para instalação
- [ ] PostgreSQL rodando em Docker (outra WSL2)
- [ ] Redis rodando em Docker (outra WSL2)
- [ ] Conectividade de rede entre WSL2s
- [ ] Portas 5432 e 6379 acessíveis
- [ ] Credenciais PostgreSQL + Redis

---

## 🔗 Informações Necessárias do Docker Remoto

**Você será solicitado a fornecer**:

```
PostgreSQL:
  □ Host: (ex: 192.168.1.100)
  □ Port: (ex: 5432)
  □ Database: (ex: blog_platform)
  □ Username: (ex: blog_user)
  □ Password: (ex: YourPassword123)

Redis:
  □ Host: (ex: 192.168.1.100)
  □ Port: (ex: 6379)
  □ Password: (opcional)
```

---

## 📈 Fluxo Completo

```
1. install-php8-env.sh
   ↓
   Instala PHP 8.1 + extensões + dependências

2. health-check.sh
   ↓
   Valida ambiente local

3. DOCKER_CONNECTION_GUIDE.md
   ↓
   Você obtém credenciais do Docker remoto

4. validate-connections.php
   ↓
   • Solicita credenciais
   • Testa conexões
   • Gera .env
   • Valida schema

5. Backend + Frontend
   ↓
   Servidores iniciam

6. NEXT_STEPS.md Validation
   ↓
   Testa funcionalidades
```

---

## 🧪 Testes de Validação

Com servidores rodando:

**Health Check**:
```bash
curl http://localhost:8000/api/v1/health
```

**Frontend**:
```bash
open http://localhost:3000
```

**Cache**:
```bash
redis-cli MONITOR
```

**Database**:
```bash
psql -h [HOST] -U [USER] -d blog_platform -c "SELECT 1;"
```

---

## ⚠️ Situações Especiais

### Se PostgreSQL não estiver criado

```bash
# No Docker remoto
docker exec [POSTGRES_CONTAINER] psql -U postgres -c \
  "CREATE DATABASE blog_platform;"
```

### Se Redis não estiver acessível

```bash
# No Docker remoto
docker exec [REDIS_CONTAINER] redis-cli PING
```

### Se Port 8000 estiver em uso

```bash
php -S localhost:8001 -t public
```

---

## 📚 Documentação Relacionada

- **LOCAL_SETUP_GUIDE.md** - Setup completo (detalhado)
- **DOCKER_CONNECTION_GUIDE.md** - Como conectar ao Docker
- **NEXT_STEPS.md** - Próximas fases do projeto
- **README.md** - Visão geral do projeto
- **docs/POSTGRESQL_REDIS_SETUP.md** - Setup PostgreSQL + Redis

---

## 🎯 Checklist Final

Antes de validar NEXT_STEPS.md:

- [ ] PHP 8.1 instalado
- [ ] Composer instalado
- [ ] Node.js + npm instalados
- [ ] health-check.sh passa com sucesso
- [ ] validate-connections.php conecta a PostgreSQL
- [ ] validate-connections.php conecta a Redis
- [ ] .env foi gerado em backend/
- [ ] Dependências instaladas (composer + npm)
- [ ] Migrations rodadas (se necessário)
- [ ] Backend rodando em localhost:8000
- [ ] Frontend rodando em localhost:3000

---

## 🚀 Próximas Ações

1. Executar `./install-php8-env.sh`
2. Fornecer credenciais do Docker remoto
3. Executar `php validate-connections.php`
4. Iniciar servidores
5. Validar NEXT_STEPS.md

---

**Status**: ✅ Preparação Completa  
**Próximo**: Executar install-php8-env.sh
