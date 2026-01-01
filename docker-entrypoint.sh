#!/bin/bash

# Docker Entrypoint Script for CogsFlow
# Handles database initialization and starts services

echo "Starting CogsFlow Application..."

# Set proper permissions first
echo "Setting file permissions..."
chown -R www-data:www-data /app/writable /app/public/uploads 2>/dev/null || true
chmod -R 777 /app/writable /app/public 2>/dev/null || true

# Start services immediately
echo "Starting PHP-FPM and Nginx..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf &
SUPERVISOR_PID=$!

# Wait for services to start
sleep 2

# Run database initialization in background
if [ -f /app/init-database.sh ]; then
    echo "Running database initialization in background..."
    bash /app/init-database.sh &
fi

# Keep container running
wait $SUPERVISOR_PID
