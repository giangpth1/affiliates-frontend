#!/bin/bash

# =========================================
# Laravel Deployment Script for Azure Web App
# =========================================

set -e

echo "Starting Laravel deployment..."

# 1. Navigate to deployment folder
cd "$DEPLOYMENT_TARGET" || exit 1

# 2. Install Composer dependencies (production mode)
echo "Installing Composer dependencies..."
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --quiet
php -r "unlink('composer-setup.php');"
php composer.phar install --no-dev --optimize-autoloader --no-interaction

# 3. Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache

# 4. Clear and cache config (optional - only if .env is set)
if [ -f .env ]; then
    echo "Caching configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# 5. Run migrations (if needed)
# Uncomment if using database
# php artisan migrate --force --no-interaction

echo "Deployment completed successfully!"
