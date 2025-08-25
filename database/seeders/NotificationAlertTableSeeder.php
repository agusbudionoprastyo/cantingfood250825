<?php

namespace Database\Seeders;

use App\Enums\SwitchBox;
use Illuminate\Database\Seeder;
use App\Models\NotificationAlert;

class NotificationAlertTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public array $notificationAlerts = [
        'name'    => [
            'Admin And Branch Manager New Order Message',
        ],
        'message' => [
            'You have a new order.',
        ],
        'whatsapp_message' => [
            "🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: {order_id}\n• Order Type: {order_type}\n• Order Date: {order_date}\n• Payment Method: {payment_method}\n• Payment Status: {payment_status}\n• Total Amount: {total_amount}\n\n👤 Customer Info:\n• Name: {customer_name}\n• Phone: {customer_phone}\n• Email: {customer_email}\n\n🏪 Branch Info:\n• Branch: {branch_name}\n• Address: {branch_address}\n\n🍽️ Order Items:\n{order_items}\n\n📍 Delivery Address:\n{delivery_address}\n\n⏰ Timestamp: {timestamp}\n🔗 Order Link: {order_link}",
        ]
    ];

    public function run()
    {
        foreach ($this->notificationAlerts['name'] as $key => $notificationAlert) {
            NotificationAlert::create([
                'name'                      => $notificationAlert,
                'language'                  => str_replace(' ', '_', strtolower($notificationAlert)),
                'mail_message'              => $this->notificationAlerts['message'][$key],
                'sms_message'               => $this->notificationAlerts['message'][$key],
                'push_notification_message' => $this->notificationAlerts['message'][$key],
                'whatsapp_message'          => $this->notificationAlerts['whatsapp_message'][$key],
                'mail'                      => SwitchBox::OFF,
                'sms'                       => SwitchBox::OFF,
                'push_notification'         => SwitchBox::OFF,
                'whatsapp'                  => SwitchBox::ON,
            ]);
        }
    }
}
