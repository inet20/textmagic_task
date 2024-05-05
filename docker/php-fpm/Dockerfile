FROM php:8.3-fpm as textmagic_php

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y libpq-dev libzip-dev zip \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && pecl install --onlyreqdeps --force redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

#COPY ./docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ARG USER_ID=1000
RUN adduser --uid ${USER_ID} --disabled-password --no-create-home app
USER app

WORKDIR /var/www/app

EXPOSE 9000
