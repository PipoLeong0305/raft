#!/usr/bin/env bash

# Start PHP-FPM
php-fpm -D

# Start Apache in the foreground
apache2ctl -D FOREGROUND