FROM php:8.2-apache


RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    pkg-config \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp

RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_sqlite

RUN docker-php-ext-install gd

RUN a2enmod rewrite



# Installa Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Aggiunge configurazione Xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini




WORKDIR /var/www/html

