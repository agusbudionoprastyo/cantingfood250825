#!/bin/bash

echo "🚀 Starting Laravel Deployment..."

echo "📦 Installing Composer Dependencies..."
composer install --no-dev --optimize-autoloader

echo "🗄️ Setting File Permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/

echo "🔑 Generating Application Key..."
php artisan key:generate

echo "🗃️ Running Database Migrations..."
php artisan migrate --force

echo "🌱 Running Database Seeders..."
php artisan db:seed --force

echo "🧹 Clearing Application Caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "⚡ Optimizing Application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "📊 Optimizing Autoloader..."
composer dump-autoload --optimize

echo "✅ Deployment Completed Successfully!"
echo "🌐 Your Laravel application is now live!"
