FROM composer:latest
FROM php:8.2-fpm-alpine

# Make the 'php-extension-installer' binary accessible
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN install-php-extensions pdo_mysql gmp gd zip redis

# Because in docker-compose.yaml we bind the whole app directory as volume, and also want it to be
# writable, change the user to 1000:1000, so the permissions would be right.
# This is probably bad to hardcode this, but anyway.
# USER 501:1000

WORKDIR /src

# RUN composer install --no-dev --no-interaction --ansi
