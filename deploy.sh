#!/usr/bin/bash

git fetch
git merge origin/master

php artisan migrate --path=database/migrations/custom
php artisan view:clear
