FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY vite.config.js ./
RUN npm run build


FROM php:8.4-apache-bookworm

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    COMPOSER_ALLOW_SUPERUSER=1 \
    FFMPEG_BIN=/usr/bin/ffmpeg

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ffmpeg \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libzip-dev \
        unzip \
        curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    && a2enmod rewrite headers expires \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

COPY --from=frontend /app/public/build ./public/build

RUN composer dump-autoload --optimize \
    && mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/apache/000-default.conf \
    /etc/apache2/sites-available/000-default.conf

COPY docker/entrypoint.sh \
    /usr/local/bin/paz-entrypoint

RUN chmod +x /usr/local/bin/paz-entrypoint

EXPOSE 80

ENTRYPOINT ["paz-entrypoint"]

CMD ["apache2-foreground"]