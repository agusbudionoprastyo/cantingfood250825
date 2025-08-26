<?php

require_once 'vendor/autoload.php';

use App\Services\WhatsAppService;
use App\Services\WhatsAppGatewayService;
use Smartisan\Settings\Facades\Settings;

echo "=== WhatsApp Gateway Test ===\n\n";

// Test 1: Check if settings are loaded
echo "1. Testing Settings Loading...\n";
try {
    $settings = Settings::group('whatsapp_gateway')->all();
    echo "✅ Settings loaded successfully\n";
    echo "Settings: " . json_encode($settings, JSON_PRETTY_PRINT) . "\n\n";
} catch (Exception $e) {
    echo "❌ Failed to load settings: " . $e->getMessage() . "\n\n";
}

// Test 2: Test WhatsApp Service
echo "2. Testing WhatsApp Service...\n";
try {
    $whatsappService = new WhatsAppService();
    echo "✅ WhatsApp service created successfully\n";
    
    $orderData = [
        'table_name' => 'Table 1',
        'items' => [
            [
                'name' => 'Burger',
                'quantity' => 2,
                'variations' => 'Spicy',
                'extras' => 'Cheese',
                'instruction' => 'No onions'
            ]
        ],
        'subtotal' => '$20.00',
        'tax' => '$4.20',
        'total' => '$24.20'
    ];
    
    echo "Order data prepared\n";
    echo "Order data: " . json_encode($orderData, JSON_PRETTY_PRINT) . "\n\n";
    
} catch (Exception $e) {
    echo "❌ WhatsApp service test failed: " . $e->getMessage() . "\n\n";
}

// Test 3: Test Gateway Service
echo "3. Testing Gateway Service...\n";
try {
    $gatewayService = new WhatsAppGatewayService();
    $settings = $gatewayService->list();
    echo "✅ Gateway service working\n";
    echo "Gateway settings: " . json_encode($settings, JSON_PRETTY_PRINT) . "\n\n";
} catch (Exception $e) {
    echo "❌ Gateway service test failed: " . $e->getMessage() . "\n\n";
}

echo "=== Test Completed ===\n";
