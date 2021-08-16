FROM php:7.4-fpm-buster

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    vim

RUN pecl install redis-5.1.1 \
    && pecl install xdebug-2.8.1 \
    && docker-php-ext-enable redis xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get install -y libicu-dev
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN pecl install xdebug

# configuration files
ADD conf/* /usr/local/etc/php/conf.d/
WORKDIR /var/www/html

RUN usermod -u 1000 www-data
RUN mkdir /var/www/.cache
RUN chown -R www-data:www-data /var/www/.cache
