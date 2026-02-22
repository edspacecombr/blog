<?php

namespace App\Database;

use PDO;

class Migration
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function run(): void
    {
        echo "Running migrations...\n";
        
        $this->createUsersTable();
        $this->createBlogSettingsTable();
        $this->createPostsTable();
        $this->createCategoriesTable();
        $this->createPostCategoryTable();
        $this->createPagesTable();
        $this->createMenusTable();
        $this->createMediaLibraryTable();
        $this->createLanguagesTable();
        $this->createLanguageSettingsTable();
        
        echo "✓ All migrations completed successfully!\n";
    }

    private function createUsersTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(255) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            role ENUM('admin', 'author', 'editor') DEFAULT 'author',
            status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
            email_verified BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            last_login_at TIMESTAMP NULL,
            INDEX idx_email (email),
            INDEX idx_role (role),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Users table created\n";
    }

    private function createBlogSettingsTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS blog_settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            blog_name VARCHAR(255) NOT NULL,
            blog_description TEXT,
            blog_niche VARCHAR(100),
            primary_language VARCHAR(10) DEFAULT 'en',
            support_languages JSON,
            logo_url VARCHAR(500),
            favicon_url VARCHAR(500),
            primary_color VARCHAR(7),
            secondary_color VARCHAR(7),
            accent_color VARCHAR(7),
            layout_template VARCHAR(50) DEFAULT 'minimal',
            admin_email VARCHAR(255),
            enable_comments BOOLEAN DEFAULT FALSE,
            enable_newsletter BOOLEAN DEFAULT FALSE,
            seo_title TEXT,
            seo_description TEXT,
            seo_keywords TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_primary_language (primary_language)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Blog Settings table created\n";
    }

    private function createPostsTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS posts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            author_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            content LONGTEXT,
            excerpt TEXT,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
            featured_image_id INT,
            seo_title VARCHAR(255),
            seo_description VARCHAR(500),
            seo_keywords VARCHAR(255),
            meta_og_title VARCHAR(255),
            meta_og_description VARCHAR(500),
            meta_og_image_id INT,
            views_count INT DEFAULT 0,
            published_at TIMESTAMP NULL,
            scheduled_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (featured_image_id) REFERENCES media_library(id),
            FOREIGN KEY (meta_og_image_id) REFERENCES media_library(id),
            UNIQUE KEY unique_slug_lang (slug, language),
            INDEX idx_author (author_id),
            INDEX idx_status (status),
            INDEX idx_language (language),
            INDEX idx_published_at (published_at),
            FULLTEXT idx_search (title, content)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Posts table created\n";
    }

    private function createCategoriesTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS categories (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            description TEXT,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            parent_id INT,
            display_order INT DEFAULT 0,
            color VARCHAR(7),
            icon VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
            UNIQUE KEY unique_slug_lang (slug, language),
            INDEX idx_language (language),
            INDEX idx_parent (parent_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Categories table created\n";
    }

    private function createPostCategoryTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS post_category (
            post_id INT NOT NULL,
            category_id INT NOT NULL,
            PRIMARY KEY (post_id, category_id),
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Post Category table created\n";
    }

    private function createPagesTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS pages (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content LONGTEXT,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            status ENUM('published', 'draft') DEFAULT 'draft',
            seo_title VARCHAR(255),
            seo_description VARCHAR(500),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_slug (slug),
            INDEX idx_language (language),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Pages table created\n";
    }

    private function createMenusTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS menus (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            display_location VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_language (language)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        
        $sql = "
        CREATE TABLE IF NOT EXISTS menu_items (
            id INT PRIMARY KEY AUTO_INCREMENT,
            menu_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            url VARCHAR(500),
            target_type ENUM('page', 'post', 'category', 'external', 'none') DEFAULT 'none',
            target_id INT,
            display_order INT DEFAULT 0,
            parent_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
            FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE,
            INDEX idx_menu (menu_id),
            INDEX idx_display_order (display_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Menus and Menu Items tables created\n";
    }

    private function createMediaLibraryTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS media_library (
            id INT PRIMARY KEY AUTO_INCREMENT,
            filename VARCHAR(255) NOT NULL,
            original_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(500) NOT NULL,
            file_type VARCHAR(50),
            file_size INT,
            width INT,
            height INT,
            alt_text_en VARCHAR(255),
            alt_text_es VARCHAR(255),
            alt_text_pt VARCHAR(255),
            mime_type VARCHAR(100),
            uploaded_by INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_file_type (file_type),
            INDEX idx_uploaded_by (uploaded_by)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Media Library table created\n";
    }

    private function createLanguagesTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS languages (
            id INT PRIMARY KEY AUTO_INCREMENT,
            code VARCHAR(10) NOT NULL UNIQUE,
            name VARCHAR(100) NOT NULL,
            native_name VARCHAR(100),
            is_enabled BOOLEAN DEFAULT FALSE,
            is_primary BOOLEAN DEFAULT FALSE,
            locale VARCHAR(10),
            direction ENUM('ltr', 'rtl') DEFAULT 'ltr',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Languages table created\n";
    }

    private function createLanguageSettingsTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS language_settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            language_id INT NOT NULL,
            setting_key VARCHAR(255) NOT NULL,
            setting_value LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE,
            UNIQUE KEY unique_lang_key (language_id, setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        $this->connection->exec($sql);
        echo "✓ Language Settings table created\n";
    }
}
