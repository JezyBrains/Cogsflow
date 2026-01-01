#!/bin/bash

# Database Initialization Script for CogsFlow
# This script runs migrations and seeds the database on first deployment

echo "=========================================="
echo "CogsFlow Database Initialization"
echo "=========================================="

# Wait for database to be ready
echo "Waiting for database connection..."
max_attempts=60
attempt=0

while [ $attempt -lt $max_attempts ]; do
    if php -r "try { \$db = \Config\Database::connect(); \$db->query('SELECT 1'); echo 'OK'; } catch (Exception \$e) { exit(1); }" 2>/dev/null | grep -q "OK"; then
        echo "✓ Database connection established"
        break
    fi
    attempt=$((attempt + 1))
    echo "Waiting for database... (attempt $attempt/$max_attempts)"
    sleep 3
done

if [ $attempt -eq $max_attempts ]; then
    echo "✗ Failed to connect to database after $max_attempts attempts"
    echo "Application will continue running, but database may not be initialized"
    exit 0
fi

# Check if database is already initialized by checking for users table
echo ""
echo "Checking if database is already initialized..."
table_exists=$(php -r "try { \$db = \Config\Database::connect(); \$result = \$db->query('SHOW TABLES LIKE \"users\"'); echo \$result->getNumRows(); } catch (Exception \$e) { echo '0'; }" 2>/dev/null)

if [ "$table_exists" -gt 0 ]; then
    echo "✓ Database tables already exist - skipping initialization"
    exit 0
fi

echo ""
echo "=========================================="
echo "Running Database Migrations"
echo "=========================================="
php spark migrate --all

echo ""
echo "=========================================="
echo "Seeding Production Data"
echo "=========================================="

# Run individual seeders
echo "→ Creating roles and permissions..."
php spark db:seed RolesSeeder 2>/dev/null || echo "  (Roles may already exist)"

echo "→ Creating permissions..."
php spark db:seed PermissionsSeeder 2>/dev/null || echo "  (Permissions may already exist)"

echo "→ Creating default admin user..."
php spark db:seed ProductionUserSeeder 2>/dev/null || echo "  (User may already exist)"

echo "→ Setting up system settings..."
php spark db:seed ProductionSettingsSeeder 2>/dev/null || echo "  (Settings may already exist)"

echo "→ Creating notification types..."
php spark db:seed NotificationTypesSeeder 2>/dev/null || echo "  (Notification types may already exist)"

echo "→ Setting up reports..."
php spark db:seed ReportsSeeder 2>/dev/null || echo "  (Reports may already exist)"

echo ""
echo "=========================================="
echo "✓ Database Initialization Complete!"
echo "=========================================="
echo ""
echo "Default Admin Credentials:"
echo "  Username: admin"
echo "  Email: admin@nipoagro.com"
echo "  Password: NipoAgro2025!"
echo ""
echo "=========================================="
