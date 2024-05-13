FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    vim \
    git \
    curl \
    libicu-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

# Сonfigure PHP extensions
RUN docker-php-ext-configure intl

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif intl pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

COPY . /var/www

RUN chown -R www-data:www-data /var/www

# Run composer install
RUN composer install
