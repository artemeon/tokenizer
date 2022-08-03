FROM php:8.1-fpm-alpine

COPY xdebug.ini $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini

RUN apk add --no-cache $PHPIZE_DEPS curl \
    && docker-php-source extract \
    && pecl install redis-5.3.7 \
    && pecl install xdebug-3.1.5 \
    && docker-php-ext-enable redis xdebug \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-source delete \
    && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/*
