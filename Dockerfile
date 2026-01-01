# Use official PHP-FPM image
FROM php:8.1-fpm

# Install system dependencies and Nginx
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client \
    nginx \
    supervisor \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Copy development environment file for debugging
COPY .env.development /app/.env

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/sites-available/default

# Copy initialization scripts
COPY init-database.sh /app/init-database.sh
COPY docker-entrypoint.sh /app/docker-entrypoint.sh
COPY setup-database-now.sh /app/setup-database-now.sh
RUN chmod +x /app/init-database.sh /app/docker-entrypoint.sh /app/setup-database-now.sh

# Create supervisor config
RUN echo "[supervisord]\n\
nodaemon=true\n\
\n\
[program:php-fpm]\n\
command=/usr/local/sbin/php-fpm\n\
autostart=true\n\
autorestart=true\n\
stdout_logfile=/dev/stdout\n\
stdout_logfile_maxbytes=0\n\
stderr_logfile=/dev/stderr\n\
stderr_logfile_maxbytes=0\n\
\n\
[program:nginx]\n\
command=/usr/sbin/nginx -g 'daemon off;'\n\
autostart=true\n\
autorestart=true\n\
stdout_logfile=/dev/stdout\n\
stdout_logfile_maxbytes=0\n\
stderr_logfile=/dev/stderr\n\
stderr_logfile_maxbytes=0" > /etc/supervisor/conf.d/supervisord.conf

# Create writable directories and set permissions
RUN mkdir -p /app/writable/cache \
    /app/writable/logs \
    /app/writable/session \
    /app/writable/uploads \
    /app/public/uploads \
    && chown -R www-data:www-data /app \
    && chmod -R 777 /app/writable \
    && chmod -R 755 /app/public

# Expose port 80
EXPOSE 80

# Use custom entrypoint for initialization
ENTRYPOINT ["/app/docker-entrypoint.sh"]
