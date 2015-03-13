#!/bin/sh

git pull origin master --tags

sudo ./composer.phar install

sudo chmod -R 755 storage

php artisan migrate --force

php artisan cache:clear
