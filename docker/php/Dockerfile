FROM php:8.2-fpm

ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    libicu-dev curl libjpeg-dev libfreetype6-dev zlib1g-dev \
  && docker-php-ext-configure gd --with-jpeg --with-freetype \
  && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

RUN pecl install redis && docker-php-ext-enable redis

# composer desde imagen oficial
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${GID} app && useradd -u ${UID} -g app -m app

WORKDIR /var/www/html

# copiar composer para cache de build (opcional)
COPY composer.json composer.lock* /var/www/html/
RUN composer install --no-interaction --no-progress || true

USER app
EXPOSE 9000
CMD ["php-fpm"]
