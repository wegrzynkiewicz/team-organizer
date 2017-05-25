FROM php:7.1.5-apache

RUN apt-get update && \
    apt-get install -y zlib1g-dev && \
    docker-php-ext-install zip && \
    pecl install xdebug-2.5.3 && \
    docker-php-ext-enable xdebug && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/bin --filename=composer

RUN a2enmod rewrite