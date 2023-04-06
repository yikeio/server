# syntax=docker/dockerfile:1

FROM debian:stable

ENV EDITOR=vim \
  PHP_FPM_PM_MAX_CHILDREN=10 \
  LARAVEL_DEFAULT_WORKER_COUNT=2

COPY . /data/www/docker

RUN mkdir -p /data/logs/nginx /data/logs/php /data/www/storage/logs /var/run/php /data/certs \
    && touch /data/logs/cron.log \
    && chmod 777 /data/logs/cron.log

RUN apt update -y \
    && apt upgrade -y \
    && apt install -y curl wget unzip vim \
    lsb-release ca-certificates apt-transport-https software-properties-common gnupg2 \
    sudo \
    && mkdir -p /etc/apt/gpg-keys \
    && curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /etc/apt/gpg-keys/php-apt.gpg \
    && echo "deb [signed-by=/etc/apt/gpg-keys/php-apt.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/sury-php.list \
    && apt update -y \
    && apt install -y nginx cron supervisor \
    && apt install -y php8.1 php8.1-cli php8.1-fpm \
    && apt install -y php8.1-mbstring php8.1-xml php8.1-bcmath php8.1-curl \
    php8.1-gd php8.1-mysql php8.1-opcache php8.1-zip php8.1-sqlite3 \
    php8.1-redis php8.1-gmp php8.1-soap \
    && echo "Asia/Shanghai" > /etc/timezone \
    && rm /etc/localtime  \
    && ln -s /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && apt clean \
    && apt autoremove -y \
    && sh /data/www/docker/cert/ca.sh

RUN rm -f /etc/nginx/sites-enabled/* \
    && cp -f /data/www/docker/config/nginx/nginx.conf /etc/nginx \
    && cp -f /data/www/docker/config/nginx/www.conf /etc/nginx/sites-enabled \
    && cp -f /data/www/docker/config/php/cli/* /etc/php/8.1/cli \
    && cp -f /data/www/docker/config/php/fpm/* /etc/php/8.1/fpm \
    && cp -f /data/www/docker/config/supervisord/* /etc/supervisor/conf.d \
    && cp -f /data/www/docker/config/cron.d/* /etc/cron.d \
    && cp -f /data/www/docker/script/* /usr/local/bin \
    && chmod +x /usr/local/bin/*

USER root:root

EXPOSE 80
