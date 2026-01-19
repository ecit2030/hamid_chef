#!/bin/bash

# Script to fix 419 CSRF Token issues on server
# Run this script on your server after uploading the new files

echo "🔧 Starting 419 Fix Script..."
echo ""

# 1. Create sessions table if not exists
echo "📊 Creating sessions table..."
php artisan session:table
php artisan migrate --force

# 2. Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 3. Rebuild cache
echo "🔨 Rebuilding cache..."
php artisan config:cache
php artisan route:cache

# 4. Fix storage permissions
echo "🔐 Fixing storage permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 5. Fix ownership (adjust www-data if needed)
echo "👤 Fixing ownership..."
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# 6. Fix public/build permissions
echo "📦 Fixing build permissions..."
chmod -R 755 public/build

echo ""
echo "✅ Fix script completed!"
echo ""
echo "Next steps:"
echo "1. Make sure .env has correct SESSION settings"
echo "2. Test login in incognito/private window"
echo "3. Check browser console for any errors"
