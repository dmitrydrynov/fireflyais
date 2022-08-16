#!/usr/bin/env bash

chown -R application:www-data /app/storage
chown -R application:www-data /app/bootstrap/cache
chmod -R 775 /app/storage
chmod -R 775 /app/bootstrap/cache

yarn install --frozen-lockfile

if [ "$APP_ENV" = "production" ]; then \
    yarn production; \
fi

composer install

php artisan migrate --seed
php artisan firefly-iii:upgrade-database
php artisan passport:install --force

if [ "$APP_ENV" = "local" ]; then \
    php artisan ide-helper:generate \
    && php artisan optimize; \
fi

crontab -l | { cat; echo "0 3 * * * /usr/local/bin/php /app/artisan firefly-iii:cron"; } | sort -u - | crontab -