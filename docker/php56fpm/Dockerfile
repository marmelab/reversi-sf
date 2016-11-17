FROM php:5.6.28-fpm

RUN apt-get update && apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  libpng12-dev \
  libssl-dev \
  libcurl4-gnutls-dev \
  libxml2-dev \
  libicu-dev

RUN docker-php-ext-install \
    iconv json mcrypt mbstring phar curl ftp hash session simplexml tokenizer xml zip intl opcache pdo pdo_mysql \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    # Install Composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version \
    # Set docker timezone
    && rm /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && "date"

WORKDIR app/
