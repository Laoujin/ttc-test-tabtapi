FROM php:8.4.6-apache-bullseye

RUN apt-get update && \
  apt-get install -y libxml2-dev && \
  docker-php-ext-install soap && \
  apt-get clean && rm -rf /var/lib/apt/lists/*
