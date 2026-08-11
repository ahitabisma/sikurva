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
    freetype libpng libjpeg-turbo oniguruma libzip icu \
    && apk add --no-cache --virtual .build-deps \
    freetype-dev libpng-dev libjpeg-turbo-dev oniguruma-dev libzip-dev icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql gd mbstring zip bcmath intl \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --ignore-platform-req=ext-gd --ignore-platform-req=ext-zip

COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

EXPOSE 9000
CMD ["php-fpm"]
