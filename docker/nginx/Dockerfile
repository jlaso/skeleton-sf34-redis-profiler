FROM debian:jessie

RUN apt-get update && apt-get install -y \
    nginx

ADD nginx.conf /etc/nginx/
ADD web.conf /etc/nginx/sites-available/

RUN rm /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/web.conf /etc/nginx/sites-enabled/web \
    && echo "upstream php-upstream { server php_fpm:9000; }" > /etc/nginx/conf.d/upstream.conf \
    && usermod -u 1000 www-data \
    && echo 'alias ll="ls -la"' >> ~/.bashrc\
    && mkdir /var/www/web

CMD ["nginx"]

WORKDIR /var/www/web

EXPOSE 80
