#!/bin/bash

# Put app into maintenance mode
php artisan down

# Pull the latest updates
git pull --ff-only

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

# Reload php8.2-fpm if running
if systemctl is-active --quiet php8.2-fpm; then
    sudo systemctl reload php8.2-fpm
fi

# Reload php8.3-fpm if running
if systemctl is-active --quiet php8.3-fpm; then
    sudo systemctl reload php8.3-fpm
fi

# Reload php8.4-fpm if running
if systemctl is-active --quiet php8.4-fpm; then
    sudo systemctl reload php8.4-fpm
fi

# Reload php8.5-fpm if running
if systemctl is-active --quiet php8.5-fpm; then
    sudo systemctl reload php8.5-fpm
fi

# Reload nginx if running
if systemctl is-active --quiet nginx; then
    sudo systemctl reload nginx
fi

# Bring app out of maintenance mode
php artisan up
