#!/bin/sh
# ============================================================
# Entrypoint — create storage dirs + fix perms → start PHP-FPM
# Runs as root; FPM workers run as www (via www.conf pool)
# ============================================================
set -e

BASE=/var/www/html

echo "[entrypoint] Ensuring Laravel storage structure..."
mkdir -p \
    "$BASE/storage/app/public" \
    "$BASE/storage/framework/cache/data" \
    "$BASE/storage/framework/sessions" \
    "$BASE/storage/framework/testing" \
    "$BASE/storage/framework/views" \
    "$BASE/storage/logs" \
    "$BASE/bootstrap/cache" \
    "$BASE/public/assets/img/profil"

echo "[entrypoint] Fixing permissions..."
chown -R www:www \
    "$BASE/storage" \
    "$BASE/bootstrap/cache" \
    "$BASE/public/assets/img/profil" 2>/dev/null || true

chmod -R 775 \
    "$BASE/storage" \
    "$BASE/bootstrap/cache" 2>/dev/null || true

# Cache config/routes/views jika APP_KEY sudah diset
if [ -n "${APP_KEY:-}" ]; then
    echo "[entrypoint] Caching Laravel config..."
    su-exec www php "$BASE/artisan" config:cache  2>/dev/null || true
    su-exec www php "$BASE/artisan" route:cache   2>/dev/null || true
    su-exec www php "$BASE/artisan" view:cache    2>/dev/null || true
else
    echo "[entrypoint] WARNING: APP_KEY not set — skipping cache."
fi

echo "[entrypoint] Starting PHP-FPM..."
exec php-fpm
