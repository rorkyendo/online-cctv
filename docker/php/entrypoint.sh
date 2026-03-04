#!/bin/sh
# ============================================================
# Entrypoint — fix volume permissions lalu start PHP-FPM
# Berjalan sebagai root (container), FPM workers tetap www
# ============================================================
set -e

BASE=/var/www/html

# ── Buat struktur storage jika kosong (named volume baru/empty) ──
echo "[entrypoint] Ensuring storage structure exists..."
mkdir -p \
    "$BASE/storage/app/public" \
    "$BASE/storage/framework/cache/data" \
    "$BASE/storage/framework/sessions" \
    "$BASE/storage/framework/testing" \
    "$BASE/storage/framework/views" \
    "$BASE/storage/logs" \
    "$BASE/bootstrap/cache" \
    "$BASE/public/assets/img/profil"

# Buat .gitignore di storage/logs agar Laravel tidak error
if [ ! -f "$BASE/storage/logs/.gitignore" ]; then
    echo "*\n!.gitignore" > "$BASE/storage/logs/.gitignore"
fi

echo "[entrypoint] Fixing storage permissions..."
chown -R www:www \
    "$BASE/storage" \
    "$BASE/bootstrap/cache" \
    "$BASE/public/assets/img/profil" 2>/dev/null || true

chmod -R 775 \
    "$BASE/storage" \
    "$BASE/bootstrap/cache" 2>/dev/null || true

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
