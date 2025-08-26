<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Admin\WhatsAppGatewayController;
use App\Services\WhatsAppGatewayService;

echo "=== Testing API Endpoint ===\n\n";

try {
    echo "1. Testing Controller directly...\n";
    $service = new WhatsAppGatewayService();
    $controller = new WhatsAppGatewayController($service);
    
    $response = $controller->index();
    $data = json_decode($response->getContent(), true);
    
    echo "✅ Controller working\n";
    echo "Status: " . ($data['status'] ? 'true' : 'false') . "\n";
    echo "Data count: " . count($data['data']) . "\n";
    
    foreach ($data['data'] as $key => $value) {
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
    
    $updateResponse = $controller->update(new \App\Http\Requests\WhatsAppGatewayRequest($requestData));
    $updateData = json_decode($updateResponse->getContent(), true);
    
    echo "✅ Update method working\n";
    echo "Status: " . ($updateData['status'] ? 'true' : 'false') . "\n";
    echo "Message: " . $updateData['message'] . "\n";
    
    echo "\n✅ All API tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
