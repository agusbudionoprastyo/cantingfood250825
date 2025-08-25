<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $whatsappGateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
        if ($whatsappGateway) {
            \App\Models\GatewayOption::updateOrCreate(
                [
                    'model_type' => \App\Models\SmsGateway::class,
                    'model_id' => $whatsappGateway->id,
                    'option' => 'whatsapp_api_path',
                ],
                [
                    'value' => '/message/send-text',
                    'type' => \App\Enums\InputType::TEXT,
                    'activities' => json_encode([]),
                ]
            );
        }
    }

    public function down(): void
    {
        $whatsappGateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
        if ($whatsappGateway) {
            \App\Models\GatewayOption::where([
                'model_type' => \App\Models\SmsGateway::class,
                'model_id' => $whatsappGateway->id,
                'option' => 'whatsapp_api_path',
            ])->delete();
        }
    }
};
