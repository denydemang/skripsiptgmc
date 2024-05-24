#!/bin/bash

echo "Clearing Laravel caches..."

php artisan optimize
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan ser


echo "Laravel caches cleared successfully."
