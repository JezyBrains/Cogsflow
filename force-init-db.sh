#!/bin/bash

# Force Database Initialization - Run this manually
# This bypasses all checks and forces database setup

echo "=========================================="
echo "Force Database Initialization"
echo "=========================================="
echo ""

cd /app || exit 1

echo "Step 1: Running migrations..."
php spark migrate --all
if [ $? -eq 0 ]; then
    echo "✓ Migrations completed"
else
    echo "✗ Migrations failed"
    exit 1
fi

echo ""
echo "Step 2: Seeding database..."

php spark db:seed RolesSeeder
php spark db:seed PermissionsSeeder
php spark db:seed ProductionUserSeeder
php spark db:seed ProductionSettingsSeeder
php spark db:seed NotificationTypesSeeder
php spark db:seed ReportsSeeder

echo ""
echo "=========================================="
echo "✓ Database Setup Complete!"
echo "=========================================="
echo ""
echo "Default Admin Login:"
echo "  URL: https://nipoagro.com/auth/login"
echo "  Username: admin"
echo "  Password: NipoAgro2025!"
echo ""
echo "=========================================="
