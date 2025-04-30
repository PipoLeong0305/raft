#!/usr/bin/env bash

# Fix permissions for runtime directories
if [ "$(id -u)" = "0" ]; then
  chmod -R 775 /var/run/apache2 /var/lock/apache2 /var/log/apache2
  chown -R raft:raft /var/run/apache2 /var/lock/apache2 /var/log/apache2 /var/www/html
  
  # Print Node.js, npm, and Yarn versions
  echo "Node.js version: $(node -v)"
  echo "npm version: $(npm -v)"
  echo "Yarn version: $(yarn -v)"
  
  # Start PHP-FPM
  php-fpm -D
  
  # Start Apache in the foreground
  exec apache2ctl -D FOREGROUND
else
  # Already running as non-root user
  php-fpm -D
  exec apache2ctl -D FOREGROUND
fi

if [ ! -z "$WWWUSER" ]; then
    usermod -u $WWWUSER raft
fi

if [ ! -d /.composer ]; then
    mkdir /.composer
fi

chmod -R ugo+rw /.composer

if [ $# -gt 0 ]; then
    exec gosu raft "$@"
else
    exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi