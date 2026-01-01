#!/bin/bash

echo "=========================================="
echo "Simple Database Initialization"
echo "=========================================="

cd /app

# Test connection first (disable SSL)
echo "Testing database connection..."
mysql --ssl-mode=DISABLED -h grainflow-database-7brbih -u cogsflow_user -pCogsFlow2026SecurePass -D cogsflow_db -e "SELECT 1;" 2>&1
if [ $? -ne 0 ]; then
    echo "✗ Cannot connect to database"
    exit 1
fi
echo "✓ Database connection OK"

# Check if tables exist
echo ""
echo "Checking existing tables..."
table_count=$(mysql --ssl-mode=DISABLED -h grainflow-database-7brbih -u cogsflow_user -pCogsFlow2026SecurePass -D cogsflow_db -e "SHOW TABLES;" 2>/dev/null | wc -l)
echo "Found $table_count tables"

if [ "$table_count" -gt 5 ]; then
    echo "✓ Database already initialized"
    exit 0
fi

# Run migrations
echo ""
echo "Running migrations..."
php spark migrate --all 2>&1 | tee /tmp/migration.log

# Check if users table was created
echo ""
echo "Verifying users table..."
mysql --ssl-mode=DISABLED -h grainflow-database-7brbih -u cogsflow_user -pCogsFlow2026SecurePass -D cogsflow_db -e "DESCRIBE users;" 2>&1
if [ $? -eq 0 ]; then
    echo "✓ Users table exists"
else
    echo "✗ Users table not created - check migration logs"
    cat /tmp/migration.log
    exit 1
fi

# Seed data
echo ""
echo "Seeding data..."
php spark db:seed RolesSeeder 2>&1
php spark db:seed PermissionsSeeder 2>&1
php spark db:seed ProductionUserSeeder 2>&1
php spark db:seed ProductionSettingsSeeder 2>&1

echo ""
echo "=========================================="
echo "✓ Database initialization complete!"
echo "=========================================="
echo ""
echo "Login at: https://nipoagro.com/auth/login"
echo "Username: admin"
echo "Password: NipoAgro2025!"
echo ""
