ARG NGINX_VERSION=1
ARG PHP_VERSION=7.4

FROM php:${PHP_VERSION}-fpm-alpine AS base
ENV PATH=$PATH:/app/bin
WORKDIR /app
RUN apk add --no-cache \
    libpng-dev \
    mariadb-client \
  && docker-php-ext-install \
    bcmath \
    gd \
    pdo_mysql

FROM base AS dev
RUN apk add --no-cache \
  git \
  unzip
COPY --from=composer:1 /usr/bin/composer /usr/bin/composer
WORKDIR /app
#COPY composer.* ./
#RUN composer validate --strict
#RUN composer install
#COPY . .

FROM nginx:${NGINX_VERSION}-alpine AS nginx
WORKDIR /app
COPY tools/docker/images/nginx/root /
