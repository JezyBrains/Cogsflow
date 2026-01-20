FROM php:8.4-fpm


# Arguments defined in docker-compose.yml
ARG user=www-data
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    supervisor \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy custom configurations
COPY deployment/nginx.conf /etc/nginx/sites-available/default
COPY deployment/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create entrypoint script
COPY deployment/entrypoint.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

# Copy application code
COPY . /var/www

# Install Dependencies (excluding dev)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Fix permissions
RUN chown -R www-data:www-data /var/www

# Expose ports
EXPOSE 80

# Execute Entrypoint
ENTRYPOINT ["/usr/local/bin/start-container"]
