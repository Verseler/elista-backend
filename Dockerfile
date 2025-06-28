# Use official PHP image with necessary extensions
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www

# Copy composer and install dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy existing application
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Expose port 8000
EXPOSE 8000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
