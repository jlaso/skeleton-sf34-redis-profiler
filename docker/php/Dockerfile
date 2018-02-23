# See https://github.com/docker-library/php/blob/master/7.1/fpm/Dockerfile
FROM php:7.1-fpm

ARG TIMEZONE
ARG USER_EMAIL
ARG USER_NAME
ENV USER_HOME /root

RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    nano \
    libicu-dev \
    mysql-client \
    figlet

RUN mkdir -p /usr/local/etc/php \
    && mkdir -p /tmp/xdebug_log

ADD ./php.ini /usr/local/etc/php/php.ini
ADD ./.gitconfig ${USER_HOME}
ADD ./.bashrc ${USER_HOME}
ADD ./git-completion.bash ${USER_HOME}

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version \
#    && composer require "instrumental/instrumental_agent" \
    && ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date" \
    && docker-php-ext-install pdo pdo_mysql bcmath intl \
    && pecl install -o -f redis \
    && pecl install xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis xdebug \
    && mkdir -p app/data \
    && chown -R www-data:www-data app/data \
    && chmod -R 777 app/data \
    && git config --global user.email ${USER_EMAIL} \
    && git config --global user.name ${USER_NAME}
    #\
    #&& echo "<?php require_once __DIR__.'/vendor/autoload'; \$I = new InstrumentalAgent(); \$I->setApiKey("${INSTRUMENTAL_API}"); return \$I;" > /var/www/web/instrumental.php


COPY xdebug.ini     /tmp/xdebug.ini
# RUN echo "xdebug.remote_host="`/sbin/ip route|awk '/default/ { print $3 }'`"\n" >> /tmp/xdebug.ini \
RUN cat /tmp/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

EXPOSE 9000 9001

WORKDIR /var/www/web

# RUN mkdir -p app/cache app/logs && chown -R www-data:nogroup app/cache app/logs
