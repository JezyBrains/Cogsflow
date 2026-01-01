#!/bin/bash

# Manual Database Setup Script for CogsFlow
# Run this script directly in your Dokploy container to set up the database immediately

echo "=========================================="
echo "CogsFlow Manual Database Setup"
echo "=========================================="
echo ""

# Change to application directory
cd /app || exit 1

echo "Step 1: Testing database connection..."
if php -r "try { \$db = \Config\Database::connect(); \$db->query('SELECT 1'); echo 'OK'; } catch (Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); exit(1); }" 2>&1 | grep -q "OK"; then
    echo "✓ Database connection successful"
else
    echo "✗ Database connection failed"
    echo "Please check your database credentials in .env file"
    exit 1
fi

echo ""
echo "Step 2: Running database migrations..."
php spark migrate --all
if [ $? -eq 0 ]; then
    echo "✓ Migrations completed successfully"
else
    echo "✗ Migration failed"
    exit 1
fi

echo ""
echo "Step 3: Seeding database with initial data..."

echo "  → Creating roles..."
php spark db:seed RolesSeeder
echo "  → Creating permissions..."
php spark db:seed PermissionsSeeder
echo "  → Creating admin user..."
php spark db:seed ProductionUserSeeder
echo "  → Setting up system settings..."
php spark db:seed ProductionSettingsSeeder
echo "  → Creating notification types..."
php spark db:seed NotificationTypesSeeder
echo "  → Setting up reports..."
php spark db:seed ReportsSeeder

echo ""
echo "=========================================="
echo "✓ Database Setup Complete!"
echo "=========================================="
echo ""
echo "Default Admin Credentials:"
echo "  Username: admin"
echo "  Email: admin@nipoagro.com"
echo "  Password: NipoAgro2025!"
echo ""
echo "You can now access the application at:"
echo "  https://nipoagro.com"
echo ""
echo "=========================================="
