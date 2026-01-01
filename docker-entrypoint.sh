#!/bin/bash

# Docker Entrypoint Script for CogsFlow
# Handles database initialization and starts services

set -e

echo "Starting CogsFlow Application..."

# Run database initialization (only runs on first deployment)
if [ -f /app/init-database.sh ]; then
    echo "Running database initialization..."
    bash /app/init-database.sh
fi

# Set proper permissions
echo "Setting file permissions..."
chown -R www-data:www-data /app/writable /app/public/uploads 2>/dev/null || true
chmod -R 755 /app/writable /app/public 2>/dev/null || true

echo "Starting services via Supervisor..."

# Start supervisor (which manages nginx and php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
