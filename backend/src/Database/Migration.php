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
        echo "Running PostgreSQL migrations...\n";
        
        $this->createUsersTable();
        $this->createLanguagesTable();
        $this->createBlogSettingsTable();
        $this->createCategoriesTable();
        $this->createPostsTable();
        $this->createPostCategoryTable();
        $this->createPagesTable();
        $this->createMenusTable();
        $this->createMediaLibraryTable();
        $this->createLanguageSettingsTable();
        
        echo "✓ All migrations completed successfully!\n";
    }

    private function createUsersTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL DEFAULT 'author',
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            email_verified BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_login_at TIMESTAMP NULL,
            CHECK (role IN ('admin', 'editor', 'author')),
            CHECK (status IN ('active', 'inactive', 'suspended'))
        );
        
        CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
        CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);
        CREATE INDEX IF NOT EXISTS idx_users_status ON users(status);
        ";
        $this->connection->exec($sql);
        echo "✓ Users table created\n";
    }

    private function createLanguagesTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS languages (
            id SERIAL PRIMARY KEY,
            code VARCHAR(10) UNIQUE NOT NULL,
            name VARCHAR(100) NOT NULL,
            native_name VARCHAR(100),
            is_enabled BOOLEAN DEFAULT FALSE,
            is_primary BOOLEAN DEFAULT FALSE,
            locale VARCHAR(10),
            direction VARCHAR(3) NOT NULL DEFAULT 'ltr',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CHECK (direction IN ('ltr', 'rtl'))
        );
        ";
        $this->connection->exec($sql);
        echo "✓ Languages table created\n";
    }

    private function createBlogSettingsTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS blog_settings (
            id SERIAL PRIMARY KEY,
            blog_name VARCHAR(255) NOT NULL,
            blog_description TEXT,
            blog_niche VARCHAR(100),
            primary_language VARCHAR(10) DEFAULT 'en',
            support_languages JSONB,
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
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE INDEX IF NOT EXISTS idx_blog_settings_lang ON blog_settings(primary_language);
        ";
        $this->connection->exec($sql);
        echo "✓ Blog Settings table created\n";
    }

    private function createCategoriesTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS categories (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            description TEXT,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            parent_id INT,
            display_order INT DEFAULT 0,
            color VARCHAR(7),
            icon VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
            UNIQUE(slug, language)
        );
        
        CREATE INDEX IF NOT EXISTS idx_categories_lang ON categories(language);
        CREATE INDEX IF NOT EXISTS idx_categories_parent ON categories(parent_id);
        ";
        $this->connection->exec($sql);
        echo "✓ Categories table created\n";
    }

    private function createPostsTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS posts (
            id SERIAL PRIMARY KEY,
            author_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            content TEXT,
            excerpt TEXT,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            status VARCHAR(20) NOT NULL DEFAULT 'draft',
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
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE(slug, language),
            CHECK (status IN ('draft', 'published', 'scheduled', 'archived'))
        );
        
        CREATE INDEX IF NOT EXISTS idx_posts_author ON posts(author_id);
        CREATE INDEX IF NOT EXISTS idx_posts_status ON posts(status);
        CREATE INDEX IF NOT EXISTS idx_posts_language ON posts(language);
        CREATE INDEX IF NOT EXISTS idx_posts_published_at ON posts(published_at);
        ";
        $this->connection->exec($sql);
        echo "✓ Posts table created\n";
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
        );
        ";
        $this->connection->exec($sql);
        echo "✓ Post Category table created\n";
    }

    private function createPagesTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS pages (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            content TEXT,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            status VARCHAR(20) NOT NULL DEFAULT 'draft',
            seo_title VARCHAR(255),
            seo_description VARCHAR(500),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CHECK (status IN ('published', 'draft'))
        );
        
        CREATE INDEX IF NOT EXISTS idx_pages_slug ON pages(slug);
        CREATE INDEX IF NOT EXISTS idx_pages_language ON pages(language);
        CREATE INDEX IF NOT EXISTS idx_pages_status ON pages(status);
        ";
        $this->connection->exec($sql);
        echo "✓ Pages table created\n";
    }

    private function createMenusTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS menus (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            display_location VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE INDEX IF NOT EXISTS idx_menus_lang ON menus(language);
        ";
        $this->connection->exec($sql);
        
        $sql = "
        CREATE TABLE IF NOT EXISTS menu_items (
            id SERIAL PRIMARY KEY,
            menu_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            url VARCHAR(500),
            target_type VARCHAR(20) DEFAULT 'none',
            target_id INT,
            display_order INT DEFAULT 0,
            parent_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
            FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE,
            CHECK (target_type IN ('page', 'post', 'category', 'external', 'none'))
        );
        
        CREATE INDEX IF NOT EXISTS idx_menu_items_menu ON menu_items(menu_id);
        CREATE INDEX IF NOT EXISTS idx_menu_items_display ON menu_items(display_order);
        ";
        $this->connection->exec($sql);
        echo "✓ Menus and Menu Items tables created\n";
    }

    private function createMediaLibraryTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS media_library (
            id SERIAL PRIMARY KEY,
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
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
        );
        
        CREATE INDEX IF NOT EXISTS idx_media_type ON media_library(file_type);
        CREATE INDEX IF NOT EXISTS idx_media_uploaded_by ON media_library(uploaded_by);
        ";
        $this->connection->exec($sql);
        echo "✓ Media Library table created\n";
    }

    private function createLanguageSettingsTable(): void
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS language_settings (
            id SERIAL PRIMARY KEY,
            language_id INT NOT NULL,
            setting_key VARCHAR(255) NOT NULL,
            setting_value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE,
            UNIQUE(language_id, setting_key)
        );
        ";
        $this->connection->exec($sql);
        echo "✓ Language Settings table created\n";
    }
}
