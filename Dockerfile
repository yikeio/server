FROM yikeio/server-core:latest

WORKDIR /data/www

COPY . .

RUN chown -R www-data:www-data /data/www

ENTRYPOINT [ "/usr/local/bin/start-all" ]
