FROM php:7.3-fpm
COPY php.ini /usr/local/etc/php/

RUN apt-get update && docker-php-ext-install pdo_mysql mysqli mbstring

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 80
