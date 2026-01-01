#!/bin/bash

# Docker Entrypoint Script for CogsFlow
# Handles database initialization and starts services

echo "Starting CogsFlow Application..."

# Start services first (non-blocking)
echo "Starting PHP-FPM and Nginx..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf &
SUPERVISOR_PID=$!

# Wait a moment for services to start
sleep 3

# Set proper permissions
echo "Setting file permissions..."
chown -R www-data:www-data /app/writable /app/public/uploads 2>/dev/null || true
chmod -R 755 /app/writable /app/public 2>/dev/null || true

# Run database initialization in background (non-blocking)
if [ -f /app/init-database.sh ]; then
    echo "Running database initialization in background..."
    bash /app/init-database.sh &
fi

# Keep container running by waiting for supervisor
wait $SUPERVISOR_PID
