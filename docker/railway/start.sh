#!/bin/sh
set -e

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force --no-interaction

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec /opt/docker/bin/entrypoint supervisord
