# syntax=docker/dockerfile:1

# ---- 1. Composer dependencies (PHP 8.3 with intl + ext-zip + ext-gd, etc.) ----
FROM serversideup/php:8.3-cli AS composer-deps
USER root
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --no-progress

# ---- 2. Frontend assets (Vite) ----
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY . .
COPY --from=composer-deps /app/vendor ./vendor
RUN npm run build

# ---- 3. Runtime image ----
FROM serversideup/php:8.3-fpm-nginx

USER root
WORKDIR /var/www/html

# Application code
COPY --chown=www-data:www-data . .
# Composer vendor
COPY --from=composer-deps --chown=www-data:www-data /app/vendor ./vendor
# Built frontend assets
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

# Optimize autoload and finalize install
RUN composer dump-autoload --optimize --no-interaction --classmap-authoritative \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && php artisan storage:link || true

# serversideup/php runs migrations + config cache on start when these are set
ENV AUTORUN_ENABLED=true \
    PHP_OPCACHE_ENABLE=1 \
    SSL_MODE=off

USER www-data
EXPOSE 8080
