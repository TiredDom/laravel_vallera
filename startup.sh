#!/bin/bash
echo "Starting Vallera Laravel application..."

cd /home/site/wwwroot

if [ ! -f "artisan" ]; then
    echo "ERROR: Laravel files not found!"
    exit 1
fi

echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null || true

echo "Linking storage..."
php artisan storage:link 2>/dev/null || true

echo "Optimizing Laravel..."
php artisan optimize 2>/dev/null || true

echo "Startup complete. Laravel is ready."
