#!/bin/bash

# Exit immediately if any command exits with a non-zero status
set -e

# Ensure passwordless sudo privileges are available
sudo -n true

# Reload PHP-FPM
for version in 8.5; do
    if systemctl is-active --quiet "php${version}-fpm"; then
        sudo systemctl reload "php${version}-fpm"
    fi
done
