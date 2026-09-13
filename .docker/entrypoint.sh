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

# Fail fast with a clear message if the app DB user cannot authenticate.
# (mysqladmin ping only checks liveness; a real query proves credentials.)
# MySQL creates users only at first init, so credentials added or changed
# in .env afterwards require wiping the mysql data dir and re-initializing.
if [ -n "$DB_HOST" ]; then
  DB_CHECK_DB=""
  [ -n "$DB_DATABASE" ] && DB_CHECK_DB="$DB_DATABASE"
  if ! mysql -h"$DB_HOST" -P"${DB_PORT:-3306}" -u"$DB_USERNAME" -p"$DB_PASSWORD" \
          -e "SELECT 1;" $DB_CHECK_DB >/dev/null 2>/tmp/dbcheck.err; then
    echo "ERROR: cannot authenticate to MySQL as user '$DB_USERNAME'."
    cat /tmp/dbcheck.err || true
    echo "Likely cause: DB credentials in .env were added or changed after the"
    echo "database was first initialized. Fix: 'docker compose down', empty the"
    echo "mysql data dir, confirm a single DB_PASSWORD in .env, then up again."
    exit 1
  fi
  echo "Database credentials verified."
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
