FROM php:8.4-fpm

ARG WORKDIR
WORKDIR ${WORKDIR}

RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev libxpm-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY . .

RUN composer install --no-interaction

EXPOSE 9000

CMD ["php-fpm"]
