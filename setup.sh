#!/usr/bin/env bash

# Professional Multilingual Blog - Phase 0 Setup Script
# This script automates the installation and setup of backend and frontend

set -e

echo "🚀 Professional Multilingual Blog - Phase 0 Setup"
echo "=================================================="

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check prerequisites
echo -e "\n${YELLOW}Checking prerequisites...${NC}"

if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP is not installed${NC}"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo -e "${RED}❌ Composer is not installed${NC}"
    exit 1
fi

if ! command -v node &> /dev/null; then
    echo -e "${RED}❌ Node.js is not installed${NC}"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    echo -e "${RED}❌ npm is not installed${NC}"
    exit 1
fi

if ! command -v mysql &> /dev/null; then
    echo -e "${YELLOW}⚠️  MySQL CLI is not installed (optional, you can set up database manually)${NC}"
fi

echo -e "${GREEN}✓ All prerequisites found${NC}"

# Backend setup
echo -e "\n${YELLOW}Setting up Backend...${NC}"

cd backend

if [ ! -f ".env" ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
    echo -e "${GREEN}✓ .env created${NC}"
else
    echo -e "${YELLOW}⚠️  .env already exists${NC}"
fi

echo "Installing PHP dependencies..."
composer install --no-interaction

echo -e "${GREEN}✓ Backend setup complete${NC}"

# Frontend setup
echo -e "\n${YELLOW}Setting up Frontend...${NC}"

cd ../frontend

echo "Installing npm dependencies..."
npm install

echo -e "${GREEN}✓ Frontend setup complete${NC}"

# Summary
cd ..
echo -e "\n${GREEN}✅ Phase 0 Setup Complete!${NC}"
echo -e "\n${YELLOW}Next steps:${NC}"
echo ""
echo "1. Configure Backend Database:"
echo "   - Edit backend/.env with your database credentials"
echo "   - Run database migrations"
echo ""
echo "2. Start Backend:"
echo "   cd backend && php -S localhost:8000 -t public"
echo ""
echo "3. Start Frontend:"
echo "   cd frontend && npm run dev"
echo ""
echo "4. Access the application:"
echo "   - Frontend: http://localhost:3000"
echo "   - Backend: http://localhost:8000"
echo ""
echo "📚 Documentation:"
echo "   - Database Schema: docs/DATABASE_SCHEMA.md"
echo "   - Build Progress: docs/PHASE_0_BUILD.md"
