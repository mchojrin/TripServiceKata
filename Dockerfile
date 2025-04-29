FROM php:7.2.34-cli-alpine
LABEL authors="Mauro Chojrin"

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk update \
    && apk upgrade \
    && mkdir "/app/" \
    && wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet \
    && mv composer.phar /usr/local/bin/composer  \
    && apk add --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
    && install-php-extensions mysqli xdebug-3.1.0 \
    && apk del .build-dependencies  \
    && echo "xdebug.mode = coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /app