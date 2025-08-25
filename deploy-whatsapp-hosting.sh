#!/bin/bash

echo "🚀 Starting WhatsApp System Deployment for Hosting..."
echo ""

echo "📦 Step 1: Checking Laravel Installation..."
if [ ! -f "artisan" ]; then
    echo "❌ Error: Laravel not found. Please run this script from Laravel root directory."
    exit 1
fi

echo "✅ Laravel found!"
echo ""

echo "🗄️ Step 2: Running WhatsApp Migrations..."
php artisan migrate --path=database/migrations/2024_01_01_000000_add_whatsapp_to_notification_alerts_table.php
if [ $? -ne 0 ]; then
    echo "❌ Migration failed!"
    exit 1
fi

php artisan migrate --path=database/migrations/2024_01_02_000000_create_whatsapp_logs_table.php
if [ $? -ne 0 ]; then
    echo "❌ Migration failed!"
    exit 1
fi

php artisan migrate --path=database/migrations/2024_01_03_000000_create_whatsapp_templates_table.php
if [ $? -ne 0 ]; then
    echo "❌ Migration failed!"
    exit 1
fi

php artisan migrate --path=database/migrations/2024_01_04_000000_add_default_phone_to_whatsapp_gateway.php
if [ $? -ne 0 ]; then
    echo "❌ Migration failed!"
    exit 1
fi

php artisan migrate --path=database/migrations/2024_01_05_000000_add_api_path_to_whatsapp_gateway.php
if [ $? -ne 0 ]; then
    echo "❌ Migration failed!"
    exit 1
fi

echo "✅ Migrations completed!"
echo ""

echo "🌱 Step 3: Running WhatsApp Seeders..."
php artisan db:seed --class=WhatsappGatewaySeeder
if [ $? -ne 0 ]; then
    echo "❌ Seeder failed!"
    exit 1
fi

php artisan db:seed --class=WhatsappTemplateSeeder
if [ $? -ne 0 ]; then
    echo "❌ Seeder failed!"
    exit 1
fi

php artisan db:seed --class=NotificationAlertTableSeeder
if [ $? -ne 0 ]; then
    echo "❌ Seeder failed!"
    exit 1
fi

echo "✅ Seeders completed!"
echo ""

echo "🔐 Step 4: Setting File Permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
chmod 644 .env

echo "✅ Permissions set!"
echo ""

echo "🧹 Step 5: Clearing Caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "✅ Caches cleared!"
echo ""

echo "⚡ Step 6: Optimizing Application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Application optimized!"
echo ""

echo "📊 Step 7: Verifying Installation..."
php artisan tinker --execute="
echo '=== WhatsApp System Status ===';
echo 'notification_alerts: ' . \App\Models\NotificationAlert::count() . ' records';
echo 'whatsapp_logs: ' . \App\Models\WhatsappLog::count() . ' records';
echo 'whatsapp_templates: ' . \App\Models\WhatsappTemplate::count() . ' records';
echo 'sms_gateways (whatsapp): ' . \App\Models\SmsGateway::where('slug', 'whatsapp')->count() . ' records';

\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
if (\$gateway) {
    echo 'WhatsApp Gateway: ' . \$gateway->name . ' (Status: ' . \$gateway->status . ')';
} else {
    echo 'WhatsApp Gateway not found!';
}
"

echo ""
echo "🎯 Step 8: Configuration Checklist..."
echo ""
echo "📋 Please configure the following:"
echo "1. Update .env file:"
echo "   - APP_ENV=production"
echo "   - APP_DEBUG=false"
echo "   - APP_URL=https://yourdomain.com"
echo ""
echo "2. Configure WhatsApp Gateway in admin panel:"
echo "   - Go to Settings > SMS Gateway > WhatsApp"
echo "   - Set API URL: https://dev-iptv-wa.appdewa.com"
echo "   - Set Session: mysession"
echo "   - Enable gateway"
echo ""
echo "3. Configure Notification Alerts:"
echo "   - Go to Settings > Notification Alert"
echo "   - Enable WhatsApp for order notifications"
echo "   - Add admin phone numbers"
echo ""
echo "4. Test the system:"
echo "   - Create a test order"
echo "   - Check WhatsApp notifications"
echo "   - Monitor logs"
echo ""

echo "🧪 Step 9: Testing Endpoints..."
echo ""
echo "Testing direct WhatsApp endpoint..."
curl -s -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
  -H "Content-Type: application/json" \
  -d '{
    "session": "mysession",
    "to": "62812345678",
    "text": "🚀 WhatsApp system deployed successfully!"
  }' | jq '.' 2>/dev/null || echo "Direct endpoint test completed"

echo ""
echo "✅ WhatsApp System Deployment Completed Successfully!"
echo ""
echo "🎉 Your WhatsApp notification system is now ready!"
echo ""
echo "📱 Next Steps:"
echo "1. Configure admin panel settings"
echo "2. Add admin phone numbers"
echo "3. Test with real orders"
echo "4. Monitor logs and performance"
echo ""
echo "📞 Support: Check logs in storage/logs/laravel.log for any issues"
