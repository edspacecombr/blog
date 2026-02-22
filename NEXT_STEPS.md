# 🚀 Próximos Passos - Phase 1: Content Management

**Data:** 22 de Fevereiro de 2026  
**Status Atual:** Phase 0 ✅ Completo (PostgreSQL + Redis)  
**Próxima Fase:** Phase 1 🔄 Pronta para Iniciar  
**Estimativa:** 2 semanas

**Stack Utilizado:**
- Backend: PHP 8.1
- Database: PostgreSQL 13.0+
- Cache: Redis 6.0+
- Frontend: Next.js 14

---

## 📋 O Que Fazer Agora

### 1️⃣ Instalação de Pré-requisitos

```bash
# PostgreSQL 13+
# macOS
brew install postgresql@13

# Ubuntu/Debian
sudo apt-get install postgresql-13

# Redis 6+
# macOS
brew install redis

# Ubuntu/Debian
sudo apt-get install redis-server
```

### 2️⃣ Setup do Banco de Dados (PostgreSQL)

```bash
# Conectar ao PostgreSQL
sudo -u postgres psql

# Criar database
CREATE DATABASE blog_platform 
  ENCODING 'UTF8' 
  LC_COLLATE 'en_US.UTF-8' 
  LC_CTYPE 'en_US.UTF-8';

# Criar usuário
CREATE USER blog_user WITH PASSWORD 'sua_senha_segura';

# Conceder privilégios
GRANT ALL PRIVILEGES ON DATABASE blog_platform TO blog_user;

# Conectar ao banco
\c blog_platform

# Conceder privilégios no schema
GRANT ALL ON SCHEMA public TO blog_user;

# Sair
\q
```

### 3️⃣ Validação da Fundação

Antes de começar Phase 1, execute os seguintes testes:

```bash
# Terminal 1: Redis
redis-server

# Terminal 2: Backend
cd backend
cp .env.example .env
# Editar .env com credenciais PostgreSQL e Redis
composer install
php -S localhost:8000 -t public

# Terminal 3: Frontend
cd frontend
npm install
npm run dev

# Terminal 4: Testar API
curl http://localhost:8000/api/v1/health
# Esperado: {"status":"ok","timestamp":"..."}
```

### 4️⃣ Rodar Migrations (PostgreSQL)

```bash
# No diretório backend
php -r "
  require 'vendor/autoload.php';
  \$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
  \$dotenv->load();
  \$migration = new \App\Database\Migration();
  \$migration->run();
"

# Ou manualmente em psql
psql -h localhost -U blog_user -d blog_platform
# \i docs/DATABASE_SCHEMA.md (não direto, converter para SQL)
```

### 5️⃣ Testar Autenticação

```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","name":"Test User","password":"pass123"}'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"pass123"}'

# Verificar token
curl -X GET http://localhost:8000/api/v1/auth/validate \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Testar Frontend
# Acessar http://localhost:3000/login
```

### 6️⃣ Testar Cache Redis

```bash
# Verificar conexão
redis-cli ping
# Esperado: PONG

# Monitorar cache em tempo real (em outro terminal)
redis-cli MONITOR

# Testar CacheService (criar script teste)
php -r "
  require 'vendor/autoload.php';
  \$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
  \$dotenv->load();
  \$cache = new \App\Cache\CacheService('blog:');
  \$cache->set('test', ['data' => 'value'], 3600);
  var_dump(\$cache->get('test'));
"
```

---

## 🎯 Phase 1: Content Management Tasks

### Priority 1: Core Post Management (Semana 1)

#### 1.1 Post Model & Database
```php
// backend/src/Models/Post.php
- Create Post entity
- Implement validation
- Add multilingual support
- Use PostgreSQL JSONB for metadata
```

**Files to Create:**
- `backend/src/Models/Post.php`
- `backend/src/Models/Category.php`
- `backend/src/Models/PostCategory.php`

#### 1.2 Post API Endpoints
```
POST   /api/v1/posts           → Create new post
GET    /api/v1/posts           → List posts (paginated, cached)
GET    /api/v1/posts/{id}      → Get single post (cached)
PUT    /api/v1/posts/{id}      → Update post
DELETE /api/v1/posts/{id}      → Delete post
POST   /api/v1/posts/{id}/publish → Publish post
```

**Cache Keys (Redis):**
- `blog:post:{id}` - TTL 1h
- `blog:posts:lang:{lang}` - TTL 30min
- `blog:posts:author:{author_id}` - TTL 30min

**Files to Create:**
- `backend/src/Controllers/PostController.php`
- `backend/src/Services/PostService.php`
- `backend/src/Validators/PostValidator.php`

#### 1.3 Frontend Post Management
- Post list page with pagination
- Post editor page
- Post preview
- Draft/Published status
- Uses cached API responses

**Files to Create:**
- `frontend/src/pages/posts/index.tsx`
- `frontend/src/pages/posts/[id].tsx`
- `frontend/src/pages/posts/new.tsx`
- `frontend/src/components/PostEditor.tsx`
- `frontend/src/components/PostList.tsx`

### Priority 2: Category Management (Semana 1)

#### 2.1 Category API
```
GET    /api/v1/categories          (cached)
POST   /api/v1/categories
PUT    /api/v1/categories/{id}
DELETE /api/v1/categories/{id}
```

**Cache Keys (Redis):**
- `blog:categories:lang:{lang}` - TTL 1h
- `blog:categories:hierarchical:{lang}` - TTL 1h

#### 2.2 Frontend Category Management
- Category list
- Category CRUD pages
- Category selector for posts

**Files to Create:**
- `backend/src/Controllers/CategoryController.php`
- `backend/src/Services/CategoryService.php`
- `frontend/src/pages/categories/index.tsx`
- `frontend/src/components/CategoryForm.tsx`

### Priority 3: Media Management (Semana 2)

#### 3.1 Media Upload API
```
POST   /api/v1/media/upload
GET    /api/v1/media               (cached)
GET    /api/v1/media/{id}
DELETE /api/v1/media/{id}
```

**Features:**
- Image upload
- File size validation
- Format validation (jpg, png, webp)
- Image optimization
- Store paths in PostgreSQL

**Cache Keys (Redis):**
- `blog:media:{id}` - TTL 24h
- `blog:media:list:{user_id}` - TTL 1h

#### 3.2 Frontend Media Library
- Media upload interface
- Media browser
- Image optimization display

**Files to Create:**
- `backend/src/Controllers/MediaController.php`
- `backend/src/Services/MediaService.php`
- `backend/src/Validators/MediaValidator.php`
- `frontend/src/pages/media/index.tsx`
- `frontend/src/components/MediaUpload.tsx`

### Priority 4: Page Management (Semana 2)

#### 4.1 Page API
```
POST   /api/v1/pages
GET    /api/v1/pages               (cached)
GET    /api/v1/pages/{slug}        (cached)
PUT    /api/v1/pages/{id}
DELETE /api/v1/pages/{id}
```

**Cache Keys (Redis):**
- `blog:page:{slug}:{lang}` - TTL 24h
- `blog:pages:list:{lang}` - TTL 1h

#### 4.2 Frontend Page Management
- Page editor
- Page list
- Essential pages generator (Privacy, Terms, About)

**Files to Create:**
- `backend/src/Controllers/PageController.php`
- `backend/src/Services/PageService.php`
- `frontend/src/pages/pages/index.tsx`
- `frontend/src/pages/pages/new.tsx`
- `frontend/src/components/PageEditor.tsx`

---

## 📋 Implementation Checklist

### Backend Tasks

```
[ ] Create PostController with CRUD endpoints
[ ] Create PostService with business logic
[ ] Create CategoryController
[ ] Create CategoryService
[ ] Create MediaController with upload handling
[ ] Create MediaService
[ ] Create PageController
[ ] Create PageService
[ ] Add request validation for all endpoints
[ ] Add error handling for all services
[ ] Implement pagination for list endpoints
[ ] Add search functionality
[ ] Add status workflow (draft → publish → archive)
[ ] Add content versioning
[ ] Add audit logging
```

### Frontend Tasks

```
[ ] Create PostList page with pagination
[ ] Create PostEditor page
[ ] Create CategoryManagement page
[ ] Create MediaLibrary page
[ ] Create PageEditor page
[ ] Build navigation for admin panel
[ ] Add form validation components
[ ] Create reusable table component
[ ] Add loading states
[ ] Add error handling
[ ] Add success notifications
[ ] Create responsive mobile layouts
```

### Database Tasks

```
[ ] Verify all 11 tables created
[ ] Test foreign key constraints
[ ] Verify indexes are working
[ ] Create test data
[ ] Verify multilingual queries work
[ ] Test full-text search
```

### Testing Tasks

```
[ ] Write unit tests for services
[ ] Write integration tests for API endpoints
[ ] Write E2E tests for user flows
[ ] Performance testing
[ ] Security testing
```

---

## 🔧 Technical Considerations

### Database Improvements Needed
1. Add slug uniqueness constraint per language
2. Add content versioning tables (for future)
3. Add revision history tracking
4. Optimize queries with proper indexes

### Backend Patterns to Follow
1. Repository pattern for database queries
2. Validation service for input sanitization
3. Event system for post publish/unpublish
4. Caching layer for frequently accessed data

### Frontend Patterns to Follow
1. Custom hooks for API calls
2. Context API for admin state
3. Error boundary components
4. Loading skeleton components
5. Optimistic updates for better UX

### Security Checklist for Phase 1
- [ ] Validate file uploads (type, size, virus scan)
- [ ] Sanitize content input
- [ ] Implement CSRF protection
- [ ] Add rate limiting
- [ ] Implement request signing
- [ ] Add audit logging

---

## 📚 Resources & References

### Database Queries Examples (Phase 1 - PostgreSQL)

```sql
-- Get published posts by language
SELECT * FROM posts 
WHERE language = 'en' AND status = 'published'
ORDER BY published_at DESC
LIMIT 10;

-- Get posts with categories (PostgreSQL string_agg)
SELECT p.id, p.title, p.slug, string_agg(c.name, ', ') as categories
FROM posts p
LEFT JOIN post_category pc ON p.id = pc.post_id
LEFT JOIN categories c ON c.id = pc.category_id
WHERE p.language = 'en'
GROUP BY p.id;

-- Multilingual posts (get all versions)
SELECT DISTINCT p.id, p.language, p.title, p.slug
FROM posts p
WHERE p.id IN (
  SELECT id FROM posts WHERE slug = 'my-post'
);

-- Full-text search using PostgreSQL GIN index
SELECT * FROM posts 
WHERE to_tsvector('english', title || ' ' || COALESCE(content, '')) 
  @@ plainto_tsquery('english', 'search term')
AND language = 'en'
ORDER BY published_at DESC;

-- Get cached vs fresh posts count
SELECT COUNT(*) FROM posts WHERE status = 'published' AND language = 'en';
-- Cache this in Redis: blog:posts:count:en
```

### Redis Cache Integration (Phase 1)

```php
// In PostService
use App\Cache\CacheService;

class PostService {
    private CacheService $cache;
    
    public function __construct() {
        $this->cache = new CacheService('blog:');
    }
    
    public function getPosts($language, $page = 1) {
        $cacheKey = "posts:lang:{$language}:page:{$page}";
        
        // Try cache first
        if ($this->cache->exists($cacheKey)) {
            return $this->cache->get($cacheKey);
        }
        
        // Query database if not cached
        $posts = $this->queryPostsFromDb($language, $page);
        
        // Cache results (30 min TTL)
        $this->cache->set($cacheKey, $posts, 1800);
        
        return $posts;
    }
    
    public function createPost($data) {
        $post = $this->saveToDb($data);
        
        // Invalidate related caches
        $this->cache->delete("posts:lang:{$data['language']}:page:1");
        $this->cache->delete("posts:lang:{$data['language']}:page:2");
        // ... invalidate other pages
        
        // Cache individual post (1 hour TTL)
        $this->cache->set("post:{$post['id']}", $post, 3600);
        
        return $post;
    }
}
```

### API Response Format Example

```json
{
  "success": true,
  "message": "Post created successfully",
  "data": {
    "id": 1,
    "title": "My First Post",
    "slug": "my-first-post",
    "language": "en",
    "status": "published",
    "author": {
      "id": 1,
      "name": "John Doe"
    },
    "categories": [1, 2, 3],
    "published_at": "2024-02-22T10:00:00Z"
  }
}
```

---

## 🚀 How to Structure Phase 1

### Week 1: Posts & Categories (with PostgreSQL + Redis)
- Day 1-2: Database optimization & Post CRUD backend
  * Use PostgreSQL JSONB for metadata
  * Implement CacheService for post caching
- Day 3-4: Post frontend (list, editor, preview)
  * Consume cached API responses
- Day 5: Category backend & frontend
  * Cache categories in Redis
- Day 6-7: Integration & testing
  * Test cache invalidation
  * Test PostgreSQL queries

### Week 2: Media & Pages (with caching)
- Day 1-2: Media upload backend
  * Store in PostgreSQL
  * Cache file paths
- Day 3-4: Media library frontend
  * Show cached media list
- Day 5-6: Page management backend & frontend
  * Cache static pages (24h TTL)
- Day 7: Admin dashboard completion & testing
  * Monitor Redis cache performance

---

## 📊 Success Criteria for Phase 1

✅ **Backend Completed When:**
- All CRUD endpoints working
- Input validation implemented
- Error handling in place
- Pagination working (with Redis caching)
- PostgreSQL full-text search working
- Cache invalidation strategies implemented
- JSONB queries for multilingual data working
- All tests passing (database + cache)

✅ **Frontend Completed When:**
- All admin pages functional
- Forms validate and submit
- Loading states display properly
- Errors handled gracefully
- Mobile responsive
- Cache strategies implemented (stale-while-revalidate)

✅ **Cache & Database Completed When:**
- Redis cache hit rate > 80%
- PostgreSQL queries optimized
- GIN indexes working for full-text search
- Cache invalidation working correctly
- No cache stale data issues
- Memory usage acceptable on both services

---

## 🔄 Git Workflow for Phase 1

```bash
# Create feature branch from Phase 0
git checkout -b feature/posts-management

# Work on features
git add .
git commit -m "feat: Add post CRUD operations"

# Push and create PR for review
git push origin feature/posts-management

# After review, merge to develop
git checkout develop
git merge feature/posts-management
```

---

## 📝 Definition of Done - Phase 1

A feature is considered "Done" when:
- ✅ Code written and tested
- ✅ PR reviewed and approved
- ✅ Tests passing
- ✅ No console errors/warnings
- ✅ Documentation updated
- ✅ Merged to main branch

---

## 🎯 Post Phase 1

Once Phase 1 is complete:

1. **Phase 2: Advanced Features**
   - Visual editor (GrapesJS)
   - Content scheduling
   - Comment system
   - Newsletter integration

2. **Phase 3: SEO & Monetization**
   - Sitemap generation
   - AdSense integration
   - Analytics setup
   - Performance optimization

3. **Phase 4: Deployment**
   - Production setup
   - CI/CD pipeline
   - Monitoring & alerts
   - Documentation finalization

---

## 💡 Tips for Success

1. **Start Small:** Get one feature working completely before moving to next
2. **Test Early:** Write tests as you code
3. **Document:** Update docs as features are added
4. **Review:** Get code reviewed before merging
5. **Backup:** Commit often
6. **Communicate:** Update team on progress

---

## ❓ Common Questions

**Q: Should I use an ORM for Phase 1?**
A: No, keep PDO for now. ORM can be added in Phase 3 if needed.

**Q: How to handle image optimization?**
A: Use GD library or ImageMagick via system calls for Phase 1. CDN can be added later.

**Q: Should I add caching?**
A: No, focus on correct functionality first. Add Redis caching in Phase 3.

**Q: What about background jobs?**
A: Use basic PHP for now. Add queue system (RabbitMQ/Redis) in Phase 4.

---

## 📞 Support

For questions or issues:
1. Check documentation in `docs/`
2. Review TECHNICAL_CHECKLIST.md
3. Check commit history for examples
4. Test in development first

---

**Ready to start Phase 1? Let's build! 🚀**

Next milestone: Posts & Categories (End of Week 1)

Created: 22 de Fevereiro de 2026
