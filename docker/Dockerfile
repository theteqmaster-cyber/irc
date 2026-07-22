FROM php:8.3-fpm-alpine

# Install system dependencies & Poppler utilities (pdftotext)
RUN apk add --no-config --no-cache \
    nginx \
    poppler-utils \
    libpq-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Nginx config copy
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Expose port
EXPOSE 80

# Start Nginx & PHP-FPM
CMD php-fpm -D && nginx -g 'daemon off;'
