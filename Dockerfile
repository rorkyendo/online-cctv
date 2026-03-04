# ============================================================
# Online CCTV — Laravel App (PHP 8.2-FPM Alpine)
# Single-stage build — sederhana & reliable
# ============================================================
FROM php:8.2-fpm-alpine

# ── System deps (build + runtime) ────────────────────────────
RUN apk add --no-cache \
        bash curl git unzip su-exec \
        # GD deps
        libpng libpng-dev libjpeg-turbo libjpeg-turbo-dev \
        libwebp libwebp-dev freetype freetype-dev \
        # ZIP + mbstring + intl deps
        libzip libzip-dev oniguruma oniguruma-dev \
        icu-libs icu-dev \
    # ── PHP extensions ────────────────────────────────────────
    && docker-php-ext-configure gd \
        --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql gd zip bcmath mbstring intl opcache pcntl \
    # ── Cleanup dev headers (kecilkan image) ──────────────────
    && apk del --no-cache \
        libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
        libzip-dev oniguruma-dev icu-dev \
    && rm -rf /var/cache/apk/* /tmp/*

# ── Composer ──────────────────────────────────────────────────
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ── PHP & FPM config ─────────────────────────────────────────
COPY docker/php/opcache.ini  /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/php-prod.ini /usr/local/etc/php/conf.d/php-prod.ini
COPY docker/php/www.conf     /usr/local/etc/php-fpm.d/www.conf

# ── App user ──────────────────────────────────────────────────
RUN addgroup -g 1000 www \
    && adduser -u 1000 -G www -s /bin/sh -D www

WORKDIR /var/www/html

# ── Composer install (cache layer) ────────────────────────────
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# ── Copy full source ──────────────────────────────────────────
COPY . .

# ── Post-copy setup ──────────────────────────────────────────
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
        public/assets/img/profil \
    && composer dump-autoload --optimize \
    && chown -R www:www storage bootstrap/cache public/assets/img \
    && chmod -R 775 storage bootstrap/cache

# ── Entrypoint ────────────────────────────────────────────────
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD php-fpm -t || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
