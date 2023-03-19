# Use an official PHP runtime as a parent image
FROM php:8.1.0-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the local Laravel files to the container's working directory
COPY . .

# Update package list and install required packages
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libmcrypt-dev \
    libcurl4-openssl-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    curl

# Install required PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Enable required Apache modules
RUN a2enmod rewrite

# Set the Apache server's document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Copy the .env.example file to .env
COPY .env.example .env

# Generate the application key
RUN php artisan key:generate

# Set the ownership and permissions of the Laravel files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expose port 80 and start the Apache server
EXPOSE 80
CMD ["apache2-foreground"]
