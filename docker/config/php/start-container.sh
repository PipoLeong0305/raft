#!/usr/bin/env bash

# Fix permissions for runtime directories
if [ "$(id -u)" = "0" ]; then
  chmod -R 775 /var/run/apache2 /var/lock/apache2 /var/log/apache2
  chown -R raft:raft /var/run/apache2 /var/lock/apache2 /var/log/apache2 /var/www/html
  
  # Start PHP-FPM
  php-fpm -D
  
  # Start Apache in the foreground
  exec apache2ctl -D FOREGROUND
else
  # Already running as non-root user
  php-fpm -D
  exec apache2ctl -D FOREGROUND
fi