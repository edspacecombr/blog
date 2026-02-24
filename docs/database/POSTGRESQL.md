# 🗄️ PostgreSQL - Configuração e Conexão

## Informações de Conexão

```
Host: localhost
Port: 5432
Database: blog_platform
User: blog_admin
Password: <USE_ENV>
SSL Mode: prefer
```

## Ambiente

Os dados estão configurados em `backend/.env`:

```env
# Database (PostgreSQL)
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_admin
DB_PASSWORD=<USE_ENV>
DB_SSLMODE=prefer
```

## Validar Conexão (PHP)

```php
<?php
require 'backend/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/backend');
$dotenv->load();

try {
    $dsn = "pgsql:host=" . $_ENV['DB_HOST'] . 
           ";port=" . $_ENV['DB_PORT'] . 
           ";dbname=" . $_ENV['DB_DATABASE'] . 
           ";sslmode=" . $_ENV['DB_SSLMODE'];
    
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $result = $pdo->query('SELECT version()')->fetch();
    echo "✅ Conectado: " . $result['version'];
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage();
}
```

## Classe de Conexão (Backend)

Localizado em: `backend/src/Database/Connection.php`

```php
<?php
namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    public static function getInstance(): PDO
    {
        // Implementação com singleton
    }
}
```

## Próximas Migrações

As tabelas serão criadas em FASE 1:

- `users` - Usuários do sistema
- `posts` - Artigos e páginas
- `categories` - Categorias e subcategorias
- `authors` - Profiles de autores
- `languages` - Suporte multilíngue
- `site_settings` - Configurações globais
- E mais...

## Troubleshooting

### Erro de Autenticação
```
SQLSTATE[08006]: connection to server at "localhost" (127.0.0.1), port 5432 failed: 
FATAL:  password authentication failed for user "blog_admin"
```

**Solução:**
- Verificar senha no servidor PostgreSQL (WSL2)
- Confirmar que o usuário `blog_admin` existe
- Testar com client nativo do PostgreSQL

### Porta Não Acessível
Se a porta 5432 não estiver acessível:
- Verificar se PostgreSQL está rodando em outro WSL
- Confirmar firewall não está bloqueando
- Usar endereço IP em vez de localhost se necessário

## Performance e Índices

Recomendado após criar tabelas:

```sql
-- Índices essenciais
CREATE INDEX idx_posts_status ON posts(status);
CREATE INDEX idx_posts_created ON posts(created_at);
CREATE INDEX idx_posts_author ON posts(author_id);
CREATE INDEX idx_posts_category ON posts(category_id);
```

## Backup Recomendado

```bash
# Backup completo
pg_dump blog_platform > backup_$(date +%Y%m%d).sql

# Restaurar
psql blog_platform < backup_20260222.sql
```
