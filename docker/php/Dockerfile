FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
        build-essential \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        locales \
        jpegoptim optipng pngquant gifsicle \
        libzip-dev \
        git \
        zip \
        unzip \
        curl \
        cron \
        nano

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql exif pcntl bcmath zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN cd ~

RUN curl -sL https://deb.nodesource.com/setup_14.x -o nodesource_setup.sh

RUN bash nodesource_setup.sh

RUN apt-get install -y nodejs