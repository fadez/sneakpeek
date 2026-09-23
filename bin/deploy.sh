#!/bin/bash

# Exit immediately if any command exits with a non-zero status
set -e

# Put the application into maintenance mode
php artisan down --refresh=15

# Pull the latest updates
git fetch origin main
git reset --hard origin/main

# Install composer dependencies
composer install --no-progress --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Run Laravel migrations
php artisan migrate --force

# Cache configuration, events, routes, and views
php artisan optimize:clear
php artisan optimize

# Build assets
npm ci
npm run build

# Reload PHP-FPM and NGINX
./reload-php.sh
./reload-nginx.sh

# Bring the application out of maintenance mode
php artisan up
