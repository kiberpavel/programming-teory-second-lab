#!/bin/bash

sleep 10

echo 'start sh script...'

touch /var/www/app/storage/logs/laravel.log
chmod 777 /var/www/app/storage/logs/laravel.log
chmod -R 777 /var/www/app/storage/framework/views
chmod -R 777 /var/www/app/storage/framework/sessions

exec "php-fpm"

/usr/bin/supervisord
