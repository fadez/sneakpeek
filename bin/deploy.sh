#!/bin/bash

# Stop on any error, unset variable, or failed pipe, so a failed deploy leaves the site down
set -euo pipefail

# main() makes bash parse the full script first, so overwriting it mid-run is safe
main() {
    # Go to the project root
    cd "$(dirname "$0")/.."

    # Acquire a temporary lock for the deployment, the kernel releases it when this script exits
    exec 9>/tmp/sneakpeek-deploy.lock

    # Exit immediately if another deploy already holds the lock
    flock -n 9 || { echo "Deploy already in progress, skipping..." >&2; exit 1; }

    # Commit SHA that passed CI, supplied by the CD pipeline, falls back to "origin/main" if empty
    TARGET="${1:-origin/main}"

    # Put the application into maintenance mode
    php artisan down --refresh=15

    # Pull the latest updates
    git fetch origin main
    git reset --hard "$TARGET"

    # Install composer dependencies
    composer install --no-progress --no-interaction --no-dev --prefer-dist --optimize-autoloader

    # Run Laravel migrations
    php artisan migrate --force

    # Cache configuration, events, routes, and views
    php artisan optimize

    # Build assets
    npm ci
    npm run build

    # Reload PHP-FPM and NGINX
    ./bin/reload-php.sh
    ./bin/reload-nginx.sh

    # Bring the application out of maintenance mode
    php artisan up
}

main "$@"
