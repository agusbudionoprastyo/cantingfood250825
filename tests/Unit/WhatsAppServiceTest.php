<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WhatsAppServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_format_order_message()
    {
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
                ]
            ],
            'subtotal' => '$20.00',
            'tax' => '$4.20',
            'total' => '$24.20'
        ];

        $message = $whatsappService->sendOrderNotification('62812345678', $orderData);
        
        $this->assertTrue($message);
    }

    public function test_send_message_with_valid_data()
    {
        $whatsappService = new WhatsAppService();
        
        $result = $whatsappService->sendMessage('62812345678', 'Test message');
        
        $this->assertIsBool($result);
    }
}
