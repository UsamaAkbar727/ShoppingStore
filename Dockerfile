FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Run composer install to ensure vendor folder is properly compiled for the target platform
RUN composer install --no-interaction --optimize-autoloader

# Set Apache permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
