# ⚡ Phase 0 Complete - Quick Start Reference

**Status:** ✅ ALL SYSTEMS OPERATIONAL  
**Last Updated:** 22 de Fevereiro de 2026  
**Version:** 0.1.0

---

## 🚀 Start the Project (3 terminals)

### Terminal 1: Backend API
```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public
```
✓ Access: http://localhost:8000/api/v1/health

### Terminal 2: Frontend
```bash
cd /home/edblack/projetos/blog/frontend
npm run dev
```
✓ Access: http://localhost:3000

### Terminal 3: Optional - Monitor Redis
```bash
redis-cli -h localhost -p 6379 -a "<USE_ENV>" MONITOR
```

---

## 📍 Application URLs

| Service | URL | Purpose |
|---------|-----|---------|
| Frontend | http://localhost:3000 | Main app |
| Backend API | http://localhost:8000 | API server |
| Health Check | http://localhost:8000/api/v1/health | API status |
| Login | http://localhost:3000/login | User login |
| Register | http://localhost:3000/register | User registration |
| Dashboard | http://localhost:3000/dashboard | Admin panel |

---

## 🔐 Database Credentials

### PostgreSQL
```
Host: localhost
Port: 5432
Database: blog_platform
User: blog_admin
Password: <USE_ENV>
```

### Redis
```
Host: localhost
Port: 6379
Password: <USE_ENV>
```

---

## ✅ Verify Everything Is Working

```bash
# PostgreSQL
psql -h localhost -U blog_admin -d blog_platform

# Redis
redis-cli -h localhost -p 6379 -a "<USE_ENV>" PING

# Backend API
curl http://localhost:8000/api/v1/health

# Frontend
curl http://localhost:3000 | head -20
```

---

## 📦 Project Structure

```
/home/edblack/projetos/blog/
├── backend/                    # PHP 8.3 API
│   ├── public/index.php       # Entry point
│   ├── src/                   # Source code
│   ├── .env                   # Configuration ✓
│   └── composer.json          # Dependencies ✓
│
├── frontend/                   # Next.js 14 App
│   ├── src/app/              # App Router pages
│   ├── package.json          # npm dependencies ✓
│   ├── .env.local            # Configuration ✓
│   └── tsconfig.json         # TypeScript ✓
│
├── docs/                      # Documentation
├── PHASE_0_VALIDATION_COMPLETE.md
├── EXECUTION_SUMMARY_PHASE0.md
└── QUICKSTART_PHASE0_COMPLETE.md (this file)
```

---

## 🔧 Common Commands

### Backend (Composer)
```bash
cd /home/edblack/projetos/blog

# Install dependencies
php ./composer.phar install

# Update dependencies
php ./composer.phar update

# Add a package
php ./composer.phar require vendor/package
```

### Frontend (npm)
```bash
cd /home/edblack/projetos/blog/frontend

# Install dependencies
npm install

# Start dev server
npm run dev

# Build for production
npm run build

# Start production server
npm start

# Lint code
npm run lint

# Type check
npm run type-check
```

---

## 🧪 Test Connections

```bash
# Test PHP + PostgreSQL
php -r "
\$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=blog_platform;sslmode=prefer', 'blog_admin', '<USE_ENV>');
echo 'PostgreSQL: OK' . PHP_EOL;
"

# Test PHP + Redis
php -r "
\$r = new Redis();
\$r->connect('localhost', 6379, 5);
\$r->auth('<USE_ENV>');
\$r->ping();
echo 'Redis: OK' . PHP_EOL;
"
```

---

## 📊 System Status

### Environment
- ✅ PHP 8.3.6
- ✅ Node.js v22.21.1
- ✅ npm 10.9.4
- ✅ Composer 2.9.5

### Services
- ✅ Backend API (port 8000)
- ✅ Frontend Dev Server (port 3000)
- ✅ PostgreSQL (port 5432)
- ✅ Redis (port 6379)

### Dependencies
- ✅ Backend packages: 17 core + dev
- ✅ Frontend packages: 350+
- ✅ Total: 367+ packages

---

## 🎯 Next: Phase 1

**Phase 1: Content Management (3 weeks)**

Read these files for Phase 1 planning:
1. `NEXT_STEPS.md` - Detailed Phase 1 tasks
2. `docs/4-PLANO_DE_EXECUCAO.md` - Full execution plan
3. `PHASE_0_VALIDATION_COMPLETE.md` - Detailed validation

---

## 🛑 Stop Services

```bash
# Kill backend
pkill -f "php -S localhost:8000"

# Kill frontend
pkill -f "npm run dev"

# Kill all node processes (careful!)
pkill -f "node"
```

---

## 🔄 Restart Everything

```bash
# Terminal 1
pkill -f "php -S localhost:8000"
cd /home/edblack/projetos/blog/backend && php -S localhost:8000 -t public

# Terminal 2
pkill -f "npm run dev"
cd /home/edblack/projetos/blog/frontend && npm run dev
```

---

## 📱 API Response Format

```json
{
  "status": "ok",
  "timestamp": "2026-02-22T21:57:04+00:00"
}
```

---

## 🎓 File Locations

### Configuration
- Backend Config: `/home/edblack/projetos/blog/backend/.env`
- Frontend Config: `/home/edblack/projetos/blog/frontend/.env.local`
- Composer: `/home/edblack/projetos/blog/composer.phar`

### Logs
- Backend: Check terminal output
- Frontend: Check terminal output
- System: `/tmp/backend.log`, `/tmp/frontend.log`

### Dependencies
- Backend Packages: `/home/edblack/projetos/blog/backend/vendor/`
- Frontend Packages: `/home/edblack/projetos/blog/frontend/node_modules/`

---

## ⚠️ Troubleshooting

### Backend won't start
```bash
# Check if port 8000 is in use
lsof -i :8000

# Kill process using port
kill -9 <PID>

# Try again
cd backend && php -S localhost:8000 -t public
```

### Frontend won't start
```bash
# Clear cache
rm -rf /home/edblack/projetos/blog/frontend/.next

# Reinstall
cd frontend && npm install

# Try again
npm run dev
```

### Database connection fails
```bash
# Test connection
psql -h localhost -U blog_admin -d blog_platform

# Check if PostgreSQL is running
docker ps | grep postgres
```

### Redis connection fails
```bash
# Test connection
redis-cli -h localhost -p 6379 -a "<USE_ENV>" PING

# Check if Redis is running
docker ps | grep redis
```

---

## 📚 Documentation

Quick links to key docs:

1. **Setup Complete** → `SETUP_COMPLETE_GUIDE.md`
2. **Next Steps** → `NEXT_STEPS.md`
3. **Validation Results** → `PHASE_0_VALIDATION_COMPLETE.md`
4. **Execution Summary** → `EXECUTION_SUMMARY_PHASE0.md`
5. **Execution Plan** → `docs/4-PLANO_DE_EXECUCAO.md`
6. **Database Schema** → `docs/DATABASE_SCHEMA.md`
7. **README** → `README.md`

---

## 🎉 You're All Set!

Everything is configured and ready to go. 

✅ Backend running  
✅ Frontend running  
✅ PostgreSQL connected  
✅ Redis connected  

**Start building Phase 1!** 🚀

---

**Last Updated:** 22 de Fevereiro de 2026  
**Version:** 0.1.0 - Phase 0 Complete
