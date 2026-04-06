#!/bin/bash

# WARNING: Ensure user has NOPASSWD sudo rights

set -e

# Pull the latest updates
git fetch origin main
git reset --hard origin/main

# Install composer dependencies
composer install --no-progress --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Build assets
npm ci
npm run build

# Put app into maintenance mode
php artisan down

# Run Laravel migrations
php artisan migrate --force

# Cache configuration, routes, events, and views
php artisan optimize

# Reload PHP-FPM versions if running
for version in 8.5; do
    if systemctl is-active --quiet "php${version}-fpm"; then
        sudo systemctl reload "php${version}-fpm"
    fi
done

# Reload nginx if running
if systemctl is-active --quiet nginx; then
    sudo systemctl reload nginx
fi

# Bring app out of maintenance mode
php artisan up
