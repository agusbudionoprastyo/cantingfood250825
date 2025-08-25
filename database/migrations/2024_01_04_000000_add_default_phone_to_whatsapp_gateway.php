<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $whatsappGateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
        
        if ($whatsappGateway) {
            \App\Models\GatewayOption::updateOrCreate(
                [
                    'model_type' => \App\Models\SmsGateway::class,
                    'model_id' => $whatsappGateway->id,
                    'option' => 'whatsapp_default_phone'
                ],
                [
                    'value' => '62812345678',
                    'type' => \App\Enums\InputType::TEXT,
                    'activities' => json_encode([])
                ]
            );

            \App\Models\GatewayOption::updateOrCreate(
                [
                    'model_type' => \App\Models\SmsGateway::class,
                    'model_id' => $whatsappGateway->id,
                    'option' => 'whatsapp_country_code'
                ],
                [
                    'value' => '62',
                    'type' => \App\Enums\InputType::TEXT,
                    'activities' => json_encode([])
                ]
            );
        }
    }

    public function down()
    {
        $whatsappGateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
        
        if ($whatsappGateway) {
            \App\Models\GatewayOption::where([
                'model_type' => \App\Models\SmsGateway::class,
                'model_id' => $whatsappGateway->id,
                'option' => 'whatsapp_default_phone'
            ])->delete();

            \App\Models\GatewayOption::where([
                'model_type' => \App\Models\SmsGateway::class,
                'model_id' => $whatsappGateway->id,
                'option' => 'whatsapp_country_code'
            ])->delete();
        }
    }
};
