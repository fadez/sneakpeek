#!/bin/bash

# Exit immediately if any command exits with a non-zero status
set -e

# Ensure passwordless sudo privileges are available
sudo -n true

# Reload NGINX
if systemctl is-active --quiet nginx; then
    sudo systemctl reload nginx
fi
