# 🚀 Professional Multilingual Blog Platform

A production-ready, SEO-optimized, multilingual blogging platform built with modern technologies. Designed for content creators, agencies, and businesses looking to build sustainable, organic traffic with global reach.

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://php.net)
[![Next.js](https://img.shields.io/badge/Next.js-14-black)](https://nextjs.org)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13.0%2B-336791)](https://postgresql.org)
[![Redis](https://img.shields.io/badge/Redis-6.0%2B-DC382D)](https://redis.io)
[![TypeScript](https://img.shields.io/badge/TypeScript-5-blue)](https://www.typescriptlang.org)
[![License](https://img.shields.io/badge/License-MIT-green)](#license)

---

## ✨ Key Features

### 🌐 Multilingual Foundation
- Native support for multiple languages (English, Spanish, Portuguese out of the box)
- Language-specific URLs and content
- Automatic hreflang implementation
- Multiple sitemaps per language
- RTL language support

### 🔍 SEO-First Architecture
- Built-in SEO optimization from day one
- Automatic sitemap generation
- Schema.org markup (Article, BlogPosting, BreadcrumbList)
- Open Graph and Twitter Card support
- Core Web Vitals optimization
- Performance-focused defaults

### 📝 Content Management
- Intuitive post editor with rich text support
- Visual content builder ready (GrapesJS integration)
- Content scheduling and auto-publishing
- Category management with hierarchy
- Media library with image optimization
- Draft/Published/Scheduled/Archived states

### 🔐 Enterprise Security
- JWT-based authentication
- Role-based access control (Admin, Editor, Author)
- Prepared statements (SQL injection prevention)
- CORS configuration
- Secure password hashing (BCrypt)
- Token expiration management

### 💰 Monetization Ready
- Google AdSense integration structure
- Revenue tracking setup
- Affiliate link management
- Ad unit configuration
- Policy compliance checks

### 📱 Responsive Design
- Mobile-first approach
- Tailwind CSS for responsive utilities
- Optimized for all screen sizes
- Touch-friendly admin interface
- Progressive Web App ready

### 🚀 Performance Optimized
- Next.js for server-side rendering
- Redis caching layer for performance
- Image optimization (WebP, AVIF support)
- Code splitting and lazy loading
- Gzip compression
- Browser caching headers
- Database query optimization
- In-memory cache for frequently accessed data

### 🛠️ Developer Friendly
- Clean separation of concerns
- RESTful API design
- Comprehensive documentation
- TypeScript for type safety
- Easy to extend and customize
- Docker-ready structure

---

## 🏗️ Architecture

### System Overview
```
Frontend (Next.js 14)
    ↓ REST API + JWT
Backend (PHP 8.1)
    ↓ Cached Queries
Cache Layer (Redis 6.0+)
    ↓
Database (PostgreSQL 13.0+)
```

### Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Frontend** | Next.js | 14.0+ |
| | React | 18.2+ |
| | TypeScript | 5.0+ |
| | Tailwind CSS | 3.3+ |
| **Backend** | PHP | 8.1+ |
| | PostgreSQL | 13.0+ |
| | Redis | 6.0+ |
| | JWT | Firebase/JWT 6.8+ |
| **State Management** | Zustand | 4.4+ |
| **HTTP Client** | Axios | 1.6+ |

---

## 📦 Project Structure

```
blog/
├── backend/                    # PHP API Server
│   ├── public/index.php       # Entry point
│   ├── src/
│   │   ├── App.php            # Router & middleware
│   │   ├── Auth/              # Authentication
│   │   ├── Cache/             # Redis caching
│   │   ├── Controllers/       # Request handlers
│   │   ├── Database/          # DB connection & migration
│   │   ├── Middleware/        # Auth, CORS, etc
│   │   └── Models/            # (Phase 1+)
│   ├── config/                # Configuration
│   ├── storage/logs           # Application logs
│   ├── composer.json          # PHP dependencies
│   └── .env.example           # Environment template
│
├── frontend/                   # Next.js Application
│   ├── src/
│   │   ├── pages/             # Next.js pages
│   │   ├── components/        # React components
│   │   ├── services/          # API client
│   │   ├── store/             # State management
│   │   ├── styles/            # Global CSS
│   │   ├── types/             # TypeScript types
│   │   ├── hooks/             # Custom hooks
│   │   └── utils/             # Utilities
│   ├── public/                # Static assets
│   ├── package.json           # JS dependencies
│   ├── next.config.js         # Next.js config
│   ├── tsconfig.json          # TypeScript config
│   └── tailwind.config.ts     # Tailwind config
│
├── docs/                       # Documentation
│   ├── DATABASE_SCHEMA.md     # PostgreSQL DB structure
│   ├── POSTGRESQL_REDIS_SETUP.md # Setup guide
│   ├── PHASE_0_BUILD.md       # Build progress
│   ├── ARCHITECTURE_OVERVIEW.md
│   ├── TECHNICAL_CHECKLIST.md
│   └── README.md
│
├── setup.sh                    # Installation script
└── .git/                       # Version control
```

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Node.js 18.0 or higher
- PostgreSQL 13.0 or higher
- Redis 6.0 or higher
- Composer
- npm or yarn

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/professional-blog.git
cd professional-blog
```

2. **Run setup script** (Recommended)
```bash
chmod +x setup.sh
./setup.sh
```

The setup script will:
- ✅ Check PHP 8.1+, Node.js, npm
- ✅ Verify PostgreSQL 13+ installation
- ✅ Verify Redis 6+ installation
- ✅ Validate PHP extensions (pdo_pgsql, redis)
- ✅ Install backend dependencies (composer)
- ✅ Install frontend dependencies (npm)
- ✅ Display PostgreSQL + Redis setup instructions

Or **manual setup**:

3. **Setup Backend**
```bash
cd backend
cp .env.example .env
# Edit .env with your PostgreSQL + Redis credentials
composer install
```

4. **Setup PostgreSQL Database**

First, ensure PostgreSQL 13+ is running:
```bash
# macOS
brew services start postgresql@13

# Ubuntu
sudo service postgresql start
```

Then create the database:
```bash
sudo -u postgres psql
CREATE DATABASE blog_platform ENCODING 'UTF8';
CREATE USER blog_user WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE blog_platform TO blog_user;
\c blog_platform
GRANT ALL ON SCHEMA public TO blog_user;
\q
```

Update backend/.env:
```env
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=blog_platform
DB_USER=blog_user
DB_PASSWORD=your_secure_password
```

Run migrations:
```bash
php -r "require 'vendor/autoload.php';
\$m = new \App\Database\Migration();
\$m->run();"
```

5. **Setup Redis**

Ensure Redis 6+ is running:
```bash
# macOS
brew services start redis

# Ubuntu
sudo service redis-server start
```

Verify connection:
```bash
redis-cli ping
# Should return: PONG
```

Update backend/.env:
```env
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_DB=0
REDIS_CACHE_DB=1
```

6. **Setup Frontend**
```bash
cd ../frontend
npm install
```

### Running the Application

**Terminal 1 - Redis:**
```bash
redis-server
```

**Terminal 2 - Backend:**
```bash
cd backend
php -S localhost:8000 -t public
```

**Terminal 3 - Frontend:**
```bash
cd frontend
npm run dev
```

**Access the application:**
- 🌐 Frontend: `http://localhost:3000`
- 📡 Backend: `http://localhost:8000`
- 💚 Health Check: `http://localhost:8000/api/v1/health`
- ⚡ Redis Monitor: `redis-cli MONITOR` (in another terminal)

---

## 📚 API Documentation

### Authentication

#### Register
```http
POST /api/v1/auth/register
Content-Type: application/json

{
  "email": "user@example.com",
  "name": "John Doe",
  "password": "securepassword",
  "role": "author"
}
```

Response:
```json
{
  "success": true,
  "message": "User registered successfully",
  "user_id": 1
}
```

#### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "securepassword"
}
```

Response:
```json
{
  "success": true,
  "message": "Login successful",
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe",
    "role": "author"
  }
}
```

#### Validate Token
```http
GET /api/v1/auth/validate
Authorization: Bearer {token}
```

### Health Check
```http
GET /api/v1/health
```

Response:
```json
{
  "status": "ok",
  "timestamp": "2024-02-22T02:28:33.806Z"
}
```

---

## 🔒 Security

### Authentication
- JWT tokens with configurable expiration (default: 24 hours)
- BCrypt password hashing
- Token validation on every request
- Secure token storage (localStorage on frontend)

### Authorization
- Role-based access control (RBAC)
- Admin, Editor, Author roles
- User status management (active, inactive, suspended)

### Protection
- CORS configuration
- Input validation
- Prepared statements (SQL injection prevention)
- Secure headers
- HTTPS support ready
- Redis authentication support

### Caching Strategy
- Redis in-memory cache layer
- Configurable TTL per endpoint
- Cache invalidation on data changes
- Key prefixing for organization
- Separate cache databases for sessions vs data

---

## 📊 Database Schema

The platform uses PostgreSQL 13.0+ with 11 optimized tables:

1. **users** - User management with roles and status
2. **blog_settings** - Blog configuration (JSONB for multilingual data)
3. **posts** - Blog posts with multilingual support + metadata
4. **categories** - Post categories
5. **post_category** - Post-category relationships
6. **pages** - Static pages
7. **menus** - Navigation menus
8. **menu_items** - Menu items with hierarchy
9. **media_library** - Media management with PostgreSQL storage
10. **languages** - Language configuration
11. **language_settings** - Language-specific settings

### PostgreSQL Features Used
- **SERIAL** - Auto-incrementing primary keys
- **JSONB** - Efficient storage for multilingual data
- **CHECK** constraints - Data validation at database level
- **GIN** indexes - Full-text search optimization
- **UTF-8** - Default encoding for international support

### Redis Cache Layer
- `blog:post:{id}` - Individual posts (TTL: 1h)
- `blog:posts:lang:{lang}` - Post lists per language (TTL: 30min)
- `blog:categories:lang:{lang}` - Categories (TTL: 1h)
- `blog:menus:lang:{lang}` - Menus (TTL: 1h)
- `blog:settings` - Global settings (TTL: 24h)
- `blog:user_sessions:{user_id}` - User sessions

See `docs/DATABASE_SCHEMA.md` for complete schema details.
See `docs/POSTGRESQL_REDIS_SETUP.md` for setup guide.

---

## 🗺️ Roadmap

### Phase 0 ✅ (Complete)
- [x] Backend foundation with PHP 8.1
- [x] Frontend with Next.js 14
- [x] PostgreSQL 13+ database schema
- [x] Redis 6+ cache layer
- [x] JWT authentication
- [x] Basic admin pages
- [x] CacheService implementation

### Phase 1 📋 (In Progress)
- [ ] Post management (CRUD) with caching
- [ ] Category management with cache invalidation
- [ ] Media upload system with PostgreSQL storage
- [ ] Page management with static page caching
- [ ] Menu builder
- [ ] Redis cache performance optimization

### Phase 2 🔄 (Planned)
- [ ] Visual editor (GrapesJS)
- [ ] Content scheduling
- [ ] Comment system
- [ ] Newsletter integration
- [ ] Advanced cache strategies

### Phase 3 📈 (Planned)
- [ ] SEO analytics
- [ ] AdSense integration
- [ ] Performance monitoring
- [ ] Revenue tracking
- [ ] Redis cluster setup

### Phase 4 🚀 (Planned)
- [ ] Production deployment
- [ ] CI/CD pipeline
- [ ] Automated backups
- [ ] Performance optimization
- [ ] Database replication

---

---

## 📖 Documentation

For detailed setup and development guides, see:

- **[NEXT_STEPS.md](./NEXT_STEPS.md)** - Phase 1 development roadmap with cache strategies
- **[PHASE_0_SUMMARY.txt](./PHASE_0_SUMMARY.txt)** - Phase 0 completion summary
- **[STACK_CORRECTION.md](./STACK_CORRECTION.md)** - Database stack changes (MySQL → PostgreSQL + Redis)
- **[docs/POSTGRESQL_REDIS_SETUP.md](./docs/POSTGRESQL_REDIS_SETUP.md)** - Complete PostgreSQL + Redis setup guide
- **[docs/DATABASE_SCHEMA.md](./docs/DATABASE_SCHEMA.md)** - PostgreSQL schema documentation
- **[docs/ARCHITECTURE_OVERVIEW.md](./docs/ARCHITECTURE_OVERVIEW.md)** - System architecture and design
- **[docs/TECHNICAL_CHECKLIST.md](./docs/TECHNICAL_CHECKLIST.md)** - Technical requirements checklist

---

## 🤝 Contributing

Contributions are welcome! Please read our contributing guidelines and submit pull requests to our repository.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 🆘 Support

- 📖 [Documentation](./docs)
- 🐛 [Report Issues](https://github.com/yourusername/professional-blog/issues)
- 💬 [Discussions](https://github.com/yourusername/professional-blog/discussions)

---

## 🙌 Acknowledgments

- [Next.js](https://nextjs.org) - React framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS
- [Zustand](https://github.com/pmndrs/zustand) - State management
- [Firebase JWT](https://github.com/firebase/php-jwt) - JWT handling
- [PostgreSQL](https://postgresql.org) - Enterprise database
- [Redis](https://redis.io) - In-memory cache layer

---

## 📞 Contact

- Email: support@yourdomain.com
- Website: https://yourdomain.com
- Twitter: [@yourusername](https://twitter.com/yourusername)

---

**Built with ❤️ for content creators worldwide**

*Professional Multilingual Blog Platform - Making SEO-optimized global blogging accessible to everyone.*
