#!/usr/bin/env bash
if [ -f /var/app/composer.json ]; then
    echo "[info] Composer install"
    composer install --working-dir=/var/app
fi

if [ ! -f /var/app/.env.local ]; then
    cp /var/app/.docker/php-fpm/.env.local /var/app/.env.local
    php /var/app/bin/console doctrine:database:create
fi

php /var/app/bin/console doctrine:migration:migrate --no-interaction