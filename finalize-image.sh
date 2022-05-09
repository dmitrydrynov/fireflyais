#!/bin/bash
if [ ! -f "/app/first_start_container" ]; then
    mkdir -p /app/storage/app/public
    mkdir -p /app/storage/build
    mkdir -p /app/storage/database
    mkdir -p /app/storage/debugbar
    mkdir -p /app/storage/export
    mkdir -p /app/storage/framework/cache/data
    mkdir -p /app/storage/framework/sessions
    mkdir -p /app/storage/framework/testing
    mkdir -p /app/storage/framework/views/twig
    mkdir -p /app/storage/framework/views/v1
    mkdir -p /app/storage/framework/views/v2
    mkdir -p /app/storage/logs
    mkdir -p /app/storage/upload
    sleep 30
	php artisan config:cache
	php artisan key:generate
	php artisan migrate:refresh --seed
    php artisan firefly-iii:upgrade-database
    php artisan passport:install>>passport_install_out
    echo "APP_KEY=$APP_KEY">/app/.env
    echo "$APP_KEY">>/app/first_start_container
    chown -R application:application /app
fi

