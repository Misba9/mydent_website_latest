#!/bin/bash
# =====================================================================
# Hostinger Production Deployment Script for MyDent (https://mydent.in)
# =====================================================================

set -e

echo "🚀 Starting MyDent Deployment..."

# 1. Enable Maintenance Mode
php artisan down || true

# 2. Update Composer Dependencies (Production mode)
if [ -f "composer.phar" ]; then
    php composer.phar install --no-dev --optimize-autoloader --no-interaction
else
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# 3. Create Storage Symlink if missing
php artisan storage:link || true

# 4. Clear and Cache Configurations, Routes, Views, and Events
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 5. Bring Application Out of Maintenance Mode
php artisan up

echo "✅ MyDent Deployment Completed Successfully!"
