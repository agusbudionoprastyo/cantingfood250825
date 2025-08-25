#!/bin/bash

echo "🚀 Starting WhatsApp Migration and Seeder..."

echo "📦 Running WhatsApp Migrations..."
php artisan migrate --path=database/migrations/2024_01_01_000000_add_whatsapp_to_notification_alerts_table.php
php artisan migrate --path=database/migrations/2024_01_02_000000_create_whatsapp_logs_table.php
php artisan migrate --path=database/migrations/2024_01_03_000000_create_whatsapp_templates_table.php

echo "🌱 Running WhatsApp Seeders..."
php artisan db:seed --class=WhatsappGatewaySeeder
php artisan db:seed --class=WhatsappTemplateSeeder
php artisan db:seed --class=NotificationAlertTableSeeder

echo "🧪 Running Test Data Seeder (Optional)..."
read -p "Do you want to add test data? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed --class=WhatsappTestDataSeeder
    echo "✅ Test data added successfully!"
fi

echo "🧹 Clearing Caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "⚡ Optimizing Application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "📊 Checking Database Tables..."
php artisan tinker --execute="
echo 'WhatsApp Tables Status:';
echo 'notification_alerts: ' . \App\Models\NotificationAlert::count() . ' records';
echo 'whatsapp_logs: ' . \App\Models\WhatsappLog::count() . ' records';
echo 'whatsapp_templates: ' . \App\Models\WhatsappTemplate::count() . ' records';
echo 'sms_gateways: ' . \App\Models\SmsGateway::where('slug', 'whatsapp')->count() . ' records';
"

echo "✅ WhatsApp Migration and Seeder Completed Successfully!"
echo "📱 WhatsApp notification system is now ready!"
echo ""
echo "🔧 Next Steps:"
echo "1. Configure WhatsApp Gateway in admin panel"
echo "2. Set up notification alerts"
echo "3. Test with a new order"
echo "4. Monitor logs in storage/logs/laravel.log"
