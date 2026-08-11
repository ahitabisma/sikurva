# Stage 1: Frontend build
FROM node:22-alpine AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources/css resources/css
COPY resources/js resources/js
RUN npm run build

# Stage 2: PHP runtime
FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        freetype libpng libjpeg-turbo oniguruma libzip icu curl libxml2 \
    && apk add --no-cache --virtual .build-deps \
        freetype-dev libpng-dev libjpeg-turbo-dev oniguruma-dev libzip-dev icu-dev curl-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql gd mbstring zip bcmath intl curl exif opcache \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Tahap 1: install dependency saja, tanpa menjalankan script/autoload
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --no-scripts --no-autoloader

# Copy seluruh source code aplikasi
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Tahap 2: generate autoloader + jalankan script post-install
RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi

# Pastikan struktur folder storage & cache selalu ada,
# karena folder ini sering kosong di Git dan tidak ikut ter-copy
RUN mkdir -p storage/framework/cache \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        storage/app/public \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

EXPOSE 9000
CMD ["php-fpm"]