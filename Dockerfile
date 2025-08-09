FROM php:8.3-fpm-alpine
WORKDIR /var/www/html
RUN apk add --no-cache \
    curl \
    icu-dev
RUN docker-php-ext-install \
    intl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
