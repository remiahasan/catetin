#!/bin/sh
set -e

# Wait for MySQL (container networking: DB_HOST=db)
if [ -n "$DB_HOST" ]; then
  echo "Waiting for database $DB_HOST:${DB_PORT:-3306}..."
  for i in $(seq 1 30); do
    if mysqladmin ping -h"$DB_HOST" -P"${DB_PORT:-3306}" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent 2>/dev/null; then
      echo "Database reachable."
      break
    fi
    sleep 2
    if [ "$i" = "30" ]; then
      echo "WARNING: database not reachable, continuing anyway."
    fi
  done
fi

# Bind mounts under /opt/app-data start empty: ensure Laravel's
# writable structure exists before artisan commands run.
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions \
         storage/framework/views storage/logs bootstrap/cache

# Laravel bootstrapping (idempotent)
php artisan storage:link --force || true
php artisan migrate --force
if [ "${APP_ENV:-production}" = "production" ]; then
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
else
  # Development: never bake caches, or config/route/view edits won't hot-reload.
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
fi
chown -R www-data:www-data storage bootstrap/cache || true

exec "$@"
