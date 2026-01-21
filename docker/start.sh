#!/bin/bash

echo "Running Laravel migrations..."
php artisan migrate --force

echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running seeders..."
php artisan db:seed --class=AdminSeeder --force

echo "Creating storage link..."
php artisan storage:link || true

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "Starting services..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
