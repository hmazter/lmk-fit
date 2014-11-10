#!/bin/sh

git pull origin master --tags

sudo ./composer.phar install

sudo chown -R www-data:www-data storage

php artisan migrate --force

php artisan cache:clear
