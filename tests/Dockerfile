# https://hub.docker.com/_/php/
FROM php:7.2-alpine

RUN apk add --no-cache make openssl-dev && \
    apk add --no-cache --virtual .php-ext-deps autoconf g++ pcre-dev && \
    pecl channel-update pecl.php.net && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-source delete && \
    apk del .php-ext-deps

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN echo $'\
xdebug.remote_autostart=1\n\
xdebug.remote_enable=1\n\
xdebug.remote_host=host.docker.internal\n\
' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer && \
    curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig && \
    php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" && \
    php /tmp/composer-setup.php && \
    mv composer.phar /usr/local/bin/composer
