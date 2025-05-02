FROM php:8.4.6-apache-bullseye

RUN apt-get update && \
  apt-get install -y libxml2-dev && \
  docker-php-ext-install soap && \
  apt-get clean && rm -rf /var/lib/apt/lists/*

COPY ./src /var/www/html

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
