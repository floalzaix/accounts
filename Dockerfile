FROM php:8.2-apache

COPY . /var/www/html

RUN apt-get update && apt-get install -y libpq-dev

#Pour intl 
RUN apt-get install -y libicu-dev && docker-php-ext-install intl && apt-get clean 

RUN docker-php-ext-install pdo pdo_pgsql

EXPOSE 80