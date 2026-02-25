#!/bin/bash

# Frontend Deployment Script for VPS (PM2 + Nginx)
# Usage: ./deploy-frontend.sh [environment] [host] [user] [path]

set -e

ENVIRONMENT=${1:-production}
DEPLOY_HOST=${2:-your-server.com}
DEPLOY_USER=${3:-deploy}
DEPLOY_PATH=${4:-/var/www/blog-frontend}

echo "==================================================="
echo "Blog Frontend Deployment (Next.js + PM2)"
echo "==================================================="
echo "Environment: $ENVIRONMENT"
echo "Host: $DEPLOY_HOST"
echo "User: $DEPLOY_USER"
echo "Path: $DEPLOY_PATH"
echo "==================================================="

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check if rsync is available
if ! command -v rsync &> /dev/null; then
    echo -e "${RED}Error: rsync is not installed${NC}"
    exit 1
fi

# Create backup
echo -e "${YELLOW}Creating backup...${NC}"
BACKUP_NAME="blog-frontend-backup-$(date +%Y%m%d_%H%M%S).tar.gz"
ssh $DEPLOY_USER@$DEPLOY_HOST "cd $DEPLOY_PATH && tar -czf /backups/$BACKUP_NAME . || true"
echo -e "${GREEN}Backup created: $BACKUP_NAME${NC}"

# Sync files
echo -e "${YELLOW}Syncing files...${NC}"
rsync -avz --delete \
    --exclude='node_modules' \
    --exclude='.next' \
    --exclude='.env.local' \
    --exclude='.git' \
    ./frontend/ \
    $DEPLOY_USER@$DEPLOY_HOST:$DEPLOY_PATH/

echo -e "${GREEN}Files synced${NC}"

# Install dependencies and build
echo -e "${YELLOW}Installing dependencies and building...${NC}"
ssh $DEPLOY_USER@$DEPLOY_HOST << EOFSH
    cd $DEPLOY_PATH
    npm ci
    npm run build
EOFSH
echo -e "${GREEN}Build completed${NC}"

# Restart PM2 app
echo -e "${YELLOW}Restarting PM2 application...${NC}"
ssh $DEPLOY_USER@$DEPLOY_HOST << EOFSH
    cd $DEPLOY_PATH
    pm2 delete blog-frontend || true
    pm2 start npm --name blog-frontend -- start
    pm2 save
EOFSH
echo -e "${GREEN}PM2 application restarted${NC}"

# Test endpoint
echo -e "${YELLOW}Testing deployment...${NC}"
sleep 2
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://your-domain.com/)
if [ "$HTTP_CODE" -eq 200 ]; then
    echo -e "${GREEN}Deployment verified - HTTP $HTTP_CODE${NC}"
else
    echo -e "${RED}Warning: HTTP $HTTP_CODE received${NC}"
fi

echo ""
echo -e "${GREEN}==================================================="
echo "Frontend deployment completed!"
echo "===================================================${NC}"
