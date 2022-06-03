#!/usr/bin/env bash

# chmod 755 -R storage

# yarn install --frozen-lockfile

if [ "$APP_ENV" = "production" ]; then \
    yarn production; \
fi

if [ "$APP_ENV" = "local" ]; then \
    php artisan ide-helper:generate \
    php artisan optimize; \
fi

php artisan migrate --seed
php artisan firefly-iii:upgrade-database
php artisan passport:install --force

crontab -l | { cat; echo "0 3 * * * /usr/local/bin/php /app/artisan firefly-iii:cron"; } | crontab -