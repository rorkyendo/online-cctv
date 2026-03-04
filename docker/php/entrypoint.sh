#!/bin/sh
# ============================================================
# Entrypoint — fix volume permissions lalu start PHP-FPM
# Berjalan sebagai root (container), FPM workers tetap www
# ============================================================
set -e

echo "[entrypoint] Fixing storage permissions..."
chown -R www:www \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/public/assets/img/profil 2>/dev/null || true

chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache 2>/dev/null || true

# Jalankan artisan optimize kalau APP_KEY sudah ada
if [ -n "$APP_KEY" ]; then
    echo "[entrypoint] Running artisan optimize..."
    su-exec www php /var/www/html/artisan config:cache  2>/dev/null || true
    su-exec www php /var/www/html/artisan route:cache   2>/dev/null || true
    su-exec www php /var/www/html/artisan view:cache    2>/dev/null || true
else
    echo "[entrypoint] WARNING: APP_KEY not set, skipping artisan optimize."
fi

echo "[entrypoint] Starting PHP-FPM..."
exec php-fpm
