# 🔴 Redis - Cache e Sessões

## Informações de Conexão

```
Host: localhost
Port: 6379
Password: <USE_ENV>
Session DB: 0
Cache DB: 1
```

## Configuração no Backend

Localizado em `backend/.env`:

```env
# Redis (Cache & Session)
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=<USE_ENV>
REDIS_DB=0
REDIS_CACHE_DB=1
```

## Redis em PHP

### CacheService

Localizado em: `backend/src/Cache/CacheService.php`

```php
<?php
namespace App\Cache;

class CacheService
{
    private Redis $redis;
    private string $prefix = 'cache:';
    
    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
        if ($_ENV['REDIS_PASSWORD']) {
            $this->redis->auth($_ENV['REDIS_PASSWORD']);
        }
    }
    
    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        $this->redis->setex($this->prefix . $key, $ttl, serialize($value));
    }
    
    public function get(string $key): mixed
    {
        $value = $this->redis->get($this->prefix . $key);
        return $value ? unserialize($value) : null;
    }
}
```

### Validar Conexão

```php
<?php
require 'backend/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/backend');
$dotenv->load();

try {
    $redis = new Redis();
    $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
    if ($_ENV['REDIS_PASSWORD']) {
        $redis->auth($_ENV['REDIS_PASSWORD']);
    }
    
    // Test write/read
    $redis->set('test_key', 'test_value', 60);
    $value = $redis->get('test_key');
    
    echo $value === 'test_value' ? "✅ Redis OK" : "❌ Redis Falhou";
    $redis->close();
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
```

## Dois Databases

### DB 0 - Sessões
Para armazenar tokens e sessões de usuário:

```php
$redis->select(0);
$redis->setex('session:user:1', 3600, json_encode($userData));
```

### DB 1 - Cache de Query
Para cache de queries e dados estáticos:

```php
$redis->select(1);
$redis->setex('cache:posts:all', 3600, json_encode($posts));
```

## Monitoramento

### Conectar com redis-cli

```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>"

# Dentro do cli:
SELECT 0        # Ver sessões
KEYS *
DBSIZE          # Total de chaves
MONITOR         # Ver operações em tempo real
FLUSHDB         # Limpar DB atual
```

### Comandos Úteis

```bash
# Ver informações do servidor
redis-cli INFO

# Testar latência
redis-cli --latency

# Monitor de memória
redis-cli INFO memory

# Ver todas as chaves
redis-cli KEYS "*"

# Deletar chave específica
redis-cli DEL cache:posts:all
```

## Estratégia de Cache

### Posts (Query Heavy)
```php
$cacheKey = 'cache:posts:page:' . $page;
if ($posts = $cache->get($cacheKey)) {
    return $posts;
}

$posts = $db->query('SELECT * FROM posts WHERE status = ? LIMIT ? OFFSET ?', 
    ['published', 10, ($page-1)*10]);

$cache->set($cacheKey, $posts, 3600); // 1 hora
return $posts;
```

### Categorias (Semi-Estático)
```php
$cacheKey = 'cache:categories';
if ($categories = $cache->get($cacheKey)) {
    return $categories;
}

$categories = $db->query('SELECT * FROM categories ORDER BY name');
$cache->set($cacheKey, $categories, 86400); // 24 horas
return $categories;
```

### Invalidação de Cache
```php
public function invalidatePostCache(int $postId): void
{
    $this->redis->del('cache:posts:page:*');
    $this->redis->del('cache:post:' . $postId);
    $this->redis->del('cache:categories'); // Se post afeta categorias
}
```

## Performance Esperada

Com Redis cache:
- ✅ Posts queries: ~5ms (vs ~100ms sem cache)
- ✅ Categories: ~1ms (vs ~50ms sem cache)
- ✅ Memory usage: ~100MB (estimado)
- ✅ Hit rate: >85% em produção

## Troubleshooting

### Erro de Autenticação
```
ERR invalid password
```

**Solução:**
- Verificar senha em redis.conf
- Confirmar que está usando DB correto (0 ou 1)

### Conexão Recusada
```
Connection refused on localhost:6379
```

**Solução:**
- Verificar se Redis está rodando
- Conferir porta em redis.conf
- Usar IP em vez de localhost se necessário

### Out of Memory
```
MISCONF Redis is configured to save RDB snapshots, but is currently not able to 
persist on disk
```

**Solução:**
- Aumentar `maxmemory` no redis.conf
- Configurar política de eviction: `maxmemory-policy allkeys-lru`

## Persistência

### AOF (Append Only File)
```
# redis.conf
appendonly yes
appendfsync everysec
```

### RDB (Snapshot)
```
# redis.conf
save 900 1    # Salvar a cada 15min se 1+ chave mudou
save 300 10   # Salvar a cada 5min se 10+ chaves mudaram
save 60 10000 # Salvar a cada 1min se 10000+ chaves mudaram
```

## Próximas Etapas (FASE 1+)

- ✅ Setup cache base
- 🔄 Implementar invalidação de cache inteligente
- 🔄 Adicionar session store em Redis
- 🔄 Monitorar e otimizar hit rate
- 🔄 Setup de backup automático
