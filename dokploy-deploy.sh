#!/bin/bash

# CogsFlow Dokploy Deployment Script
# This script helps you deploy CogsFlow to Dokploy

set -e

echo "🚀 CogsFlow Dokploy Deployment Helper"
echo "======================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Creating from .env.example..."
    cp .env.example .env
    echo "✅ Created .env file. Please edit it with your configuration:"
    echo "   - Database credentials"
    echo "   - Encryption key"
    echo "   - App URL"
    echo ""
    echo "Generate encryption key with: php -r \"echo bin2hex(random_bytes(16));\""
    echo ""
    read -p "Press Enter after you've configured .env..."
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

echo "✅ Docker is installed"

# Check if docker-compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ docker-compose is not installed. Please install docker-compose first."
    exit 1
fi

echo "✅ docker-compose is installed"
echo ""

# Ask deployment method
echo "Choose deployment method:"
echo "1) Build and test locally"
echo "2) Push to GitHub for Dokploy auto-deploy"
echo "3) Deploy directly with docker-compose"
echo ""
read -p "Enter choice (1-3): " choice

case $choice in
    1)
        echo ""
        echo "🔨 Building Docker images..."
        docker-compose build
        
        echo ""
        echo "🚀 Starting containers..."
        docker-compose up -d
        
        echo ""
        echo "⏳ Waiting for services to be ready..."
        sleep 10
        
        echo ""
        echo "📊 Checking container status..."
        docker-compose ps
        
        echo ""
        echo "🗄️  Running database migrations..."
        docker-compose exec -T app php spark migrate
        
        echo ""
        echo "🌱 Seeding default data..."
        docker-compose exec -T app php spark db:seed DefaultUserSeeder
        docker-compose exec -T app php spark db:seed DefaultSettingsSeeder
        
        echo ""
        echo "✅ Local deployment complete!"
        echo "🌐 Access your application at: http://localhost:8080"
        echo "👤 Default login: admin / NipoAgro2025!"
        echo ""
        echo "📋 Useful commands:"
        echo "   View logs: docker-compose logs -f app"
        echo "   Stop: docker-compose down"
        echo "   Restart: docker-compose restart"
        ;;
        
    2)
        echo ""
        echo "📦 Preparing files for GitHub..."
        
        # Check if git is initialized
        if [ ! -d .git ]; then
            echo "❌ Not a git repository. Please initialize git first."
            exit 1
        fi
        
        echo "✅ Git repository detected"
        
        # Add Dokploy files
        git add Dockerfile docker-compose.yml .dockerignore docker/ .env.example DOKPLOY_DEPLOYMENT_GUIDE.md dokploy-deploy.sh
        
        echo ""
        read -p "Enter commit message (or press Enter for default): " commit_msg
        
        if [ -z "$commit_msg" ]; then
            commit_msg="Add Dokploy deployment configuration"
        fi
        
        git commit -m "$commit_msg"
        
        echo ""
        read -p "Push to GitHub? (y/n): " push_confirm
        
        if [ "$push_confirm" = "y" ]; then
            git push origin main
            echo ""
            echo "✅ Pushed to GitHub!"
            echo ""
            echo "📝 Next steps:"
            echo "1. Login to your Dokploy dashboard"
            echo "2. Create new application"
            echo "3. Connect to GitHub repository: JezyBrains/Cogsflow"
            echo "4. Configure environment variables (see DOKPLOY_DEPLOYMENT_GUIDE.md)"
            echo "5. Deploy!"
            echo ""
            echo "📖 Full guide: DOKPLOY_DEPLOYMENT_GUIDE.md"
        fi
        ;;
        
    3)
        echo ""
        echo "🚀 Deploying with docker-compose..."
        
        # Build and start
        docker-compose up -d --build
        
        echo ""
        echo "⏳ Waiting for services to be ready..."
        sleep 15
        
        echo ""
        echo "🗄️  Running database migrations..."
        docker-compose exec -T app php spark migrate
        
        echo ""
        echo "🌱 Seeding default data..."
        docker-compose exec -T app php spark db:seed DefaultUserSeeder
        docker-compose exec -T app php spark db:seed DefaultSettingsSeeder
        
        echo ""
        echo "✅ Deployment complete!"
        echo "🌐 Access your application at: http://localhost:8080"
        echo "👤 Default login: admin / NipoAgro2025!"
        ;;
        
    *)
        echo "❌ Invalid choice"
        exit 1
        ;;
esac

echo ""
echo "🎉 Done!"
