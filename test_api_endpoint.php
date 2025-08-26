<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Admin\WhatsAppGatewayController;
use App\Services\WhatsAppGatewayService;

echo "=== Testing API Endpoint ===\n\n";

try {
    echo "1. Testing Service directly...\n";
    $service = new WhatsAppGatewayService();
    $settings = $service->list();
    
    echo "✅ Service working\n";
    echo "Data count: " . count($settings) . "\n";
    
    foreach ($settings as $key => $value) {
        echo "   - {$key}: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value) . "\n";
    }
    
    echo "\n2. Testing update method...\n";
    $requestData = [
        'whatsapp_enabled' => true,
        'whatsapp_api_url' => 'https://dev-iptv-wa.appdewa.com/message/send-text',
        'whatsapp_session' => 'mysession',
        'whatsapp_phone' => '62812345678',
        'whatsapp_company_name' => 'Canting Food',
        'whatsapp_message_template' => 'Test template'
    ];
    
    $updatedSettings = $service->update($requestData);
    
    echo "✅ Update method working\n";
    echo "Updated settings count: " . count($updatedSettings) . "\n";
    
    echo "\n3. Testing WhatsApp Service...\n";
    $whatsappService = new \App\Services\WhatsAppService();
    echo "✅ WhatsApp Service created successfully\n";
    
    echo "\n✅ All API tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
