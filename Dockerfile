FROM php:8.2

# 1. Install system dependencies and Node.js in a single layer to ensure consistency
# We install curl first, then setup NodeSource, then install nodejs and other libs.
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Verify Node/NPM installation immediately
RUN node -v && npm -v

# 3. Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set working directory
WORKDIR /var/www

# 6. Copy existing application directory contents
COPY . /var/www

# 7. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# 8. Install Node dependencies and build assets
RUN npm install && npm run build

# 9. Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 10. Copy entrypoint script
COPY docker-entrypoint.sh /var/www/docker-entrypoint.sh
RUN chmod +x /var/www/docker-entrypoint.sh

# 11. Expose port 8000
EXPOSE 8000

# 12. Set entrypoint
ENTRYPOINT ["/var/www/docker-entrypoint.sh"]

# 13. Start command
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
