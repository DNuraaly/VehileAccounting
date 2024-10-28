FROM composer:2.5.8 AS composer

FROM php:8.1-fpm
LABEL authors="DNA"

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# install all required php extensions
RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libgmp-dev libzip-dev


RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath pdo_mysql opcache exif gmp zip
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

RUN apt-get update \
&& apt-get install -y zip unzip libzip-dev default-mysql-client \
&& docker-php-ext-install zip pdo_mysql

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# Install Xdebug 3
#RUN pecl install xdebug-3.0.0 \
#    && docker-php-ext-enable xdebug

COPY ./bashrc /root/.bashrc

#COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#ARG ENABLE_XDEBUG
#RUN if [ "$ENABLE_XDEBUG" != "true" ]; then \
#    rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
#fi

CMD ["/bin/sh", "-c", "composer install && php artisan migrate && php artisan db:seed && php artisan key:generate && php-fpm "]
