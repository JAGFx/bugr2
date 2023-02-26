FROM php:8.4-fpm AS base

LABEL org.opencontainers.image.source=https://github.com/JAGFx/bugr2
LABEL org.opencontainers.image.licenses=MIT

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt update -y \
    && apt upgrade -y \
    && apt full-upgrade -y \
    && apt dist-upgrade -y \
    && apt autoremove -y \
    && apt install zip unzip libzip-dev libicu-dev -y

RUN pecl install apcu

RUN docker-php-ext-install zip pdo pdo_mysql opcache intl \
    && docker-php-ext-enable apcu

COPY conf.d/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
COPY conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/symfony

FROM base AS dev

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o php-cs-fixer \
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

FROM base AS prod

COPY . /var/www/symfony