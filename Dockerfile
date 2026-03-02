FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY src/ /var/www/html/

RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html
