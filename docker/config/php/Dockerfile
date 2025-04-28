FROM php:8.2-fpm

ARG WWWGROUP=1000
ARG WWWUSER=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
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
    nano

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Configure Apache
RUN a2enmod rewrite proxy_fcgi setenvif

# Set working directory
WORKDIR /var/www/html

# Configure Apache for PHP-FPM
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN groupadd raft || true && \
    useradd -g raft -G root -u $WWWUSER -d /home/raft raft
RUN mkdir -p /home/raft/.composer && \
    chown -R raft:raft /home/raft

# Set up sudo for the raft user
RUN echo "raft ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/raft

# Allow raft to have proper permissions
RUN usermod -u $WWWUSER raft && \
    groupmod -g $WWWGROUP raft

# Set up Apache to run as the proper user
RUN sed -i "s/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=raft/g" /etc/apache2/envvars && \
    sed -i "s/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=raft/g" /etc/apache2/envvars

# Ensure writable directory permissions for raft user
RUN mkdir -p /var/www/html/writable && \
    chown -R raft:raft /var/www/html/writable && \
    chmod -R 775 /var/www/html/writable

# Configure PHP-FPM to run as raft user
RUN sed -i "s/user = www-data/user = raft/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = raft/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/listen.owner = www-data/listen.owner = raft/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/listen.group = www-data/listen.group = raft/g" /usr/local/etc/php-fpm.d/www.conf

# Copy the PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/raft.ini

# Set up entrypoint
COPY start-container.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

# Set proper ownership for Apache directories
RUN chown -R raft:raft /var/run/apache2 /var/lock/apache2 /var/log/apache2

ENTRYPOINT ["start-container"]