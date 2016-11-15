FROM php:5.6-fpm-alpine

RUN docker-php-ext-install \
        iconv json mcrypt mbstring phar curl ftp hash session simplexml tokenizer xml zip intl opcache pdo pdo_mysql \
    # Install Composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version \
    # Set docker timezone
    && rm /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && "date"

WORKDIR app/
