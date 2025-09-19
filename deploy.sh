#!/bin/bash

# CogsFlow Production Deployment Script for localhost:8000
# Run this script on the production server

echo "=========================================="
echo "CogsFlow Production Deployment"
echo "Domain: localhost:8000"
echo "=========================================="

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    echo "Please do not run this script as root"
    exit 1
fi

# Set production environment
echo "Setting up production environment..."
cp .env.production .env

# Generate encryption key if not set
if ! grep -q "encryption.key = " .env || grep -q "REPLACE_WITH_GENERATED_ENCRYPTION_KEY" .env; then
    echo "Generating encryption key..."
    php spark key:generate
fi

# Set proper file permissions
echo "Setting file permissions..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 777 writable/
chmod 644 .env

# Install/update composer dependencies for production
echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

# Run database migrations
echo "Running database migrations..."
php spark migrate

# Seed production data
echo "Seeding production data..."
php spark db:seed ProductionSeeder

# Clear all caches
echo "Clearing caches..."
php spark cache:clear

# Optimize for production
echo "Optimizing for production..."
php spark optimize

echo "=========================================="
echo "Deployment completed successfully!"
echo "=========================================="
echo ""
echo "IMPORTANT SECURITY NOTES:"
echo "1. Change the default admin password immediately"
echo "2. Database credentials are pre-configured for johsport_nipo"
echo "3. Update email configuration in .env file if needed"
echo "4. Generate encryption key if not already set"
echo "5. Ensure SSL certificate is properly configured"
echo ""
echo "Default Admin Credentials:"
echo "Username: admin"
echo "Email: admin@localhost:8000"
echo "Password: NipoAgro2025!"
echo ""
echo "Please change these credentials after first login!"
echo "=========================================="
