FROM php:7.2-fpm

RUN apt-get update && apt-get install -y curl git

RUN pecl install xdebug && \
docker-php-ext-enable xdebug
RUN apt-get install -y \
zlib1g-dev \
&& docker-php-ext-install zip

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /slavemarket