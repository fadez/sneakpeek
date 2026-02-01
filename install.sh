#!/bin/bash

# Exit on error
set -e

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "PHP is not installed. Please install PHP 8.2 or higher."
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "Node.js is not installed. Please install Node.js first."
    exit 1
fi

# Create SQLite database if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    echo "Creating SQLite database..."
    touch database/database.sqlite
fi

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node.js dependencies
echo "Installing Node.js dependencies..."
npm ci

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Build assets
echo "Building assets..."
npm run build

echo "Installation completed successfully!"
