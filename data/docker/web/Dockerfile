FROM php:7.1.7-apache

MAINTAINER Alexander Harchenko <morontt@gmail.com>

ARG HOST_UID=1000

VOLUME ["/var/www/html"]

ENV DEBIAN_FRONTEND noninteractive

RUN echo Europe/Kiev > /etc/timezone && \
    dpkg-reconfigure -f noninteractive tzdata

RUN curl -sL https://deb.nodesource.com/setup_4.x | bash -

RUN curl -sL https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add -
RUN echo "deb http://apt.postgresql.org/pub/repos/apt/ $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list

RUN apt-get update && apt-get install -y apt-utils \
    && apt-get install -y \
    zlib1g-dev libicu-dev libpq-dev \
    git nano zip \
    nodejs postgresql-client-9.6 \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install intl zip pgsql pdo_pgsql opcache

RUN npm install -g bower uglifycss uglify-js

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" \
    > /usr/local/etc/php/conf.d/xdebug.ini

COPY ./.bashrc /root/.bashrc
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /usr/local/etc/php/
RUN echo "LogFormat \"%a %l %u %t \\\"%r\\\" %>s %O \\\"%{User-Agent}i\\\"\" mainlog" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite remoteip

RUN curl -sS -o /root/.bash_aliases https://raw.githubusercontent.com/morontt/dotfiles/master/ubuntu/.bash_aliases \
    && sed -i "s/php app\/console/php bin\/console/g" /root/.bash_aliases \
    && sed -i "s/php-cs-fixer/php bin\/php-cs-fixer/g" /root/.bash_aliases

RUN set -x && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_UID} www-data

RUN chsh -s /bin/bash www-data \
    && cp /root/.bashrc /var/www \
    && cp /root/.bash_aliases /var/www

EXPOSE 80
