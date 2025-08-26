<?php

require_once 'vendor/autoload.php';

use App\Services\WhatsAppService;

// Test the WhatsApp service
$whatsappService = new WhatsAppService();

$orderData = [
    'table_name' => 'Table 1',
    'items' => [
        [
            'name' => 'Burger',
            'quantity' => 2,
            'variations' => 'Spicy',
            'extras' => 'Cheese',
            'instruction' => 'No onions'
        ],
        [
            'name' => 'French Fries',
            'quantity' => 1,
            'variations' => '',
            'extras' => '',
            'instruction' => ''
        ]
    ],
    'subtotal' => '$20.00',
    'tax' => '$4.20',
    'total' => '$24.20'
];

echo "Testing WhatsApp Service...\n";
echo "Order Data: " . json_encode($orderData, JSON_PRETTY_PRINT) . "\n\n";

$result = $whatsappService->sendOrderNotification('62812345678', $orderData);

if ($result) {
    echo "✅ WhatsApp notification sent successfully!\n";
} else {
    echo "❌ WhatsApp notification failed to send.\n";
}

echo "\nTest completed.\n";
