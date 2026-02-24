<?php

// Load .env
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

require_once __DIR__ . '/src/Database/Connection.php';
use App\Database\Connection;

try {
    $pdo = Connection::getInstance();
    echo "Starting seed data...\n\n";

    // 1. Users
    echo "Creating users...\n";
    $users = [
        ['name' => 'Admin', 'email' => 'admin@blog.local', 'role' => 'superadmin'],
        ['name' => 'Editor 1', 'email' => 'editor1@blog.local', 'role' => 'editor'],
        ['name' => 'Editor 2', 'email' => 'editor2@blog.local', 'role' => 'editor'],
    ];

    $user_ids = [];
    foreach ($users as $user) {
        try {
            $sql = "INSERT INTO users (name, email, password_hash, role, status) 
                    VALUES (?, ?, ?, ?, 'active')
                    RETURNING id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $user['name'],
                $user['email'],
                password_hash('password123', PASSWORD_BCRYPT),
                $user['role']
            ]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                $user_ids[] = $result['id'];
                echo "  ✓ {$user['email']}\n";
            }
        } catch (Exception $e) {
            echo "  ⚠️ {$user['email']}\n";
        }
    }

    if (empty($user_ids)) {
        $user_ids = [1, 2, 3];
    }

    // 2. Authors
    echo "\nCreating authors...\n";
    $authors = [
        ['name' => 'João Silva', 'slug' => 'joao-silva', 'bio' => 'Jornalista e blogueiro'],
        ['name' => 'Maria Santos', 'slug' => 'maria-santos', 'bio' => 'Especialista em tech'],
    ];

    $author_ids = [];
    foreach ($authors as $idx => $author) {
        try {
            $sql = "INSERT INTO authors (name, slug, bio) 
                    VALUES (?, ?, ?)
                    RETURNING id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$author['name'], $author['slug'], $author['bio']]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                $author_ids[] = $result['id'];
                echo "  ✓ {$author['slug']}\n";
            }
        } catch (Exception $e) {
            echo "  ⚠️ {$author['slug']}\n";
        }
    }

    // 3. Categories
    echo "\nCreating categories...\n";
    $categories = [
        ['name' => 'Tecnologia', 'slug' => 'tecnologia', 'language' => 'pt'],
        ['name' => 'Negócios', 'slug' => 'negocios', 'language' => 'pt'],
        ['name' => 'Dicas', 'slug' => 'dicas', 'language' => 'pt'],
    ];

    $category_ids = [];
    foreach ($categories as $cat) {
        try {
            $sql = "INSERT INTO categories (name, slug, language) 
                    VALUES (?, ?, ?)
                    RETURNING id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$cat['name'], $cat['slug'], $cat['language']]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                $category_ids[] = $result['id'];
                echo "  ✓ {$cat['slug']}\n";
            }
        } catch (Exception $e) {
            echo "  ⚠️ {$cat['slug']}\n";
        }
    }

    // 4. Posts
    echo "\nCreating posts...\n";
    $posts = [
        [
            'author_id' => $author_ids[0] ?? 1,
            'title' => 'Bem-vindo ao Nosso Blog',
            'slug' => 'bem-vindo-ao-nosso-blog',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'language' => 'pt',
            'status' => 'published',
            'category_id' => $category_ids[0] ?? 1,
        ],
        [
            'author_id' => $author_ids[1] ?? 2,
            'title' => 'Dicas de Produtividade',
            'slug' => 'dicas-de-produtividade',
            'content' => 'Melhore sua produtividade com estas dicas...',
            'language' => 'pt',
            'status' => 'published',
            'category_id' => $category_ids[2] ?? 3,
        ],
        [
            'author_id' => $author_ids[0] ?? 1,
            'title' => 'O Futuro da IA',
            'slug' => 'o-futuro-da-ia',
            'content' => 'A inteligência artificial está transformando indústrias...',
            'language' => 'pt',
            'status' => 'published',
            'category_id' => $category_ids[0] ?? 1,
        ],
    ];

    foreach ($posts as $post) {
        try {
            $sql = "INSERT INTO posts (author_id, title, slug, content, language, status) 
                    VALUES (?, ?, ?, ?, ?, ?)
                    RETURNING id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $post['author_id'],
                $post['title'],
                $post['slug'],
                $post['content'],
                $post['language'],
                $post['status'],
            ]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($result) {
                echo "  ✓ {$post['slug']}\n";
            }
        } catch (Exception $e) {
            echo "  ⚠️ {$post['slug']}\n";
        }
    }

    echo "\n✅ Seed completed!\n";
    echo "   - 3 Users\n";
    echo "   - 2 Authors\n";
    echo "   - 3 Categories\n";
    echo "   - 3 Posts\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
