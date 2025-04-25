FROM php:8.1-apache

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
    sudo

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Configure Apache
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Configure Apache document root
RUN sed -i 's/DocumentRoot \/var\/www\/html/DocumentRoot \/var\/www\/html\/public/g' /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -G www-data,root -u $WWWUSER -d /home/raft raft
RUN mkdir -p /home/raft/.composer && \
    chown -R raft:raft /home/raft

# Set up sudo for the raft user
RUN echo "raft ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/raft

# Allow www-data to have proper permissions
RUN usermod -u $WWWUSER www-data && \
    groupmod -g $WWWGROUP www-data

# Set up Apache to run as the proper user
RUN sed -i "s/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=raft/g" /etc/apache2/envvars && \
    sed -i "s/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=raft/g" /etc/apache2/envvars

# Copy the PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/raft.ini

# Set up entrypoint
COPY start-container.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

ENTRYPOINT ["start-container"]