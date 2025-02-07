FROM composer:2.5.8 AS composer

FROM php:8.1-fpm
LABEL authors="DNA"

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# install all required php extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libgmp-dev \
    libzip-dev \
    zip \
    postgresql-client \
    unzip


RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo pdo_pgsql bcmath opcache exif gmp zip

# Install Redis extension
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

COPY ./bashrc /root/.bashrc
CMD ["/bin/sh", "-c", "composer install && php artisan migrate && php artisan db:seed && php artisan key:generate && php-fpm"]

EXPOSE 9000
