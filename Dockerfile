FROM php:8.1-fpm-alpine

# Install necessary packages and extensions
RUN apk update && \
    apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    linux-headers \
    && apk add --no-cache \
    bash \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Set the working directory and copy the application code
WORKDIR /var/www/html
COPY . .

# Set the ownership and permissions for storage and cache directories
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set the MySQL server host, port, username, password, and database name
ENV DB_HOST=mysql
ENV DB_PORT=3306
ENV DB_USERNAME=root
ENV DB_PASSWORD=
ENV DB_DATABASE=joya_test_laravel

# Start the PHP-FPM process
CMD ["php-fpm"]
