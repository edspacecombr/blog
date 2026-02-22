# Professional Multilingual Blog - Architecture Overview

## System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Client Browser                           │
└────────────────────────────┬──────────────────────────────────────┘
                             │ HTTP/HTTPS
                             │
        ┌────────────────────┴──────────────────┐
        │                                       │
┌───────▼──────────┐                 ┌────────▼─────────┐
│  Frontend        │                 │  SEO/Static      │
│  (Next.js 14)    │                 │  Content         │
│  - SPA Pages     │                 │  - Sitemap.xml   │
│  - Auth UI       │                 │  - Robots.txt    │
│  - Admin Panel   │                 │  - Structured    │
│  - Dashboard     │                 │    Data (JSON-LD)│
└────────┬─────────┘                 └──────────────────┘
         │
         │ REST API (JSON)
         │ JWT Authentication
         │
┌────────▼────────────────────────────────────────┐
│         Backend API Server (PHP 8.1+)           │
│                                                  │
│  ┌──────────────────────────────────────────┐  │
│  │  API Routes & Controllers                │  │
│  │  - /api/v1/auth/*                        │  │
│  │  - /api/v1/posts/*                       │  │
│  │  - /api/v1/categories/*                  │  │
│  │  - /api/v1/pages/*                       │  │
│  │  - /api/v1/media/*                       │  │
│  │  - /api/v1/menus/*                       │  │
│  └──────────────────────────────────────────┘  │
│                     ▲                           │
│  ┌────────────────┐ │ ┌──────────────────────┐ │
│  │  Middleware    │─┼─│  Services            │ │
│  │  - Auth        │ │ │  - Auth Service      │ │
│  │  - CORS        │ │ │  - Post Service      │ │
│  │  - Validation  │ │ │  - Media Service     │ │
│  │  - Logging     │ │ │  - SEO Service       │ │
│  └────────────────┘ │ └──────────────────────┘ │
│                     │                           │
│  ┌──────────────────┼──────────────────────┐   │
│  │    Database Abstraction Layer           │   │
│  │    - Connection Pool                    │   │
│  │    - Query Builder (Doctrine ready)     │   │
│  │    - Migration System                   │   │
│  └────────────────────────────────────────┘   │
└────────┬─────────────────────────────────────────┘
         │ TCP:3306
         │
┌────────▼────────────────────────┐
│   MySQL 8.0+ Database            │
│                                  │
│  ┌────────────────────────────┐ │
│  │  Tables (11 total)         │ │
│  │  - users                   │ │
│  │  - blog_settings           │ │
│  │  - posts                   │ │
│  │  - categories              │ │
│  │  - pages                   │ │
│  │  - menus                   │ │
│  │  - media_library           │ │
│  │  - languages               │ │
│  │  - language_settings       │ │
│  │  - post_category           │ │
│  │  - menu_items              │ │
│  └────────────────────────────┘ │
└─────────────────────────────────┘
```

### Technology Stack

#### Frontend (Client-Side)
- **Framework:** Next.js 14 (React 18)
- **Language:** TypeScript
- **Styling:** Tailwind CSS
- **State Management:** Zustand
- **HTTP Client:** Axios
- **Authentication:** JWT (stored in localStorage)

#### Backend (Server-Side)
- **Language:** PHP 8.1+
- **Architecture:** RESTful API with lightweight framework
- **Authentication:** JWT (Firebase/JWT library)
- **Database:** MySQL 8.0+
- **ORM:** Doctrine (prepared for Phase 1)
- **Logging:** Monolog

#### Database
- **Type:** Relational (MySQL 8.0+)
- **Encoding:** UTF-8MB4 (Complete Unicode support)
- **Collation:** utf8mb4_unicode_ci
- **Features:**
  - Multilingual support (language column)
  - Hierarchical relationships (categories, menus)
  - Foreign key constraints
  - Full-text search
  - Proper indexing

### Authentication Flow

```
┌─────────────┐
│   User      │
└──────┬──────┘
       │
       │ 1. Register / Login
       │ (email + password)
       ▼
┌─────────────────────────────┐
│  Backend Auth Controller    │
│  - Validate input           │
│  - Hash password (bcrypt)   │
│  - Create/Check user        │
└──────────┬──────────────────┘
           │
           │ 2. Generate JWT Token
           ▼
┌─────────────────────────────┐
│  JWT Token Structure        │
│  {                          │
│    userId: number           │
│    email: string            │
│    role: string             │
│    iat: timestamp           │
│    exp: timestamp           │
│  }                          │
└──────────┬──────────────────┘
           │
           │ 3. Return token to frontend
           ▼
┌─────────────────────────────┐
│  Frontend Storage           │
│  - localStorage             │
│  - Auth Store (Zustand)     │
└─────────────────────────────┘
           │
           │ 4. Include in all API calls
           │    Authorization: Bearer {token}
           ▼
┌─────────────────────────────┐
│  Backend Middleware         │
│  - Validate JWT             │
│  - Check expiration         │
│  - Extract user info        │
└─────────────────────────────┘
```

### Data Flow: Creating a Post

```
User Interface (React Component)
    │
    │ 1. Form Input
    │
    ▼
┌──────────────────┐
│ LoginForm/        │
│ RegisterForm/     │
│ PostForm          │
└────────┬─────────┘
         │
         │ 2. Validate input
         │
         ▼
┌──────────────────┐
│ API Service      │
│ (axios)          │
└────────┬─────────┘
         │
         │ 3. HTTP Request
         │ + JWT Token
         │
         ▼
┌──────────────────┐
│ Backend Router   │
└────────┬─────────┘
         │
         │ 4. Route to Handler
         │
         ▼
┌──────────────────────────────┐
│ Auth Middleware              │
│ - Extract JWT               │
│ - Validate token            │
│ - Attach user to request    │
└────────┬─────────────────────┘
         │
         │ 5. Proceed if valid
         │
         ▼
┌──────────────────────────────┐
│ Controller                    │
│ - Parse request data         │
│ - Validate business logic    │
└────────┬─────────────────────┘
         │
         │ 6. Call Service
         │
         ▼
┌──────────────────────────────┐
│ Service Layer                │
│ - Business logic             │
│ - Prepare data               │
└────────┬─────────────────────┘
         │
         │ 7. Database Query
         │
         ▼
┌──────────────────────────────┐
│ Database Connection          │
│ - Execute prepared statement│
│ - Handle transactions       │
└────────┬─────────────────────┘
         │
         │ 8. MySQL Database
         │
         ▼
┌──────────────────────────────┐
│ Insert/Update Record         │
│ - posts table                │
│ - media_library              │
│ - post_category              │
└─────────────────────────────┘
```

### Multilingual Architecture

```
┌────────────────────────────────────────────────────┐
│              Language Support                       │
├────────────────────────────────────────────────────┤
│                                                    │
│  Primary Language: English (en)                   │
│  Secondary Languages: Spanish (es), Portuguese (pt)
│                                                    │
├────────────────────────────────────────────────────┤
│              URL Structure                         │
├────────────────────────────────────────────────────┤
│  /en/blog/post-title                              │
│  /es/blog/titulo-del-post                         │
│  /pt/blog/titulo-do-post                          │
│                                                    │
├────────────────────────────────────────────────────┤
│              SEO Features                          │
├────────────────────────────────────────────────────┤
│  • hreflang tags (language alternates)            │
│  • Language-specific meta tags                    │
│  • Multiple sitemaps (one per language)           │
│  • Proper robots.txt configuration                │
│  • Localized Open Graph metadata                  │
│                                                    │
├────────────────────────────────────────────────────┤
│              Database Strategy                     │
├────────────────────────────────────────────────────┤
│  • Single content table with language column      │
│  • Independent slugs per language                 │
│  • Language settings table for config             │
│  • Supports RTL languages (direction field)       │
│                                                    │
└────────────────────────────────────────────────────┘
```

### Security Architecture

```
┌────────────────────────────────────────┐
│         Client Request                  │
└────────────────┬───────────────────────┘
                 │
                 ▼
        ┌─────────────────┐
        │ HTTPS/TLS       │
        │ Encryption      │
        └────────┬────────┘
                 │
                 ▼
        ┌──────────────────────┐
        │ CORS Validation      │
        │ (whitelist origins)  │
        └────────┬─────────────┘
                 │
                 ▼
        ┌──────────────────────┐
        │ JWT Token Validation │
        │ - Signature check    │
        │ - Expiration check   │
        │ - Payload extract    │
        └────────┬─────────────┘
                 │
                 ▼
        ┌──────────────────────┐
        │ Input Validation     │
        │ - Type checking      │
        │ - SQL sanitization   │
        │ - XSS prevention     │
        └────────┬─────────────┘
                 │
                 ▼
        ┌──────────────────────┐
        │ Role-Based Access    │
        │ Control (RBAC)       │
        │ - admin              │
        │ - editor             │
        │ - author             │
        └────────┬─────────────┘
                 │
                 ▼
        ┌──────────────────────┐
        │ Database Query       │
        │ - Prepared statements│
        │ - Parameter binding  │
        └──────────────────────┘
```

## Database Relationships

### Core Entities

```
┌──────────┐
│ Users    │
│ id (PK)  │
│ role     │ ◄────────────┐
│ status   │              │
└────┬─────┘              │
     │                    │
     │ 1:N                │
     │                    │
     ▼                    │
┌──────────────────┐      │
│ Posts            │      │
│ author_id (FK)───┼──────┘
│ slug             │
│ language         │
│ status           │      ┌──────────────┐
│ featured_image_id├──────┤ Media Library│
└────┬─────────────┘      │ id (PK)      │
     │ N:N                 └──────────────┘
     │
     ▼
┌──────────┐
│ Categories
│ language │
└──────────┘

┌──────────┐
│ Menus    │
│ language │──┐
└──────────┘  │ 1:N
              │
              ▼
         ┌──────────────┐
         │ Menu Items   │
         │ (hierarchy)  │
         └──────────────┘

┌──────────────┐
│ Pages        │
│ language     │
│ (static)     │
└──────────────┘

┌──────────────┐
│ Languages    │
└──────┬───────┘
       │ 1:N
       │
       ▼
┌──────────────────────────┐
│ Language Settings        │
│ (configuration per lang) │
└──────────────────────────┘
```

## Performance Considerations

1. **Indexing Strategy:**
   - Foreign keys indexed
   - Language columns indexed
   - Status fields indexed
   - Published date indexed for sorting
   - Full-text search on title + content

2. **Query Optimization:**
   - Connection pooling
   - Prepared statements
   - Query caching (future)
   - Pagination on list endpoints

3. **Frontend Performance:**
   - Lazy loading images
   - Code splitting with Next.js
   - CSS minification with Tailwind
   - Image optimization with Next.js Image component

4. **SEO Performance:**
   - Server-side rendering ready
   - Meta tags optimization
   - Sitemap generation
   - Structured data (JSON-LD)

## Scalability Path

### Phase 1: Content Management
- Post CRUD operations
- Category management
- Media management
- Page management

### Phase 2: Advanced Features
- Visual editor (GrapesJS)
- Content scheduling
- Auto-publishing pipelines
- Comment system

### Phase 3: SEO & Analytics
- SEO analytics
- Traffic reporting
- Keyword tracking
- Content recommendations

### Phase 4: Monetization
- AdSense integration
- Revenue tracking
- Affiliate system
- Premium content

### Phase 5: Optimization
- Performance tuning
- CDN integration
- Image optimization
- Caching layers

## Deployment Architecture

```
┌─────────────────────┐
│ Git Repository      │
│ (Versions)          │
└──────────┬──────────┘
           │
           │ CI/CD Pipeline
           │
           ▼
┌─────────────────────┐
│ Build & Test        │
│ - Lint              │
│ - Unit Tests        │
│ - Integration Tests │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Deployment          │
│                     │
│ Frontend:           │
│ Vercel/Netlify      │
│                     │
│ Backend:            │
│ Shared Hosting/VPS  │
│                     │
│ Database:           │
│ MySQL Managed       │
└─────────────────────┘
```

## Summary

The architecture follows clean separation of concerns with:
- **Frontend:** Lightweight SPA with Next.js
- **Backend:** RESTful API with PHP
- **Database:** Normalized MySQL schema with multilingual support
- **Security:** JWT-based authentication with RBAC
- **Scalability:** Designed for horizontal expansion
- **SEO:** Built-in optimization from the ground up

This foundation enables rapid feature development while maintaining code quality and security standards.
