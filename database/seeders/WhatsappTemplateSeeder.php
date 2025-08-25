<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WhatsappTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Order Notification Template',
                'slug' => 'order_notification',
                'template_content' => "🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: {order_id}\n• Order Type: {order_type}\n• Order Date: {order_date}\n• Payment Method: {payment_method}\n• Payment Status: {payment_status}\n• Total Amount: {total_amount}\n\n👤 Customer Info:\n• Name: {customer_name}\n• Phone: {customer_phone}\n• Email: {customer_email}\n\n🏪 Branch Info:\n• Branch: {branch_name}\n• Address: {branch_address}\n\n🍽️ Order Items:\n{order_items}\n\n📍 Delivery Address:\n{delivery_address}\n\n⏰ Timestamp: {timestamp}\n🔗 Order Link: {order_link}",
                'variables' => json_encode([
                    'order_id', 'order_type', 'order_date', 'payment_method', 'payment_status', 
                    'total_amount', 'customer_name', 'customer_phone', 'customer_email', 
                    'branch_name', 'branch_address', 'order_items', 'delivery_address', 
                    'timestamp', 'order_link'
                ]),
                'type' => 'order_notification',
                'is_active' => true,
                'description' => 'Template untuk notifikasi order baru'
            ],
            [
                'name' => 'Order Status Update Template',
                'slug' => 'order_status_update',
                'template_content' => "📦 ORDER STATUS UPDATE 📦\n\nOrder ID: {order_id}\nStatus: {order_status}\nUpdated: {update_time}\n\nCustomer: {customer_name}\nPhone: {customer_phone}\n\n{status_message}\n\n🔗 Track Order: {order_link}",
                'variables' => json_encode([
                    'order_id', 'order_status', 'update_time', 'customer_name', 
                    'customer_phone', 'status_message', 'order_link'
                ]),
                'type' => 'order_notification',
                'is_active' => true,
                'description' => 'Template untuk update status order'
            ],
            [
                'name' => 'Payment Confirmation Template',
                'slug' => 'payment_confirmation',
                'template_content' => "💳 PAYMENT CONFIRMED 💳\n\nOrder ID: {order_id}\nAmount: {payment_amount}\nMethod: {payment_method}\nDate: {payment_date}\n\nCustomer: {customer_name}\nPhone: {customer_phone}\n\n✅ Payment received successfully!\n\n🔗 View Order: {order_link}",
                'variables' => json_encode([
                    'order_id', 'payment_amount', 'payment_method', 'payment_date',
                    'customer_name', 'customer_phone', 'order_link'
                ]),
                'type' => 'order_notification',
                'is_active' => true,
                'description' => 'Template untuk konfirmasi pembayaran'
            ],
            [
                'name' => 'Marketing Template',
                'slug' => 'marketing_promo',
                'template_content' => "🎉 SPECIAL PROMOTION 🎉\n\n{promo_title}\n\n{promo_description}\n\n💰 Discount: {discount_amount}\n⏰ Valid until: {valid_until}\n\nUse code: {promo_code}\n\n🍽️ Order now: {website_url}",
                'variables' => json_encode([
                    'promo_title', 'promo_description', 'discount_amount', 
                    'valid_until', 'promo_code', 'website_url'
                ]),
                'type' => 'marketing',
                'is_active' => true,
                'description' => 'Template untuk promosi marketing'
            ]
        ];

        foreach ($templates as $template) {
            DB::table('whatsapp_templates')->insert($template);
        }
    }
}
