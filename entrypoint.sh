#!/usr/bin/env bash

chmod 755 -R /app/storage

composer install

php artisan migrate --seed
php artisan firefly-iii:upgrade-database
php artisan passport:install --force

line="0 3 * * * /usr/local/bin/php /app/artisan firefly-iii:cron"
(crontab -u $(whoami) -l; echo "$line" ) | crontab -u $(whoami) -