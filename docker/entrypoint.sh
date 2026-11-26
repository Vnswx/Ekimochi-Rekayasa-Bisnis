#!/bin/sh
# Container entrypoint for Railway: render nginx, wire up the public disk,
# migrate the MySQL database, cache config/views, then start php-fpm + nginx.
set -e

cd /var/www/html

PORT="${PORT:-8080}"
export PORT
echo "[entrypoint] starting Ekimochi on port ${PORT}"

# --- nginx ------------------------------------------------------------------
envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf
nginx -t

# --- writable dirs (survives a Railway volume mounted over storage/) --------
mkdir -p storage/app/public/products storage/app/public/outlets \
         storage/framework/cache/data storage/framework/sessions \
         storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# --- public disk ------------------------------------------------------------
# storage/app/public is git-ignored (Laravel default) while the seeded products
# store their images there, so copy the tracked originals from
# public/images/homepage. `cp -n` never overwrites files uploaded later through
# the admin panel.
php artisan storage:link >/dev/null 2>&1 || true
mkdir -p storage/app/public/products
cp -n public/images/homepage/*.png storage/app/public/products/ 2>/dev/null || true
cp -n public/images/homepage/*.jpg storage/app/public/products/ 2>/dev/null || true
chown -R www-data:www-data storage/app/public

# --- database ---------------------------------------------------------------
# MySQL on Railway is often still booting when the app container starts.
attempt=0
until php artisan migrate --force --no-interaction; do
    attempt=$((attempt + 1))
    if [ "$attempt" -ge 15 ]; then
        echo "[entrypoint] database unreachable after 15 attempts, aborting" >&2
        exit 1
    fi
    echo "[entrypoint] database not ready (attempt ${attempt}/15), retrying in 4s"
    sleep 4
done

if [ "${RUN_SEED:-false}" = "true" ]; then
    echo "[entrypoint] RUN_SEED=true -> seeding database (seeders are idempotent)"
    php artisan db:seed --force --no-interaction
fi

# --- caches -----------------------------------------------------------------
php artisan config:cache
php artisan view:cache
php artisan package:discover --ansi >/dev/null
# routes/web.php mixes closure routes with controller routes; caching works on
# the current tree (verified) but must never break a deploy if it stops working.
php artisan route:cache || php artisan route:clear

# --- serve ------------------------------------------------------------------
php-fpm -D
exec nginx -g 'daemon off;'
