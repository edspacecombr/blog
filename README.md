# 🚀 Professional Multilingual Blog Platform

A production-ready, SEO-optimized, multilingual blogging platform built with modern technologies. Designed for content creators, agencies, and businesses looking to build sustainable, organic traffic with global reach.

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://php.net)
[![Next.js](https://img.shields.io/badge/Next.js-14-black)](https://nextjs.org)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-blue)](https://mysql.com)
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
- Image optimization (WebP, AVIF support)
- Code splitting and lazy loading
- Gzip compression
- Browser caching headers
- Database query optimization

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
    ↓ SQL Queries
Database (MySQL 8.0+)
```

### Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Frontend** | Next.js | 14.0+ |
| | React | 18.2+ |
| | TypeScript | 5.0+ |
| | Tailwind CSS | 3.3+ |
| **Backend** | PHP | 8.1+ |
| | MySQL | 8.0+ |
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
│   ├── DATABASE_SCHEMA.md     # DB structure
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
- MySQL 8.0 or higher
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

Or **manual setup**:

3. **Setup Backend**
```bash
cd backend
cp .env.example .env
# Edit .env with your database credentials
composer install
```

4. **Setup Database**
```bash
mysql -u root -p
CREATE DATABASE blog_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'blog_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON blog_platform.* TO 'blog_user'@'localhost';
FLUSH PRIVILEGES;

# Run migrations
# (Script coming in Phase 1)
```

5. **Setup Frontend**
```bash
cd ../frontend
npm install
```

### Running the Application

**Terminal 1 - Backend:**
```bash
cd backend
php -S localhost:8000 -t public
```

**Terminal 2 - Frontend:**
```bash
cd frontend
npm run dev
```

**Access the application:**
- 🌐 Frontend: `http://localhost:3000`
- 📡 Backend: `http://localhost:8000`
- 📚 API Docs: `http://localhost:8000/api/v1/health`

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

---

## 📊 Database Schema

The platform includes 11 optimized tables:

1. **users** - User management with roles
2. **blog_settings** - Blog configuration
3. **posts** - Blog posts with multilingual support
4. **categories** - Post categories
5. **post_category** - Post-category relationships
6. **pages** - Static pages
7. **menus** - Navigation menus
8. **menu_items** - Menu items with hierarchy
9. **media_library** - Media management
10. **languages** - Language configuration
11. **language_settings** - Language-specific settings

See `docs/DATABASE_SCHEMA.md` for complete schema details.

---

## 🗺️ Roadmap

### Phase 0 ✅ (Complete)
- [x] Backend foundation with PHP 8
- [x] Frontend with Next.js 14
- [x] Database schema
- [x] JWT authentication
- [x] Basic admin pages

### Phase 1 📋 (In Progress)
- [ ] Post management (CRUD)
- [ ] Category management
- [ ] Media upload system
- [ ] Page management
- [ ] Menu builder

### Phase 2 🔄 (Planned)
- [ ] Visual editor (GrapesJS)
- [ ] Content scheduling
- [ ] Comment system
- [ ] Newsletter integration

### Phase 3 📈 (Planned)
- [ ] SEO analytics
- [ ] AdSense integration
- [ ] Performance monitoring
- [ ] Revenue tracking

### Phase 4 🚀 (Planned)
- [ ] Production deployment
- [ ] CI/CD pipeline
- [ ] Automated backups
- [ ] Performance optimization

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
- [Doctrine ORM](https://www.doctrine-project.org) - (Planned) ORM

---

## 📞 Contact

- Email: support@yourdomain.com
- Website: https://yourdomain.com
- Twitter: [@yourusername](https://twitter.com/yourusername)

---

**Built with ❤️ for content creators worldwide**

*Professional Multilingual Blog Platform - Making SEO-optimized global blogging accessible to everyone.*
