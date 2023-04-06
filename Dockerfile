FROM ccr.ccs.tencentyun.com/yike/server-core:0.1.0

WORKDIR /data/www

COPY . .

RUN chown -R www-data:www-data /data/www

ENTRYPOINT [ "/usr/local/bin/start-all" ]
