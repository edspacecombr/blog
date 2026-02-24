# 🔗 Instruções de Conexão - PostgreSQL + Redis Remoto (Docker)

**Situação**: PostgreSQL e Redis rodando em Docker em outra instância WSL2

---

## 📋 Pré-requisitos

Antes de validar as conexões, você precisa das seguintes informações do Docker remoto:

### PostgreSQL (Docker)

```
□ Host/IP do Container: ________________
□ Port: ________________ (padrão: 5432)
□ Database: ________________ (padrão: blog_platform)
□ Username: ________________ (padrão: blog_user)
□ Password: ________________
```

### Redis (Docker)

```
□ Host/IP do Container: ________________
□ Port: ________________ (padrão: 6379)
□ Password: ________________ (deixar vazio se sem password)
```

---

## 🔍 Como Obter as Informações

### Obter Host/IP do Container

Na **WSL2 remota com Docker**, execute:

```bash
# Listar containers
docker ps

# Ver IP do container PostgreSQL
docker inspect [CONTAINER_ID] | grep IPAddress

# Ou se usar docker-compose
docker-compose ps
docker-compose exec postgres sh -c 'hostname -I'

# Ou verificar docker-compose.yml
cat docker-compose.yml | grep -A 10 "postgres:"
```

**Resultado esperado:**
```
CONTAINER ID   IMAGE              PORTS
abc123def456   postgres:13        0.0.0.0:5432->5432/tcp
```

O Host pode ser:
- `localhost` (se acessar da mesma máquina)
- `172.17.0.2` (Docker network IP)
- `host.docker.internal` (especial para Docker Desktop)
- IP da WSL2 remota (ex: `192.168.1.100`)

### Obter Credenciais PostgreSQL

```bash
# Se estiver no docker-compose
cat docker-compose.yml | grep -i postgres -A 20

# Se estiver rodando o container
docker logs [POSTGRES_CONTAINER] | grep "password"

# Ou dentro do container
docker exec [POSTGRES_CONTAINER] env | grep POSTGRES
```

### Obter Credenciais Redis

```bash
# Verificar docker-compose.yml
cat docker-compose.yml | grep -i redis -A 20

# Ou testar diretamente
docker exec [REDIS_CONTAINER] redis-cli CONFIG GET requirepass
```

---

## 🧪 Teste de Conectividade Básica

### Do Host Local (WSL2 Local)

**Para PostgreSQL:**

```bash
# Teste de conexão básica
nc -zv [HOST] [PORT]

# Ou com telnet
telnet [HOST] [PORT]

# Ou com psql
psql -h [HOST] -p [PORT] -U [USERNAME] -d [DATABASE]
```

**Para Redis:**

```bash
# Teste de conexão básica
nc -zv [HOST] [PORT]

# Ou com redis-cli
redis-cli -h [HOST] -p [PORT] ping
```

---

## 💾 Guardar as Informações

Crie um arquivo `DOCKER_CREDENTIALS.txt` (não commitar!):

```
# PostgreSQL Connection
DB_HOST=192.168.1.100
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_user
DB_PASSWORD=YourSecurePassword123

# Redis Connection
REDIS_HOST=192.168.1.100
REDIS_PORT=6379
REDIS_PASSWORD=
```

---

## 🚀 Iniciar Validação

Uma vez com as informações, execute:

```bash
# 1. Tornar scripts executáveis
chmod +x health-check.sh validate-connections.php install-php8-env.sh

# 2. Verificar saúde do ambiente local
./health-check.sh

# 3. Validar conexões com Docker remoto
php validate-connections.php
```

### Durante a Execução do validate-connections.php

O script irá solicitar:

```
📋 Current Configuration:

🔵 PostgreSQL:
  • Host: localhost
  • Port: 5432
  • Database: blog_platform
  • User: blog_user
  • Password: ***

🔵 Redis:
  • Host: localhost
  • Port: 6379
  • Password: (none)
  • Database: 0

❓ Use these settings? (yes/no/customize): 
```

**Opções:**
- `yes` - Usar configuração padrão
- `no` - Sair sem fazer nada
- `customize` - Personalizar cada parâmetro

---

## 📝 Cenários Comuns

### Cenário 1: Docker na mesma WSL2 Local

```
DB_HOST=localhost
REDIS_HOST=localhost
```

### Cenário 2: Docker em outra WSL2

```
DB_HOST=192.168.1.100  # IP da WSL2 remota
DB_PORT=5432           # Port mapeada em docker-compose

REDIS_HOST=192.168.1.100
REDIS_PORT=6379
```

### Cenário 3: Docker em máquina remota

```
DB_HOST=remote.example.com
DB_PORT=5432           # Port pública

REDIS_HOST=remote.example.com
REDIS_PORT=6379
```

### Cenário 4: Docker com network customizada

```
# Se estiver em network Docker
DB_HOST=postgres  # nome do serviço em docker-compose
REDIS_HOST=redis

# Ou IP da network
DB_HOST=172.20.0.2
REDIS_HOST=172.20.0.3
```

---

## ⚠️ Troubleshooting de Conexão

### Erro: "Connection refused"

```
❌ O host/porta está incorreto ou o container não está rodando

✅ Solução:
1. Verificar docker ps na WSL2 remota
2. Confirmar portas em docker-compose.yml
3. Confirmar firewall permite a porta
```

### Erro: "Authentication failed"

```
❌ Credenciais estão incorretas

✅ Solução:
1. Verificar username e password em docker-compose.yml
2. Testar direto no container:
   docker exec postgres psql -U blog_user -d blog_platform -c "SELECT 1;"
```

### Erro: "Cannot connect to host"

```
❌ Host está errado (localhost vs IP da máquina)

✅ Solução:
1. Se Docker está em outra WSL2, usar seu IP
2. Obter IP com: wsl -l -v (no Windows)
3. Ou dentro do container: hostname -I
```

### Erro: "Database does not exist"

```
❌ Database não foi criado no PostgreSQL

✅ Solução:
1. Criar database:
   docker exec postgres createdb -U blog_user blog_platform
2. Ou usar SQL:
   docker exec postgres psql -U postgres -c "CREATE DATABASE blog_platform;"
```

### Erro: "Redis NOAUTH Authentication required"

```
❌ Redis requer password

✅ Solução:
1. Obter password do redis.conf ou docker-compose
2. Adicionar REDIS_PASSWORD no .env
3. Ou desabilitar password se for dev:
   docker exec redis redis-cli CONFIG SET requirepass ""
```

---

## ✅ Checklist Final

Antes de executar validate-connections.php:

- [ ] Confirmar que Docker está rodando em WSL2 remota
- [ ] Confirmar PostgreSQL está acessível (docker ps mostra container)
- [ ] Confirmar Redis está acessível (docker ps mostra container)
- [ ] Obter corretamente Host/Port do Docker
- [ ] Obter credenciais PostgreSQL
- [ ] Testar conectividade básica com nc/telnet
- [ ] PHP 8.1 instalado localmente
- [ ] Extensão pdo_pgsql instalada
- [ ] Extensão redis instalada
- [ ] Projeto clonado em `/home/edblack/projetos/blog`

---

## 🎯 Próximas Ações

1. **Coletar informações** do Docker remoto (veja acima)
2. **Executar health-check.sh** para validar ambiente local
3. **Executar validate-connections.php** com informações do Docker
4. **Revisar .env** gerado
5. **Rodar migrations** se necessário
6. **Iniciar servidores** (Backend + Frontend)

---

## 📚 Documentação Relacionada

- **LOCAL_SETUP_GUIDE.md** - Guia completo de setup local
- **NEXT_STEPS.md** - Próximas ações Phase 1
- **docs/POSTGRESQL_REDIS_SETUP.md** - Setup completo PostgreSQL + Redis

---

**Última Atualização**: 22 de Fevereiro de 2026  
**Status**: Pronto para VALIDAÇÃO
