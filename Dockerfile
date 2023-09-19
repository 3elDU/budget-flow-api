FROM composer:latest
FROM php:8.2-fpm-alpine

# Make the binaries for 'php-extension-installer' and 'composer' accessible
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN install-php-extensions pdo_mysql gmp gd zip exif openssl

WORKDIR /app

COPY . /app
RUN composer install --no-dev --no-interaction --ansi
