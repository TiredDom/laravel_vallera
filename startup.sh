#!/bin/bash

cd /home/site/wwwroot

if [ ! -f "artisan" ]; then
    exit 1
fi

if [ -f "/etc/nginx/sites-available/default" ] && [ -f "default" ]; then
    cp default /etc/nginx/sites-available/default 2>/dev/null
    service nginx reload 2>/dev/null || true
fi

cat > .env << EOF
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG}"
APP_URL="${APP_URL}"

DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"
MYSQL_ATTR_SSL_CA=
MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=false

SESSION_DRIVER="${SESSION_DRIVER}"
SESSION_LIFETIME="${SESSION_LIFETIME}"
CACHE_STORE="${CACHE_STORE}"
QUEUE_CONNECTION="${QUEUE_CONNECTION}"

SEED_ADMIN_NAME="${SEED_ADMIN_NAME}"
SEED_ADMIN_EMAIL="${SEED_ADMIN_EMAIL}"
SEED_ADMIN_PASSWORD="${SEED_ADMIN_PASSWORD}"

FILESYSTEM_DISK="${FILESYSTEM_DISK}"
LOG_CHANNEL="${LOG_CHANNEL}"
LOG_LEVEL="${LOG_LEVEL}"

VIEW_COMPILED_PATH=/home/site/wwwroot/storage/framework/views
EOF

chmod 644 .env
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

php artisan storage:link 2>/dev/null || true

php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true

