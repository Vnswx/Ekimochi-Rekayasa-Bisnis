# Ekimochi - production image for Railway (nginx + PHP-FPM 8.2, Laravel 10)
#
# Why a Dockerfile instead of Nixpacks: the app needs pdo_mysql, gd and raised
# upload limits, and this Laravel 10 tree has no `server.php`, so
# `php artisan serve` (the old nixpacks start command) cannot work.

# ---------- stage 1: PHP dependencies (built for Linux, not the Windows host) ----------
FROM composer:2 AS vendor

WORKDIR /build
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-scripts \
        --no-progress \
        --prefer-dist \
        --optimize-autoloader

# ---------- stage 2: runtime ----------
# PHP 8.2 matches the version this codebase is developed and tested on locally.
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
        nginx \
        gettext \
        tzdata \
        icu-dev \
        oniguruma-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
 && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring gd zip bcmath exif opcache \
 && apk del .build-deps \
 && ln -snf /usr/share/zoneinfo/Asia/Jakarta /etc/localtime \
 && echo "Asia/Jakarta" > /etc/timezone

WORKDIR /var/www/html

COPY --from=vendor /build/vendor ./vendor
COPY . .
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-ekimochi.ini
COPY docker/nginx.conf.template /etc/nginx/http.d/default.conf.template

RUN mkdir -p \
        storage/app/public/products \
        storage/app/public/outlets \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
        /var/lib/nginx/tmp \
        /var/log/nginx \
 && rm -f /etc/nginx/http.d/default.conf \
 && php artisan package:discover --ansi \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod +x docker/entrypoint.sh

EXPOSE 8080

CMD ["sh", "/var/www/html/docker/entrypoint.sh"]
