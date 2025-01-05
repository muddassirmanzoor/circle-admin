FROM alpine:latest

WORKDIR /var/www/html/

# Essentials
RUN echo "UTC" > /etc/timezone
RUN apk add --no-cache zip unzip curl sqlite nginx supervisor
RUN apk update && apk upgrade

# Installing bash
RUN apk add bash
RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

# Installing PHP
RUN apk add --no-cache php82 \
    php82-common \
    php82-fpm \
    php82-pdo \
    php82-opcache \
    php82-zip \
    php82-phar \
    php82-iconv \
    php82-gd \
    php82-intl \
    php82-xsl \
    php82-cli \
    php82-curl \
    php82-openssl \
    php82-mbstring \
    php82-tokenizer \
    php82-fileinfo \
    php82-json \
    php82-xml \
    php82-xmlwriter \
    php82-xmlreader \
    php82-simplexml \
    php82-dom \
    php82-pdo_mysql \
    php82-pdo_sqlite \
    php82-tokenizer \
    php82-pecl-redis \
    php82-xmlreader

RUN ln -s /usr/bin/php82 /usr/bin/php

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

# Configure supervisor
RUN mkdir -p /etc/supervisor.d/
COPY .docker/supervisord.ini /etc/supervisor.d/supervisord.ini

# Configure PHP
RUN mkdir -p /run/php/
RUN touch /run/php/php8.2-fpm.pid

COPY .docker/php-fpm.conf /etc/php82/php-fpm.conf
COPY .docker/php.ini-production /etc/php82/php.ini

# Configure nginx
COPY .docker/nginx.conf /etc/nginx/
COPY .docker/nginx-laravel.conf /etc/nginx/http.d/

RUN mkdir -p /run/nginx/
RUN touch /run/nginx/nginx.pid

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

# Building process
COPY . .
ARG environment
COPY ./envs/.${environment}.env .env
RUN rm -R ./envs

RUN composer update
RUN composer install --no-dev -v
RUN chown -R nobody:nobody /var/www/html/storage
RUN mv /etc/nginx/http.d/default.conf /etc/nginx/http.d/default.conf.old

# Create TEMP Folder for NGINX
RUN mkdir /tmp/nginx
RUN chown -R nobody:nobody /tmp/nginx
RUN chmod -R 7777 /tmp/nginx
RUN chown -vR nobody:nobody /var/lib/nginx/

RUN chmod +x start.sh
RUN chown -R nobody:nobody start.sh

EXPOSE 80

RUN mv start.sh /start.sh

CMD ["/start.sh"]
