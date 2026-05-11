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
chmod -R 755 storage bootstrap/cache 2>/dev/null || true

# 4. Clear Laravel caches (important for Vite manifest)
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 5. Run migrations (if needed)
# Uncomment if using database
# php artisan migrate --force --no-interaction

echo "Deployment completed successfully!"
