FROM php:8.2-fpm

ARG WWWGROUP=1000
ARG WWWUSER=1000

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

# Install system dependencies and PHP extensions in one layer
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sudo \
    apache2 \
    libapache2-mod-fcgid \
    nano \
    gnupg \
    supervisor \
    libcap2-bin \
    python3 \
    dnsutils \
    librsvg2-bin \
    fswatch \
    ffmpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js, npm, and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && npm install -g yarn \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy configuration files
COPY ./vendor/pipo/raft/docker/config/php/apache-config.conf /etc/apache2/sites-available/000-default.conf
COPY ./vendor/pipo/raft/docker/config/php/php.ini /usr/local/etc/php/conf.d/raft.ini
COPY ./vendor/pipo/raft/docker/config/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./vendor/pipo/raft/docker/config/php/start-container.sh /usr/local/bin/start-container

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user and set permissions
RUN groupadd --force -g $WWWGROUP raft && \
    useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER raft && \
    echo "raft ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/raft && \
    mkdir -p /var/www/html/writable && \
    chown -R raft:raft /var/www/html/writable && \
    chmod -R 775 /var/www/html/writable && \
    mkdir -p /var/run/apache2 /var/lock/apache2 /var/log/apache2 && \
    chown -R raft:raft /var/run/apache2 /var/lock/apache2 /var/log/apache2 && \
    chmod +x /usr/local/bin/start-container

# Set PHP-FPM and Apache to run as raft user
RUN sed -i "s/user = www-data/user = raft/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = raft/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/listen.owner = www-data/listen.owner = raft/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/listen.group = www-data/listen.group = raft/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=raft/g" /etc/apache2/envvars && \
    sed -i "s/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=raft/g" /etc/apache2/envvars

ENTRYPOINT ["start-container"]
