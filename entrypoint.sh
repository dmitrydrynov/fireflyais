#!/usr/bin/env bash

composer install

php artisan migrate:refresh --seed
php artisan firefly-iii:upgrade-database
php artisan passport:install --force

line="0 3 * * * /usr/local/bin/php /var/www/artisan firefly-iii:cron"
(crontab -u $(whoami) -l; echo "$line" ) | crontab -u $(whoami) -