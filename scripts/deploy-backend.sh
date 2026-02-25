#!/bin/bash

# Backend Deployment Script for VPS or Shared Hosting
# Usage: ./deploy-backend.sh [environment] [host] [user] [path]

set -e

ENVIRONMENT=${1:-production}
DEPLOY_HOST=${2:-your-server.com}
DEPLOY_USER=${3:-deploy}
DEPLOY_PATH=${4:-/var/www/blog-api}

echo "==================================================="
echo "Blog Backend Deployment"
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
BACKUP_NAME="blog-api-backup-$(date +%Y%m%d_%H%M%S).tar.gz"
ssh $DEPLOY_USER@$DEPLOY_HOST "cd $DEPLOY_PATH && tar -czf /backups/$BACKUP_NAME . || true"
echo -e "${GREEN}Backup created: $BACKUP_NAME${NC}"

# Sync files
echo -e "${YELLOW}Syncing files...${NC}"
rsync -avz --delete \
    --exclude='vendor' \
    --exclude='.env' \
    --exclude='storage/logs' \
    --exclude='storage/uploads' \
    --exclude='.git' \
    ./backend/ \
    $DEPLOY_USER@$DEPLOY_HOST:$DEPLOY_PATH/

echo -e "${GREEN}Files synced${NC}"

# Install dependencies
echo -e "${YELLOW}Installing dependencies...${NC}"
ssh $DEPLOY_USER@$DEPLOY_HOST << EOFSH
    cd $DEPLOY_PATH
    composer install --no-dev --optimize-autoloader
EOFSH
echo -e "${GREEN}Dependencies installed${NC}"

# Run migrations
echo -e "${YELLOW}Running migrations...${NC}"
ssh $DEPLOY_USER@$DEPLOY_HOST << EOFSH
    cd $DEPLOY_PATH
    php migrate.php --force || true
EOFSH
echo -e "${GREEN}Migrations completed${NC}"

# Set permissions
echo -e "${YELLOW}Setting permissions...${NC}"
ssh $DEPLOY_USER@$DEPLOY_HOST << EOFSH
    cd $DEPLOY_PATH
    chmod -R 755 storage
    chmod -R 755 public
EOFSH
echo -e "${GREEN}Permissions set${NC}"

# Restart services
echo -e "${YELLOW}Restarting services...${NC}"
ssh $DEPLOY_USER@$DEPLOY_HOST << EOFSH
    sudo systemctl restart php-fpm || true
    sudo systemctl restart nginx || true
    sudo systemctl restart supervisor || true
EOFSH
echo -e "${GREEN}Services restarted${NC}"

echo ""
echo -e "${GREEN}==================================================="
echo "Deployment completed successfully!"
echo "===================================================${NC}"
