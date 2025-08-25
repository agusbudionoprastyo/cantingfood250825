<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WhatsappTestDataSeeder extends Seeder
{
    public function run()
    {
        $this->createTestLogs();
        $this->createTestNotificationAlerts();
    }

    private function createTestLogs()
    {
        $testLogs = [
            [
                'order_id' => 1,
                'phone_number' => '08123456789',
                'country_code' => '62',
                'message' => '🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: ORD001\n• Order Type: dine-in\n• Order Date: 25/08/2024 14:30\n• Payment Method: cash\n• Payment Status: pending\n• Total Amount: Rp 150.000',
                'status' => 'success',
                'response' => '{"status": true, "message": "Message sent successfully"}',
                'error_message' => null,
                'gateway_used' => 'whatsapp',
                'metadata' => json_encode(['template_used' => 'order_notification', 'recipient_type' => 'admin']),
                'sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'order_id' => 2,
                'phone_number' => '08187654321',
                'country_code' => '62',
                'message' => '🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: ORD002\n• Order Type: delivery\n• Order Date: 25/08/2024 15:45\n• Payment Method: online\n• Payment Status: paid\n• Total Amount: Rp 200.000',
                'status' => 'success',
                'response' => '{"status": true, "message": "Message sent successfully"}',
                'error_message' => null,
                'gateway_used' => 'whatsapp',
                'metadata' => json_encode(['template_used' => 'order_notification', 'recipient_type' => 'branch_manager']),
                'sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'order_id' => 3,
                'phone_number' => '08111222333',
                'country_code' => '62',
                'message' => '🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: ORD003\n• Order Type: takeaway\n• Order Date: 25/08/2024 16:20\n• Payment Method: cash\n• Payment Status: pending\n• Total Amount: Rp 75.000',
                'status' => 'failed',
                'response' => null,
                'error_message' => 'Invalid phone number format',
                'gateway_used' => 'whatsapp',
                'metadata' => json_encode(['template_used' => 'order_notification', 'recipient_type' => 'admin']),
                'sent_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($testLogs as $log) {
            DB::table('whatsapp_logs')->insert($log);
        }
    }

    private function createTestNotificationAlerts()
    {
        $additionalAlerts = [
            [
                'name' => 'Order Status Update Notification',
                'language' => 'order_status_update_notification',
                'mail_message' => 'Your order status has been updated.',
                'sms_message' => 'Your order status has been updated.',
                'push_notification_message' => 'Your order status has been updated.',
                'whatsapp_message' => "📦 ORDER STATUS UPDATE 📦\n\nOrder ID: {order_id}\nStatus: {order_status}\nUpdated: {update_time}\n\nCustomer: {customer_name}\nPhone: {customer_phone}\n\n{status_message}\n\n🔗 Track Order: {order_link}",
                'mail' => 0,
                'sms' => 0,
                'push_notification' => 0,
                'whatsapp' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Payment Confirmation Notification',
                'language' => 'payment_confirmation_notification',
                'mail_message' => 'Your payment has been confirmed.',
                'sms_message' => 'Your payment has been confirmed.',
                'push_notification_message' => 'Your payment has been confirmed.',
                'whatsapp_message' => "💳 PAYMENT CONFIRMED 💳\n\nOrder ID: {order_id}\nAmount: {payment_amount}\nMethod: {payment_method}\nDate: {payment_date}\n\nCustomer: {customer_name}\nPhone: {customer_phone}\n\n✅ Payment received successfully!\n\n🔗 View Order: {order_link}",
                'mail' => 0,
                'sms' => 0,
                'push_notification' => 0,
                'whatsapp' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($additionalAlerts as $alert) {
            DB::table('notification_alerts')->insert($alert);
        }
    }
}
