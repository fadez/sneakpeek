#!/bin/bash

set -e

# Put app into maintenance mode
php artisan down

# Pull the latest updates
git fetch origin main
git reset --hard origin/main

# Install composer dependencies
composer install --no-progress --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Build assets
npm ci
npm run build

# Clear all cached data
php artisan view:clear
php artisan cache:clear

# Cache configuration
php artisan config:cache

# Run Laravel migrations
php artisan migrate --force

# Note: Ensure user has NOPASSWD sudo rights for these
for version in 8.3 8.4 8.5; do
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
