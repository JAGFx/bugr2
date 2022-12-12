FROM php:8.1-fpm

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt update -y \
    && apt upgrade -y \
    && apt full-upgrade -y \
    && apt dist-upgrade -y \
    && apt autoremove -y \
    && apt install zip unzip libzip-dev libpq-dev -y

RUN pecl install xdebug apcu

RUN docker-php-ext-install zip pdo pgsql pdo_mysql pdo_pgsql opcache \
    && docker-php-ext-enable xdebug apcu

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o php-cs-fixer \
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer


COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY conf.d/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
COPY conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

#RUN apt install curl git -y \
#    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
#    && apt install symfony-cli -y \
#    && curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
#    && apt install nodejs -y

WORKDIR /var/www/symfony