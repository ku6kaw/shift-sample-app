#!/bin/sh
set -e

if [ "${DB_HOST}" = '${MYSQLHOST}' ] && [ -n "${MYSQLHOST}" ]; then
    export DB_HOST="${MYSQLHOST}"
fi

if [ "${DB_PORT}" = '${MYSQLPORT}' ] && [ -n "${MYSQLPORT}" ]; then
    export DB_PORT="${MYSQLPORT}"
fi

if [ "${DB_DATABASE}" = '${MYSQLDATABASE}' ] && [ -n "${MYSQLDATABASE}" ]; then
    export DB_DATABASE="${MYSQLDATABASE}"
fi

if [ "${DB_USERNAME}" = '${MYSQLUSER}' ] && [ -n "${MYSQLUSER}" ]; then
    export DB_USERNAME="${MYSQLUSER}"
fi

if [ "${DB_PASSWORD}" = '${MYSQLPASSWORD}' ] && [ -n "${MYSQLPASSWORD}" ]; then
    export DB_PASSWORD="${MYSQLPASSWORD}"
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force --no-interaction

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec /opt/docker/bin/entrypoint.sh supervisord
