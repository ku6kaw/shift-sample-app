# syntax=docker/dockerfile:1.6

ARG APP_ENV=production

FROM composer:2.7 AS vendor
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    && rm -rf node_modules public/build

FROM node:20-alpine AS assets
WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci

COPY resources resources
COPY public public
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

FROM webdevops/php-nginx:8.3-alpine

ARG APP_ENV=production
ENV APP_ENV="${APP_ENV}" \
    WEB_DOCUMENT_ROOT=/var/www/html/public

WORKDIR /var/www/html

COPY --from=vendor /var/www/html ./
COPY --from=assets /var/www/html/public/build ./public/build

RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R application:application storage bootstrap/cache

COPY docker/railway/start.sh /opt/start.sh
RUN chmod +x /opt/start.sh

CMD ["/opt/start.sh"]
