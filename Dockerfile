# ---------- Build stage: frontend (Vite) ----------
FROM node:20-bookworm-slim AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY public ./public
RUN npm run build

# ---------- Runtime stage: PHP + Apache ----------
FROM php:8.2-apache

# System deps + PHP extensions required by Laravel 12 + MySQL
# (curl is used by the compose-level HEALTHCHECK against /up)
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip zip curl libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
        libicu-dev libonig-dev libxml2-dev default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql bcmath intl zip gd exif pcntl opcache \
    && a2enmod rewrite headers \
    && apt-get autoremove -y && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Use PHP's production defaults, then apply local tuning on top
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# PHP first: install deps (cached layer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# App source
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Apache vhost + PHP tuning
COPY .docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
