# Dev image
FROM php:7.4-fpm-alpine

## Install composer
RUN wget https://getcomposer.org/installer && \
    php installer --install-dir=/usr/local/bin/ --filename=composer && \
    rm installer && \
    composer global require hirak/prestissimo
    
## Install system dependencies
#    apk add --no-cache libzip-dev
RUN apk update && \
    apk add --no-cache --virtual dev-deps git autoconf gcc g++ make && \
    docker-php-ext-install pdo_mysql

WORKDIR /app
RUN chown www-data:www-data /app
USER www-data

## Copy project files to workdir
COPY --chown=www-data:www-data . .

ENV APP_ENV=prod
## Install application dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader

USER root

## Cleanup
RUN apk del dev-deps && \
    composer global remove hirak/prestissimo && \
    rm /usr/local/bin/composer && \
    rm -rf /tmp/* && \
    rm -rf /var/cache/apk/*

CMD sh docker.sh
