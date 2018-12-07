#!/usr/bin/bash

composer install

git fetch
git merge origin/master

php artisan migrate --path=database/migrations/custom
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
