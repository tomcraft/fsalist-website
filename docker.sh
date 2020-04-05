#!/bin/sh

#export APP_KEY=$(php artisan key:generate --show)
export APP_KEY=TESTESTESTESTEST
echo "Migrations in progress ..."
#php artisan migrate
docker-php-entrypoint php-fpm
