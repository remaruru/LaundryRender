# Use PHP 8.2 CLI (lighter than Apache for php artisan serve)
FROM php:8.2-cli

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
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port (Render will set PORT dynamically)
EXPOSE 80

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
cd /var/www/html\n\
# Generate app key if not exists\n\
php artisan key:generate --force || true\n\
# Cache configuration for performance\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
php artisan view:cache || true\n\
# Run migrations and seed database\n\
php artisan migrate --force || true\n\
php artisan db:seed --class=AdminSeeder || true\n\
# Start Laravel server on the port Render provides\n\
php artisan serve --host=0.0.0.0 --port=${PORT:-80}' > /start.sh && chmod +x /start.sh

# Start Laravel server
CMD ["/start.sh"]

