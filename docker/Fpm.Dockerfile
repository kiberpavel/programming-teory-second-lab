FROM php:8.0-fpm

RUN apt-get update && apt-get install -y libpq-dev git libzip-dev zip supervisor libgmp-dev
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql
RUN pecl install xdebug \
    && docker-php-ext-install zip \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install gmp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/app

ADD . /var/www/app

RUN chmod +x docker/init_app.sh

ADD docker/conf/www.conf /etc/php/8.0/fpm/pool.d/www.conf
RUN mkdir -p /var/run/php


ADD docker/conf/php.ini /etc/php/8.0/fpm/php.ini
ADD docker/conf/php-fpm.conf /etc/php/8.0/fpm/php-fpm.conf

RUN mkdir -p /var/log/supervisor
ADD docker/conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN composer i --no-dev

EXPOSE 9000

CMD ["docker/init_app.sh"]
