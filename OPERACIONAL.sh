#!/bin/bash

# SCRIPT OPERACIONAL - FASE 3
# Use este script para facilmente gerenciar o ambiente

set -e  # Exit on error

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BACKEND_DIR="$PROJECT_DIR/backend"
FRONTEND_DIR="$PROJECT_DIR/frontend"

echo "🚀 Blog Platform - Operacional Fase 3"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to start backend
start_backend() {
    echo -e "${YELLOW}Starting Backend API...${NC}"
    cd "$BACKEND_DIR"
    php -S localhost:8000 -t public &
    BACKEND_PID=$!
    echo -e "${GREEN}Backend started (PID: $BACKEND_PID)${NC}"
    echo "API: http://localhost:8000"
    echo "Health: http://localhost:8000/api/v1/health"
}

# Function to start frontend
start_frontend() {
    echo -e "${YELLOW}Starting Frontend Dev Server...${NC}"
    cd "$FRONTEND_DIR"
    npm run dev &
    FRONTEND_PID=$!
    echo -e "${GREEN}Frontend started (PID: $FRONTEND_PID)${NC}"
    echo "Dev Server: http://localhost:3000 (or next available port)"
}

# Function to test upload
test_upload() {
    echo -e "${YELLOW}Testing Media Upload...${NC}"
    
    # Create test image
    if command -v convert &> /dev/null; then
        convert -size 100x100 xc:blue /tmp/test-upload.png
        echo -e "${GREEN}Test image created${NC}"
    else
        echo -e "${RED}ImageMagick not installed, using placeholder${NC}"
        dd if=/dev/urandom of=/tmp/test-upload.png bs=1024 count=10
    fi
    
    # Upload
    RESULT=$(curl -s -X POST http://localhost:8000/api/v1/media \
      -F "file=@/tmp/test-upload.png" 2>&1)
    
    if echo "$RESULT" | grep -q '"id"'; then
        echo -e "${GREEN}✅ Upload successful!${NC}"
        echo "$RESULT" | grep -o '"filename":"[^"]*"'
    else
        echo -e "${RED}❌ Upload failed${NC}"
        echo "$RESULT"
    fi
}

# Function to list media
list_media() {
    echo -e "${YELLOW}Listing Media...${NC}"
    curl -s http://localhost:8000/api/v1/media | python3 -m json.tool 2>/dev/null || \
    curl -s http://localhost:8000/api/v1/media
}

# Function to check health
check_health() {
    echo -e "${YELLOW}Checking Environment Health...${NC}"
    
    echo ""
    echo "PHP: $(php -v | head -1)"
    echo "Node: $(node -v)"
    echo "NPM: $(npm -v)"
    
    echo ""
    echo -e "${YELLOW}Services:${NC}"
    
    # Backend
    if curl -s http://localhost:8000/api/v1/health > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Backend API${NC}"
    else
        echo -e "${RED}❌ Backend API${NC}"
    fi
    
    # Frontend
    if curl -s http://localhost:3000 > /dev/null 2>&1 || \
       curl -s http://localhost:3001 > /dev/null 2>&1 || \
       curl -s http://localhost:3002 > /dev/null 2>&1 || \
       curl -s http://localhost:3003 > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Frontend Dev${NC}"
    else
        echo -e "${RED}❌ Frontend Dev${NC}"
    fi
    
    # Database
    php << 'PHPEOF'
$host = 'localhost';
$port = '5432';
$db = 'blog_platform';
$user = 'blog_admin';
$password = 'b4m@#62P';

try {
    $dsn = "pgsql:host={$host};port={$port};dbname={$db}";
    $pdo = new PDO($dsn, $user, $password);
    echo "✅ PostgreSQL\n";
} catch (PDOException $e) {
    echo "❌ PostgreSQL\n";
}
?>
PHPEOF

    # Redis
    php << 'PHPEOF'
try {
    $redis = new Redis();
    $redis->connect('localhost', 6379);
    $password = 'd4m@!62R';
    if ($redis->auth($password)) {
        echo "✅ Redis\n";
    } else {
        echo "❌ Redis (auth failed)\n";
    }
} catch (Exception $e) {
    echo "❌ Redis\n";
}
?>
PHPEOF
}

# Function to show help
show_help() {
    echo "Operacional - Blog Platform Fase 3"
    echo ""
    echo "Usage: ./OPERACIONAL.sh [command]"
    echo ""
    echo "Commands:"
    echo "  start-backend       Start Backend API on localhost:8000"
    echo "  start-frontend      Start Frontend Dev on localhost:3000+"
    echo "  start-all          Start both Backend and Frontend"
    echo "  stop               Stop all services"
    echo "  test-upload        Test media upload endpoint"
    echo "  list-media         List all media"
    echo "  health             Check environment health"
    echo "  help               Show this help message"
    echo ""
    echo "Quick Start:"
    echo "  1. ./OPERACIONAL.sh start-all"
    echo "  2. ./OPERACIONAL.sh health"
    echo "  3. ./OPERACIONAL.sh test-upload"
    echo ""
}

# Main
case "${1:-help}" in
    start-backend)
        start_backend
        wait
        ;;
    start-frontend)
        start_frontend
        wait
        ;;
    start-all)
        start_backend
        sleep 2
        start_frontend
        wait
        ;;
    stop)
        pkill -f "php -S localhost:8000" || true
        pkill -f "npm run dev" || true
        echo -e "${GREEN}Services stopped${NC}"
        ;;
    test-upload)
        test_upload
        ;;
    list-media)
        list_media
        ;;
    health)
        check_health
        ;;
    help)
        show_help
        ;;
    *)
        echo -e "${RED}Unknown command: $1${NC}"
        show_help
        exit 1
        ;;
esac
