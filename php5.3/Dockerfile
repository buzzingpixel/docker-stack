FROM helder/php-5.3 as builder

COPY docker-php-ext-enable /usr/local/bin/

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
        libvpx-dev \
        libwebp-dev \
        libxml2 \
        libxml2-dev \
        libxpm-dev \
        libxslt1-dev \
        openssl \
        unzip \
        zlib1g-dev \
    ; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*; \
    \
    ln -s /usr/lib/x86_64-linux-gnu/libXpm.a /usr/lib/libXpm.a; \
    mkdir -p /usr/include/freetype2/freetype; \
    ln -s /usr/include/freetype2/freetype.h /usr/include/freetype2/freetype/freetype.h; \
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
        iconv \
        intl \
        mbstring \
        mysqli \
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
        igbinary-2.0.1 \
        imagick-3.3.0 \
    ; \
    echo "\n" | pecl install ssh2-0.13; \
    docker-php-ext-enable \
        igbinary \
        imagick \
        ssh2 \
    ;

# Install xdebug
RUN pecl install xdebug-2.2.7
RUN docker-php-ext-enable xdebug

# Now that all the modules are built/downloaded, use the original php:7.2-fpm image and
# install only the runtime dependencies with the new modules and config files.
FROM helder/php-5.3

WORKDIR /

RUN set -ex ; \
    \
    apt-get update && apt-get install -y --no-install-recommends \
        cron \
        git \
        libc-client2007e \
        libmagickwand-dev \
        libmcrypt4 \
        libmemcached11 \
        libmemcachedutil2 \
        libvpx-dev \
        libwebp-dev \
        libxpm-dev \
        libxslt1.1 \
        ssmtp \
        sudo \
        libpng-dev \
        ; \
    rm -rf /tmp/pear /usr/share/doc /usr/share/man /usr/lib/gcc /var/lib/apt/lists/*; \
    cd /usr/local/etc/php; \
    php-fpm -v 2>/dev/null | sed -E 's/PHP ([5|7].[0-9]{1,2}.[0-9]{1,2})(.*)/\1/g' | head -n1 > php_version.txt;

COPY --from=builder /usr/local/lib/php/extensions/no-debug-non-zts-20090626/ /usr/local/lib/php/extensions/no-debug-non-zts-20090626/
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
        echo 'listen = 9000'; \
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
    } | tee /usr/local/etc/php-fpm.conf \
    && { \
        echo 'FromLineOverride=YES'; \
        echo 'mailhub=125.6.0.1'; \
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
