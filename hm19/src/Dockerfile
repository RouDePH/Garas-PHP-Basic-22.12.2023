FROM php:8-fpm

RUN apt-get update && \
    apt-get install -y git && \
    apt-get clean && \
    docker-php-ext-install pdo_mysql