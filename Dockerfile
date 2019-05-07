FROM php:7.3-cli

ENV EXT_ASYNC_REV master

RUN apt-get update -y && apt-get install -y gcc g++ libuv1 libuv1-dev libssl-dev autoconf automake libtool libjudy-dev htop

WORKDIR /ext-async
RUN curl -LSs https://github.com/concurrent-php/ext-async/tarball/${EXT_ASYNC_REV} | tar -xz -C "/ext-async" --strip-components 1
RUN phpize
RUN ./configure
RUN make install

RUN pecl install xdebug memprof && \
    docker-php-ext-enable xdebug memprof

COPY php.ini /usr/local/etc/php/conf.d/php.ini


