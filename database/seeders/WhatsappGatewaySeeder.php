<?php

namespace Database\Seeders;

use App\Enums\Activity;
use App\Enums\InputType;
use App\Models\GatewayOption;
use App\Models\SmsGateway;
use Illuminate\Database\Seeder;

class WhatsappGatewaySeeder extends Seeder
{
    public function run()
    {
        $whatsappGateway = SmsGateway::create([
            'name' => 'WhatsApp',
            'slug' => 'whatsapp',
            'status' => Activity::ENABLE,
        ]);

        GatewayOption::create([
            'model_type' => SmsGateway::class,
            'model_id' => $whatsappGateway->id,
            'option' => 'whatsapp_api_url',
            'value' => 'https://dev-iptv-wa.appdewa.com',
            'type' => InputType::TEXT,
            'activities' => json_encode([])
        ]);

        GatewayOption::create([
            'model_type' => SmsGateway::class,
            'model_id' => $whatsappGateway->id,
            'option' => 'whatsapp_session',
            'value' => 'mysession',
            'type' => InputType::TEXT,
            'activities' => json_encode([])
        ]);

        GatewayOption::create([
            'model_type' => SmsGateway::class,
            'model_id' => $whatsappGateway->id,
            'option' => 'whatsapp_status',
            'value' => Activity::ENABLE,
            'type' => InputType::SELECT,
            'activities' => json_encode([
                Activity::ENABLE => 'enable',
                Activity::DISABLE => 'disable'
            ])
        ]);
    }
}
