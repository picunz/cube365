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


RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


RUN a2enmod rewrite

WORKDIR /var/www/html
