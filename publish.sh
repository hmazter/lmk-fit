#!/bin/sh

git pull origin master --tags

sudo ./composer.phar install --no-dev

sudo chmod -R 777 storage

php artisan migrate --force

php artisan cache:clear
