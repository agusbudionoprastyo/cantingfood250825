<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\OrderService;
use App\Services\WhatsAppService;
use Smartisan\Settings\Facades\Settings;

echo "=== Testing Order Service and WhatsApp Notification ===\n\n";

try {
    echo "1. Testing WhatsApp Settings...\n";
    $enabled = Settings::group('whatsapp_gateway')->get('whatsapp_enabled', false);
    $apiUrl = Settings::group('whatsapp_gateway')->get('whatsapp_api_url', '');
    $session = Settings::group('whatsapp_gateway')->get('whatsapp_session', '');
    $phone = Settings::group('whatsapp_gateway')->get('whatsapp_phone', '');
    
    echo "   - Enabled: " . ($enabled ? 'true' : 'false') . "\n";
    echo "   - API URL: {$apiUrl}\n";
    echo "   - Session: {$session}\n";
    echo "   - Phone: {$phone}\n";
    
    echo "\n2. Testing WhatsApp Service...\n";
    $whatsappService = new WhatsAppService();
    echo "✅ WhatsApp Service created successfully\n";
    
    echo "\n3. Testing Order Service...\n";
    $orderService = new OrderService();
    echo "✅ Order Service created successfully\n";
    
    echo "\n4. Testing WhatsApp notification method...\n";
    
    $reflection = new ReflectionClass($orderService);
    $method = $reflection->getMethod('sendWhatsAppNotification');
    $method->setAccessible(true);
    
    $mockRequest = (object) [
        'dining_table_id' => 1,
        'subtotal' => 100000,
        'total' => 121000
    ];
    
    $mockItems = [
        (object) [
            'item_id' => 1,
            'quantity' => 2,
            'item_variations' => [],
            'item_extras' => [],
            'instruction' => 'Test instruction'
        ]
    ];
    
    $orderService->order = (object) ['id' => 999];
    
    $method->invoke($orderService, $mockRequest, $mockItems);
    echo "✅ WhatsApp notification method executed successfully\n";
    
    echo "\n✅ All tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
