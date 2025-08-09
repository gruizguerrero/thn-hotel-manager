FROM php:8.3.2-fpm-bookworm

ENV PHP_FPM_PM_MAX_CHILDREN=5
ENV PHP_FPM_PM_START_SERVERS=2
ENV PHP_FPM_PM_MIN_SPARE_SERVERS=1
ENV PHP_FPM_PM_MAX_SPARE_SERVERS=3
ENV PHP_FPM_PM_MAX_REQUESTS=0
ENV PHP_FPM_PROCESS_CONTROL_TIMEOUT=5s

ENV REALPATH_CACHE_SIZE=4096K
ENV REALPATH_CACHE_TTL=600
ENV OPCACHE_ENABLED=1
ENV OPCACHE_REVALIDATE_FREQ=0
ENV OPCACHE_MAX_ACCELERATED_FILES=20000
ENV OPCACHE_MEMORY_CONSUMPTION=256
ENV OPCACHE_INTERNED_STRINGS_BUFFER=16
ENV OPCACHE_FAST_SHUTDOWN=1
ENV OPCACHE_PRELOAD_USER=www-data
ENV OPCACHE_VALIDATE_TIMESTAMPS=1

ENV PHP_MAX_EXECUTION_TIME=30
ENV PHP_MAX_INPUT_TIME=60
ENV PHP_MEMORY_LIMIT=128M
ENV PHP_POST_MAX_SIZE=8M
ENV PHP_UPLOAD_MAX_FILESIZE=2M
ENV PHP_MAX_FILE_UPLOADS=20
ENV PHP_IDE_CONFIG "serverName=app"
ENV XDEBUG_MODE "debug"
ENV XDEBUG_START_WITH_REQUEST "trigger"
ENV XDEBUG_DISCOVER_CLIENT_HOST "1"

ENV DD_PROFILING_ENABLED=0

ENV AMQP_EXTENSION_VERSION=${AMQP_EXTENSION_VERSION:-1.11.0}
ENV REDIS_EXTENSION_VERSION=${REDIS_EXTENSION_VERSION:-5.3.7}

RUN rm -rf /etc/localtime \
    && ln -s /usr/share/zoneinfo/Europe/Madrid /etc/localtime

RUN apt-get update \
    && apt-get install --no-install-recommends -y \
    ca-certificates \
    librabbitmq-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    python3-pip \
    procps \
    git \
    automake \
    libfuse-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git-core \
    && rm -rf /var/lib/apt/lists/*

# Common php extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    intl \
    bcmath \
    sockets \
    opcache

RUN pecl install -f amqp-${AMQP_EXTENSION_VERSION} redis-${REDIS_EXTENSION_VERSION} \
    && docker-php-ext-enable amqp redis

# Add composer support
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN pecl install mongodb && pecl install -f xdebug-3.3
RUN docker-php-ext-enable mongodb xdebug
RUN docker-php-ext-install zip

RUN chmod 0777 /tmp

CMD ["php-fpm"]

WORKDIR "/var/www/app"

