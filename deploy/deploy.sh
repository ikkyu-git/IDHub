#!/usr/bin/env bash
set -euo pipefail

# Deployment helper script (operator-run)
# Usage: ssh server && cd /var/www/html && sudo -u www-data /bin/bash deploy/deploy.sh

echo "Starting deploy at $(date)"

echo "1) Pull latest code"
git pull --ff-only

echo "2) Install PHP dependencies"
composer install --no-dev --optimize-autoloader

echo "3) Run migrations"
php artisan migrate --force

echo "4) Cache configs"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "5) Restart queue workers"
if command -v supervisorctl >/dev/null 2>&1; then
  supervisorctl reread || true
  supervisorctl update || true
  supervisorctl restart idhub-worker:* || true
fi

echo "6) Restart PHP-FPM (if systemd)"
if command -v systemctl >/dev/null 2>&1; then
  sudo systemctl reload php-fpm || true
fi

echo "Deploy finished at $(date)"
