<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class WhatsAppGatewayService
{
    public function list(): array
    {
        try {
            $settings = Settings::group('whatsapp_gateway')->all();
            
            if (empty($settings)) {
                return [
                    'whatsapp_enabled' => false,
                    'whatsapp_api_url' => '',
                    'whatsapp_session' => '',
                    'whatsapp_phone' => '',
                    'whatsapp_company_name' => 'Canting Food',
                    'whatsapp_message_template' => ''
                ];
            }
            
            return $settings;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return [
                'whatsapp_enabled' => false,
                'whatsapp_api_url' => '',
                'whatsapp_session' => '',
                'whatsapp_phone' => '',
                'whatsapp_company_name' => 'Canting Food',
                'whatsapp_message_template' => ''
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            foreach ($data as $key => $value) {
                Settings::group('whatsapp_gateway')->set($key, $value);
            }
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function testConnection(): bool
    {
        try {
            $enabled = Settings::group('whatsapp_gateway')->get('whatsapp_enabled', false);
            if (!$enabled) {
                throw new Exception('WhatsApp notifications are disabled');
            }

            $phone = Settings::group('whatsapp_gateway')->get('whatsapp_phone');
            if (empty($phone)) {
                throw new Exception('WhatsApp phone number not configured');
            }

            $apiUrl = Settings::group('whatsapp_gateway')->get('whatsapp_api_url');
            if (empty($apiUrl)) {
                throw new Exception('WhatsApp API URL not configured');
            }

            $session = Settings::group('whatsapp_gateway')->get('whatsapp_session');
            if (empty($session)) {
                throw new Exception('WhatsApp session not configured');
            }

            $whatsappService = new WhatsAppService();
            $testMessage = "Test message from Canting Food - " . date('Y-m-d H:i:s');
            return $whatsappService->sendMessage($phone, $testMessage);
        } catch (Exception $exception) {
            Log::error('WhatsApp connection test failed', [
                'message' => $exception->getMessage()
            ]);
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
