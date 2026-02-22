#!/bin/bash

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  🚀 PHP 8.1 Environment Setup for NEXT_STEPS Validation   ${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}\n"

# Function to check if command exists
check_command() {
    if command -v $1 &> /dev/null; then
        echo -e "${GREEN}✓${NC} $1 found"
        return 0
    else
        echo -e "${RED}✗${NC} $1 not found"
        return 1
    fi
}

# Function to install package
install_package() {
    echo -e "${YELLOW}⚡${NC} Installing $1..."
    sudo apt-get install -y $1 > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $1 installed successfully"
        return 0
    else
        echo -e "${RED}✗${NC} Failed to install $1"
        return 1
    fi
}

echo -e "${BLUE}📋 Step 1: Checking Current System${NC}\n"

# Check current installation
echo "Checking installed tools..."
check_command php
check_command composer
check_command node
check_command npm
check_command psql
check_command redis-cli

echo ""
echo -e "${BLUE}📋 Step 2: Updating Package Manager${NC}\n"
echo -e "${YELLOW}⚡${NC} Running apt update..."
sudo apt-get update -qq > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Package manager updated"
else
    echo -e "${RED}✗${NC} Failed to update package manager"
    exit 1
fi

echo ""
echo -e "${BLUE}📋 Step 3: Installing PHP 8.1 & Extensions${NC}\n"

# Install PHP 8.1 and extensions
PACKAGES="php8.1-cli php8.1-pdo php8.1-pgsql php8.1-redis php8.1-json php8.1-curl php8.1-mbstring php8.1-xml php8.1-fpm"

for package in $PACKAGES; do
    if check_command ${package%-*}; then
        echo -e "${YELLOW}⚠ ${NC} ${package} appears to be installed, skipping..."
    else
        install_package $package
    fi
done

echo ""
echo -e "${BLUE}📋 Step 4: Installing Composer${NC}\n"

if check_command composer; then
    echo -e "${YELLOW}⚠ ${NC} Composer already installed, skipping..."
else
    install_package composer
fi

echo ""
echo -e "${BLUE}📋 Step 5: Installing Node.js & npm${NC}\n"

if check_command node && check_command npm; then
    echo -e "${YELLOW}⚠ ${NC} Node.js and npm already installed, skipping..."
else
    install_package nodejs npm
fi

echo ""
echo -e "${BLUE}📋 Step 6: Verifying Installation${NC}\n"

echo "Verifying versions:"
echo -e "${BLUE}PHP:${NC}"
php -v 2>/dev/null | head -1

echo -e "${BLUE}Composer:${NC}"
composer --version 2>/dev/null | head -1

echo -e "${BLUE}Node.js:${NC}"
node -v 2>/dev/null

echo -e "${BLUE}npm:${NC}"
npm -v 2>/dev/null

echo ""
echo -e "${BLUE}📋 Step 7: Checking PHP Extensions${NC}\n"

# Check critical extensions
echo "Checking PHP extensions..."
php -m 2>/dev/null | grep -i pdo && echo -e "${GREEN}✓${NC} PDO extension found" || echo -e "${RED}✗${NC} PDO extension not found"
php -m 2>/dev/null | grep -i pgsql && echo -e "${GREEN}✓${NC} PostgreSQL extension found" || echo -e "${RED}✗${NC} PostgreSQL extension not found"
php -m 2>/dev/null | grep -i redis && echo -e "${GREEN}✓${NC} Redis extension found" || echo -e "${RED}✗${NC} Redis extension not found"

echo ""
echo -e "${BLUE}📋 Step 8: Setup Project Dependencies${NC}\n"

cd /home/edblack/projetos/blog

if [ -f "backend/composer.json" ]; then
    echo -e "${YELLOW}⚡${NC} Installing backend dependencies..."
    cd backend
    composer install --no-interaction 2>&1 | tail -5
    cd ..
    echo -e "${GREEN}✓${NC} Backend dependencies installed"
else
    echo -e "${RED}✗${NC} backend/composer.json not found"
fi

if [ -f "frontend/package.json" ]; then
    echo -e "${YELLOW}⚡${NC} Installing frontend dependencies..."
    cd frontend
    npm install --quiet 2>&1 | tail -3
    cd ..
    echo -e "${GREEN}✓${NC} Frontend dependencies installed"
else
    echo -e "${RED}✗${NC} frontend/package.json not found"
fi

echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}✅ Environment Setup Complete!${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}\n"

echo -e "${YELLOW}ℹ️  NEXT STEPS:${NC}"
echo ""
echo "1. 📱 Provide PostgreSQL Connection Details:"
echo "   • Host: (default: localhost)"
echo "   • Port: (default: 5432)"
echo "   • Database: (default: blog_platform)"
echo "   • Username: (default: blog_user)"
echo "   • Password: (your PostgreSQL password)"
echo ""
echo "2. 📱 Provide Redis Connection Details:"
echo "   • Host: (default: localhost)"
echo "   • Port: (default: 6379)"
echo "   • Password: (if required)"
echo ""
echo "3. 🔧 Configure backend/.env with the credentials"
echo ""
echo "4. 🗄️  Run database migrations:"
echo "   cd backend"
echo "   php -r \"require 'vendor/autoload.php'; \$m = new \App\Database\Migration(); \$m->run();\""
echo ""
echo "5. 🚀 Start the backend server:"
echo "   cd backend"
echo "   php -S localhost:8000 -t public"
echo ""
echo "6. 🌐 Start the frontend (in another terminal):"
echo "   cd frontend"
echo "   npm run dev"
echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════════════${NC}\n"
