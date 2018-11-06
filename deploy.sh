#!/usr/bin/bash

composer install

git fetch
git merge origin/master

php artisan migrate --path=database/migrations/custom
php artisan view:clear
