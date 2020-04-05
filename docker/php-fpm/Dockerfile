# Dev image
FROM php:7.4-fpm-alpine AS dev

## Install system dependencies
RUN apk update && \
    apk add --no-cache --virtual dev-deps git autoconf gcc g++ make
#    apk add --no-cache libzip-dev

## Install php extensions
RUN docker-php-ext-install pdo_mysql

ENV APP_ENV=dev
WORKDIR /app
RUN chown www-data:www-data /app

## Install composer
RUN wget https://getcomposer.org/installer && \
    php installer --install-dir=/usr/local/bin/ --filename=composer && \
    rm installer && \
    composer global require hirak/prestissimo

USER www-data

## Copy project files to workdir
COPY --chown=www-data:www-data . .

## Install application dependencies
RUN composer install --no-interaction --optimize-autoloader

# Prod image
FROM dev AS prod

ENV APP_ENV=prod
WORKDIR /app
USER www-data

## Remove dev dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

USER root

## Cleanup
RUN apk del dev-deps && \
    composer global remove hirak/prestissimo && \
    rm /usr/local/bin/composer && \
    rm -rf /tmp/* && \
    rm -rf /var/cache/apk/*

CMD sh docker.sh
