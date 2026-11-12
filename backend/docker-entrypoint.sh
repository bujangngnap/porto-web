#!/usr/bin/env sh
set -eu

cd /app

mkdir -p /app/storage/framework/cache/data \
         /app/storage/framework/sessions \
         /app/storage/framework/views \
         /app/storage/logs

if [ -d /data ]; then
  mkdir -p /data/public_storage
  touch /data/database.sqlite
  rm -rf /app/storage/app/public
  ln -s /data/public_storage /app/storage/app/public
  export DB_DATABASE="/data/database.sqlite"
else
  mkdir -p /app/storage/app/public
  touch /app/database/database.sqlite
  export DB_DATABASE="/app/database/database.sqlite"
fi

rm -rf /app/public/storage
ln -s /app/storage/app/public /app/public/storage

php artisan migrate --force

USER_COUNT=$(php -r 'require "vendor/autoload.php"; $a = require "bootstrap/app.php"; $a->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo App\Models\User::count();')
if [ "${USER_COUNT:-0}" = "0" ]; then
  php artisan db:seed --class=PortfolioSeeder --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

PORT_TO_USE="${PORT:-8080}"
export PHP_CLI_SERVER_WORKERS=4
cd /app/public
exec php -S "0.0.0.0:${PORT_TO_USE}" /app/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
