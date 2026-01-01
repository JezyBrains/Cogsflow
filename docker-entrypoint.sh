#!/bin/bash

# Docker Entrypoint Script for CogsFlow
# Handles database initialization and starts services

echo "Starting CogsFlow Application..."

# Set proper permissions first
echo "Setting file permissions..."
chown -R www-data:www-data /app/writable /app/public/uploads 2>/dev/null || true
chmod -R 777 /app/writable /app/public 2>/dev/null || true

# Run database initialization BEFORE starting services (blocking)
if [ -f /app/init-database.sh ]; then
    echo "Running database initialization (this may take a moment)..."
    bash /app/init-database.sh
    if [ $? -eq 0 ]; then
        echo "✓ Database initialization completed"
    else
        echo "⚠ Database initialization had issues, but continuing..."
    fi
fi

# Now start services after database is ready
echo "Starting PHP-FPM and Nginx..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
