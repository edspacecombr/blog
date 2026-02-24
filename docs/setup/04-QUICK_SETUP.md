# ⚡ Quick Setup - 5 Minutes

**Status:** Phase 0 Foundation Complete  
**Need to do:** Install PHP 8.1 + dependencies + verify connections

---

## 🏃 Quick Commands

### 1. Install PHP 8.1 & Tools

```bash
sudo apt-get update
sudo apt-get install -y \
  php8.1-cli php8.1-pdo php8.1-pgsql php8.1-redis \
  php8.1-json php8.1-curl php8.1-mbstring php8.1-xml \
  composer postgresql-client redis-tools
```

### 2. Install Project Dependencies

```bash
cd /home/edblack/projetos/blog/backend && composer install
cd ../frontend && npm install
```

### 3. Verify Setup

```bash
cd /home/edblack/projetos/blog
./validate-setup.sh
./test-connections.sh
```

### 4. Start Services (3 Terminals)

**Terminal 1:**
```bash
cd /home/edblack/projetos/blog/backend
php -S localhost:8000 -t public
```

**Terminal 2:**
```bash
cd /home/edblack/projetos/blog/frontend
npm run dev
```

**Terminal 3 (optional):**
```bash
redis-cli -a "<USE_ENV>" MONITOR
```

### 5. Access

- Frontend: http://localhost:3000
- Backend: http://localhost:8000
- Health: http://localhost:8000/api/v1/health

---

## 📋 Credentials

**PostgreSQL:**
- Host: localhost:5432
- User: blog_admin
- Pass: <USE_ENV>
- DB: blog_platform

**Redis:**
- Host: localhost:6379
- Pass: <USE_ENV>

---

## 📖 Documentation

- **Quick Start:** README.md
- **Complete Guide:** SETUP_COMPLETE_GUIDE.md
- **Troubleshooting:** INSTALLATION_CHECKLIST.md
- **Status Report:** EXECUTIVE_STATUS.md
- **Next Phase:** NEXT_STEPS.md

---

## ✅ Done!

You're ready for Phase 1: Content Management

