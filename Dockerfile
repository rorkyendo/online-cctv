# ============================================================
# Online CCTV — Laravel App (PHP 8.2-FPM)
# Multi-stage: builder (composer + npm) → runtime (fpm-alpine)
# ============================================================

# ── Stage 1: Builder ─────────────────────────────────────────
FROM php:8.2-fpm-alpine AS builder

# Install build-time deps
RUN apk add --no-cache \
        bash \
        curl \
        git \
        unzip \
        libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        freetype-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        gd \
        zip \
        bcmath \
        mbstring \
        intl \
        opcache \
        pcntl

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first (cache layer)
COPY composer.json composer.lock ./

# Install PHP deps — no dev, optimized autoloader
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy full source
COPY . .

# Pastikan direktori cache ada dan writable sebelum post-install scripts
RUN mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions \
        storage/framework/views storage/logs \
    && chmod -R 777 bootstrap/cache storage

# Run post-install scripts
RUN composer dump-autoload --optimize

# ── Stage 2: Runtime ─────────────────────────────────────────
FROM php:8.2-fpm-alpine AS runtime

# Runtime deps only
RUN apk add --no-cache \
        libpng \
        libjpeg-turbo \
        libwebp \
        freetype \
        libzip \
        oniguruma \
        icu-libs \
        curl \
        su-exec

# Copy compiled PHP extensions from builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d    /usr/local/etc/php/conf.d

# PHP production config
COPY docker/php/opcache.ini  /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/php-prod.ini /usr/local/etc/php/conf.d/php-prod.ini

# PHP-FPM pool config (workers sebagai user www)
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

# Copy built app
COPY --from=builder /var/www/html /var/www/html

# Non-root user — container tetap root, FPM workers pakai www via pool config
RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www

# Permissions awal (volume akan di-chown saat runtime oleh entrypoint)
RUN mkdir -p public/assets/img/profil \
    && chown -R www:www storage bootstrap/cache public/assets/img \
    && chmod -R 775 storage bootstrap/cache

# Entrypoint script
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD php-fpm -t || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
