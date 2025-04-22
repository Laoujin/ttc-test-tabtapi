FROM php:8.4.6-apache-bullseye

RUN apt-get update
RUN apt-get install -y libxml2-dev
# RUN apt-get install -y l<ibssl-dev

RUN docker-php-ext-install soap
# RUN docker-php-ext-install openssl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# COPY . /var/www/html/

# RUN chown -R www-data:www-data /var/www/html
