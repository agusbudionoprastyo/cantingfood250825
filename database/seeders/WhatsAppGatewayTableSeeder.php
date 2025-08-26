<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Smartisan\Settings\Facades\Settings;

class WhatsAppGatewayTableSeeder extends Seeder
{
    public function run()
    {
        Settings::group('whatsapp_gateway')->set([
            'whatsapp_enabled' => true,
            'whatsapp_api_url' => 'https://dev-iptv-wa.appdewa.com/message/send-text',
            'whatsapp_session' => 'mysession',
            'whatsapp_phone' => '62812345678',
            'whatsapp_company_name' => 'Canting Food',
            'whatsapp_message_template' => "*Hai {company_name}, ada pesanan baru nih!*\n_Klik tautan berikut untuk mengkonfirmasi pesanan_ cantingfood.my.id\n\n*Room/Table*\n{table_name}\n\n*Order Items*\n{items}\n\n*Subtotal*\n{subtotal}\n*Tax & Service*\n{tax}\n*Total*\n{total}\n\n_Thank's, happy working_"
        ]);
    }
}
