#!/bin/sh
set -e

# Caching Configuration
echo "🔥 Caching Configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run Migrations (Force Production)
echo "📦 Running Migrations..."
php artisan migrate --force

# Link Storage
echo "🔗 Linking Storage..."
php artisan storage:link

# Start Supervisor
echo "🚀 Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
