# Base image: PHP 8.2 with FPM
FROM php:8.3-fpm

ARG WWWGROUP

# Create 'raft' group and user
RUN groupadd --force -g ${WWWGROUP} raft && \
    useradd -ms /bin/bash --no-user-group -g ${WWWGROUP} -u 1000 raft

# Update PHP-FPM configuration
RUN sed -i \
    -e 's/^user = www-data/user = raft/' \
    -e 's/^group = www-data/group = raft/' \
    /usr/local/etc/php-fpm.d/www.conf

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nano \
    wget \
    dialog \
    git \
    curl \
    zip \
    openssl \
    default-mysql-client \
    libcurl4-openssl-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libmagickwand-dev \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    curl \
    zip \
    intl \
    mbstring

# Install and enable Redis extension
RUN pecl install redis-6.0.2 && \
    docker-php-ext-enable redis

# Install and configure GD extension
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install ImageMagick PHP extension
ARG IMAGICK_COMMIT="28f27044e435a2b203e32675e942eb8de620ee58"
RUN cd /usr/local/src && \
    git clone https://github.com/Imagick/imagick && \
    cd imagick && \
    git checkout ${IMAGICK_COMMIT} && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    cd .. && \
    rm -rf imagick && \
    docker-php-ext-enable imagick

# Install Node.js LTS, npm, and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g yarn

# Final cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*