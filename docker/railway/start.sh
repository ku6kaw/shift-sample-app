#!/bin/sh
set -e

PORT_VALUE="${PORT:-8080}"
export PORT="${PORT_VALUE}"
export WEB_HTTP_PORT="${PORT_VALUE}"
export WEB_PORT="${PORT_VALUE}"

# Align nginx vhost listen port with Railway's assigned port
if [ -f /opt/docker/etc/nginx/vhost.conf ]; then
    sed -i "s/listen 80 default_server;/listen ${PORT_VALUE} default_server;/" /opt/docker/etc/nginx/vhost.conf
    sed -i "s/listen \\[::\\]:80 default_server;/listen [::]:${PORT_VALUE} default_server;/" /opt/docker/etc/nginx/vhost.conf
fi

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

echo "Starting supervisord via webdevops entrypoint"
exec /opt/docker/bin/entrypoint.sh supervisord
