# Professional Multilingual Blog - Database Schema

## Overview
This document defines the complete database schema for the Professional Multilingual Blog platform.

## Database Configuration
- **Engine:** PostgreSQL 13.0+
- **Encoding:** UTF-8 (default in PostgreSQL)
- **Connection Pool:** Redis 6.0+ (for caching & sessions)

## Technology Stack - Phase 0
- **Primary Database:** PostgreSQL 13.0+
- **Cache & Sessions:** Redis 6.0+
- **Backend:** PHP 8.1+ with PDO PostgreSQL driver
- **Frontend:** Next.js 14 with TypeScript

## Tables

### 1. Users
Manages system users and administrators.
```
id (SERIAL, PK)
email (VARCHAR 255, UNIQUE)
password_hash (VARCHAR 255)
name (VARCHAR 255)
role (VARCHAR 20: admin, editor, author)
status (VARCHAR 20: active, inactive, suspended)
email_verified (BOOLEAN)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
last_login_at (TIMESTAMP)
```

### 2. Blog Settings
Stores blog configuration and metadata.
```
id (SERIAL, PK)
blog_name (VARCHAR 255)
blog_description (TEXT)
blog_niche (VARCHAR 100)
primary_language (VARCHAR 10)
support_languages (JSONB)
logo_url (VARCHAR 500)
favicon_url (VARCHAR 500)
primary_color (VARCHAR 7)
secondary_color (VARCHAR 7)
accent_color (VARCHAR 7)
layout_template (VARCHAR 50)
admin_email (VARCHAR 255)
enable_comments (BOOLEAN)
enable_newsletter (BOOLEAN)
seo_title (TEXT)
seo_description (TEXT)
seo_keywords (TEXT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 3. Posts
Core content table for blog posts.
```
id (SERIAL, PK)
author_id (INT, FK → users)
title (VARCHAR 255)
slug (VARCHAR 255)
content (TEXT)
excerpt (TEXT)
language (VARCHAR 10)
status (VARCHAR 20: draft, published, scheduled, archived)
featured_image_id (INT, FK → media_library)
seo_title (VARCHAR 255)
seo_description (VARCHAR 500)
seo_keywords (VARCHAR 255)
meta_og_title (VARCHAR 255)
meta_og_description (VARCHAR 500)
meta_og_image_id (INT, FK → media_library)
views_count (INT)
published_at (TIMESTAMP)
scheduled_at (TIMESTAMP)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 4. Categories
Taxonomy for organizing posts.
```
id (SERIAL, PK)
name (VARCHAR 255)
slug (VARCHAR 255)
description (TEXT)
language (VARCHAR 10)
parent_id (INT, FK → categories)
display_order (INT)
color (VARCHAR 7)
icon (VARCHAR 50)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 5. Post-Category Junction
Many-to-many relationship between posts and categories.
```
post_id (INT, FK → posts)
category_id (INT, FK → categories)
```

### 6. Pages
Static pages (About, Privacy, etc).
```
id (SERIAL, PK)
title (VARCHAR 255)
slug (VARCHAR 255, UNIQUE)
content (TEXT)
language (VARCHAR 10)
status (VARCHAR 20: published, draft)
seo_title (VARCHAR 255)
seo_description (VARCHAR 500)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 7. Menus
Navigation menu definitions.
```
id (SERIAL, PK)
name (VARCHAR 255)
slug (VARCHAR 255, UNIQUE)
language (VARCHAR 10)
display_location (VARCHAR 50)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 8. Menu Items
Individual menu items with hierarchy.
```
id (SERIAL, PK)
menu_id (INT, FK → menus)
title (VARCHAR 255)
url (VARCHAR 500)
target_type (VARCHAR 20: page, post, category, external, none)
target_id (INT)
display_order (INT)
parent_id (INT, FK → menu_items)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 9. Media Library
Central media management.
```
id (SERIAL, PK)
filename (VARCHAR 255)
original_name (VARCHAR 255)
file_path (VARCHAR 500)
file_type (VARCHAR 50)
file_size (INT)
width (INT)
height (INT)
alt_text_en (VARCHAR 255)
alt_text_es (VARCHAR 255)
alt_text_pt (VARCHAR 255)
mime_type (VARCHAR 100)
uploaded_by (INT, FK → users)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 10. Languages
Supported languages configuration.
```
id (SERIAL, PK)
code (VARCHAR 10, UNIQUE)
name (VARCHAR 100)
native_name (VARCHAR 100)
is_enabled (BOOLEAN)
is_primary (BOOLEAN)
locale (VARCHAR 10)
direction (VARCHAR 3: ltr, rtl)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 11. Language Settings
Language-specific configuration.
```
id (SERIAL, PK)
language_id (INT, FK → languages)
setting_key (VARCHAR 255)
setting_value (TEXT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

## Indexes
All tables include appropriate indexes for:
- Foreign keys
- Frequently queried fields (status, language, published_at)
- Full-text search (PostgreSQL GIN indexes)
- Unique constraints on slugs

## Redis Cache Structure
```
blog:post:{id}                    # Cached post data (1 hour TTL)
blog:posts:lang:{lang}            # Cached post list per language
blog:categories:lang:{lang}       # Cached categories
blog:menus:lang:{lang}            # Cached menus
blog:settings                     # Blog settings (24 hour TTL)
blog:user_sessions:{user_id}      # User session data
```

## Considerations
- All timestamps use UTC
- Slugs are unique per language
- Multilingual support through language column
- Cascading deletes for referential integrity
- Support for RTL languages through direction field
- JSONB columns for flexible multilingual data
- PostgreSQL native types (JSONB, SERIAL, TIMESTAMP)

## PostgreSQL Specific Features Used
- **JSONB:** For storing complex multilingual data structures
- **Serial:** For auto-incrementing primary keys
- **CHECK constraints:** For enum-like fields (role, status, direction)
- **GIN indexes:** For full-text search capabilities
- **UNIQUE constraints:** For slug uniqueness per language

## Redis Considerations
- Session storage: User authentication tokens
- Cache layer: Frequently accessed data (posts, settings)
- Rate limiting: API request tracking
- Queue preparation: For future job/queue system

## Tables

### 1. Users
Manages system users and administrators.
```
id (INT, PK, AI)
email (VARCHAR 255, UNIQUE)
password_hash (VARCHAR 255)
name (VARCHAR 255)
role (ENUM: admin, author, editor)
status (ENUM: active, inactive, suspended)
email_verified (BOOLEAN)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
last_login_at (TIMESTAMP)
```

### 2. Blog Settings
Stores blog configuration and metadata.
```
id (INT, PK, AI)
blog_name (VARCHAR 255)
blog_description (TEXT)
blog_niche (VARCHAR 100)
primary_language (VARCHAR 10)
support_languages (JSON)
logo_url (VARCHAR 500)
favicon_url (VARCHAR 500)
primary_color (VARCHAR 7)
secondary_color (VARCHAR 7)
accent_color (VARCHAR 7)
layout_template (VARCHAR 50)
admin_email (VARCHAR 255)
enable_comments (BOOLEAN)
enable_newsletter (BOOLEAN)
seo_title (TEXT)
seo_description (TEXT)
seo_keywords (TEXT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 3. Posts
Core content table for blog posts.
```
id (INT, PK, AI)
author_id (INT, FK → users)
title (VARCHAR 255)
slug (VARCHAR 255)
content (LONGTEXT)
excerpt (TEXT)
language (VARCHAR 10)
status (ENUM: draft, published, scheduled, archived)
featured_image_id (INT, FK → media_library)
seo_title (VARCHAR 255)
seo_description (VARCHAR 500)
seo_keywords (VARCHAR 255)
meta_og_title (VARCHAR 255)
meta_og_description (VARCHAR 500)
meta_og_image_id (INT, FK → media_library)
views_count (INT)
published_at (TIMESTAMP)
scheduled_at (TIMESTAMP)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 4. Categories
Taxonomy for organizing posts.
```
id (INT, PK, AI)
name (VARCHAR 255)
slug (VARCHAR 255)
description (TEXT)
language (VARCHAR 10)
parent_id (INT, FK → categories)
display_order (INT)
color (VARCHAR 7)
icon (VARCHAR 50)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 5. Post-Category Junction
Many-to-many relationship between posts and categories.
```
post_id (INT, FK → posts)
category_id (INT, FK → categories)
```

### 6. Pages
Static pages (About, Privacy, etc).
```
id (INT, PK, AI)
title (VARCHAR 255)
slug (VARCHAR 255, UNIQUE)
content (LONGTEXT)
language (VARCHAR 10)
status (ENUM: published, draft)
seo_title (VARCHAR 255)
seo_description (VARCHAR 500)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 7. Menus
Navigation menu definitions.
```
id (INT, PK, AI)
name (VARCHAR 255)
slug (VARCHAR 255, UNIQUE)
language (VARCHAR 10)
display_location (VARCHAR 50)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 8. Menu Items
Individual menu items with hierarchy.
```
id (INT, PK, AI)
menu_id (INT, FK → menus)
title (VARCHAR 255)
url (VARCHAR 500)
target_type (ENUM: page, post, category, external, none)
target_id (INT)
display_order (INT)
parent_id (INT, FK → menu_items)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 9. Media Library
Central media management.
```
id (INT, PK, AI)
filename (VARCHAR 255)
original_name (VARCHAR 255)
file_path (VARCHAR 500)
file_type (VARCHAR 50)
file_size (INT)
width (INT)
height (INT)
alt_text_en (VARCHAR 255)
alt_text_es (VARCHAR 255)
alt_text_pt (VARCHAR 255)
mime_type (VARCHAR 100)
uploaded_by (INT, FK → users)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 10. Languages
Supported languages configuration.
```
id (INT, PK, AI)
code (VARCHAR 10, UNIQUE)
name (VARCHAR 100)
native_name (VARCHAR 100)
is_enabled (BOOLEAN)
is_primary (BOOLEAN)
locale (VARCHAR 10)
direction (ENUM: ltr, rtl)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

### 11. Language Settings
Language-specific configuration.
```
id (INT, PK, AI)
language_id (INT, FK → languages)
setting_key (VARCHAR 255)
setting_value (LONGTEXT)
created_at (TIMESTAMP)
updated_at (TIMESTAMP)
```

## Indexes
All tables include appropriate indexes for:
- Foreign keys
- Frequently queried fields (status, language, published_at)
- FULLTEXT search on posts (title + content)
- Unique constraints on slugs

## Considerations
- All timestamps use UTC
- Slugs are unique per language
- Multilingual support through language column
- Cascading deletes for referential integrity
- Support for RTL languages through direction enum
