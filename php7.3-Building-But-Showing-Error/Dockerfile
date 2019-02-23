FROM php:7.3-fpm as builder

RUN set -ex; \
    \
    apt-get update; \
    apt-get install -my --no-install-recommends \
        autoconf \
        build-essential \
        g++ \
        jq \
        libbsd-dev \
        libbz2-dev \
        libc-client2007e-dev \
        libc-dev \
        libcurl3 \
        libcurl3-dev \
        libcurl4-openssl-dev \
        libedit-dev \
        libedit2 \
        libfreetype6-dev \
        libgmp-dev \
        libicu-dev \
        libjpeg-dev \
        libjpeg62-turbo-dev \
        libkrb5-dev \
        libldap2-dev \
        libmagick++-dev \
        libmagickwand-dev \
        libmcrypt-dev \
        libmemcached-dev \
        libevent-dev \
        libpcre3-dev \
        libpng-dev \
        libsqlite3-0 \
        libsqlite3-dev \
        libssh2-1-dev \
        libssl-dev \
        libtinfo-dev \
        libtool \
        libwebp-dev \
        libxml2 \
        libxml2-dev \
        libxpm-dev \
        libxslt1-dev \
        libzip4 \
        libzip-dev \
        openssl \
        unzip \
        zlib1g-dev \
    ; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*; \
    \
    docker-php-ext-configure gd \
        --with-png-dir=/usr \
        --with-jpeg-dir=/usr \
        --with-freetype-dir=/usr \
        --with-xpm-dir=/usr \
        --with-webp-dir=/usr \
        --with-vpx-dir=/usr; \
    docker-php-ext-install \
        bcmath \
        bz2 \
        calendar \
        dba \
        exif \
        gd \
        gettext \
        gmp \
        iconv \
        intl \
        mbstring \
        mysqli \
        opcache \
        pdo_mysql \
        shmop \
        soap \
        sockets \
        sysvmsg \
        sysvsem \
        sysvshm \
        wddx \
        xmlrpc \
        xsl \
        zip \
    ; \
    pecl install \
        igbinary-3.0.0 \
        imagick-3.4.3 \
        memcached-3.1.3 \
        msgpack-2.0.3 \
        redis-4.2.0 \
    ; \
#    TODO: Enable this extension when it supports PHP 7.3
#    echo "\n" | pecl install ssh2-1.1.2; \
    docker-php-ext-enable --ini-name pecl.ini \
        igbinary \
        imagick \
        memcached \
        msgpack \
        redis \
#    TODO: Enable this extension when it supports PHP 7.3
#        ssh2 \
    ;

# Install APCu extension
ADD https://pecl.php.net/get/apcu-5.1.17.tgz /tmp/apcu.tar.gz
RUN mkdir -p /usr/src/php/ext/apcu && tar xf /tmp/apcu.tar.gz -C /usr/src/php/ext/apcu --strip-components=1
RUN docker-php-ext-configure apcu && docker-php-ext-install apcu
RUN rm -rd /usr/src/php/ext/apcu && rm /tmp/apcu.tar.gz

# Install APCu-BC extension
ADD https://pecl.php.net/get/apcu_bc-1.0.5.tgz /tmp/apcu_bc.tar.gz
RUN mkdir -p /usr/src/php/ext/apcu-bc && tar xf /tmp/apcu_bc.tar.gz -C /usr/src/php/ext/apcu-bc --strip-components=1
RUN docker-php-ext-configure apcu-bc && docker-php-ext-install apcu-bc
RUN rm -rd /usr/src/php/ext/apcu-bc && rm /tmp/apcu_bc.tar.gz
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini
RUN echo extension=apcu.so > /usr/local/etc/php/conf.d/20-php-ext-apcu.ini
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-apc.ini
RUN echo extension=apc.so > /usr/local/etc/php/conf.d/21-php-ext-apc.ini

# Install xdebug
RUN pecl install xdebug-2.7.0RC2
RUN docker-php-ext-enable xdebug

# Now that all the modules are built/downloaded, use the original php:7.3-fpm image and
# install only the runtime dependencies with the new modules and config files.
FROM php:7.3-fpm

WORKDIR /

RUN set -ex ; \
    \
    apt-get update && apt-get install -y --no-install-recommends \
        cron \
        git \
        ghostscript \
        imagemagick \
        libc-client2007e \
        libicu57 \
        libmagickwand-6.q16-3 \
        libmcrypt4 \
        libmemcached11 \
        libmemcachedutil2 \
        libvpx-dev \
        libwebp-dev \
        libxpm-dev \
        libxslt1.1 \
        ssmtp \
        sudo \
        libpng16-16 \
        ; \
    rm -rf /tmp/pear /usr/share/doc /usr/share/man /usr/lib/gcc /var/lib/apt/lists/*; \
    cd /usr/local/etc/php; \
    php-fpm -v 2>/dev/null | sed -E 's/PHP ([5|7].[0-9]{1,2}.[0-9]{1,2})(.*)/\1/g' | head -n1 > php_version.txt;

COPY --from=builder /usr/local/lib/php/extensions/no-debug-non-zts-20180731/ /usr/local/lib/php/extensions/no-debug-non-zts-20180731/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Install composer and put binary into $PATH
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/ \
  && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

# Bash
COPY .profile /root/.profile
COPY .profile /root/.bashrc

RUN set -ex \
    && { \
        echo '[global]'; \
        echo 'daemonize = no'; \
        echo 'error_log = /proc/self/fd/2'; \
        echo; \
        echo '[www]'; \
        echo 'listen = [::]:9000'; \
        echo 'listen.owner = www-data'; \
        echo 'listen.group = www-data'; \
        echo; \
        echo 'user = www-data'; \
        echo 'group = www-data'; \
        echo; \
        echo 'access.log = /proc/self/fd/2'; \
        echo; \
        echo 'pm = static'; \
        echo 'pm.max_children = 1'; \
        echo 'pm.start_servers = 1'; \
        echo 'request_terminate_timeout = 65s'; \
        echo 'pm.max_requests = 1000'; \
        echo 'catch_workers_output = yes'; \
    } | tee /usr/local/etc/php-fpm.d/www.conf \
    && { \
        echo 'FromLineOverride=YES'; \
        echo 'mailhub=127.0.0.1'; \
        echo 'UseTLS=NO'; \
        echo 'UseSTARTTLS=NO'; \
    } | tee /etc/ssmtp/ssmtp.conf \
        && { \
        echo '[PHP]'; \
        echo 'log_errors = On'; \
        echo 'error_log = /dev/stderr'; \
    } | tee /usr/local/etc/php/conf.d/php.ini \
    ;

EXPOSE 9000

CMD ["php-fpm"]
