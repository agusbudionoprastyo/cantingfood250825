#!/usr/bin/env bash
set -euo pipefail

echo "=== Rebuild autoload and clear Laravel caches ==="

if command -v composer >/dev/null 2>&1; then
  composer dump-autoload -o
else
  php composer.phar dump-autoload -o
fi

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "\n✅ Done. Now retry the frontend checkout."


