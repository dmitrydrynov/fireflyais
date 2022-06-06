#!/usr/bin/env bash

chmod 755 -R storage

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