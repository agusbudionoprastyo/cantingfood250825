<?php

namespace App\Http\SmsGateways\Gateways;

use App\Enums\Activity;
use App\Models\SmsGateway;
use App\Services\SmsAbstract;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Whatsapp extends SmsAbstract
{
    public $apiUrl;
    public $session;

    public function __construct()
    {
        parent::__construct();

        $this->smsGateway = SmsGateway::with('gatewayOptions')->where(['slug' => 'whatsapp'])->first();
        if (!blank($this->smsGateway)) {
            $this->smsGatewayOption = $this->smsGateway->gatewayOptions->pluck('value', 'option');
            $this->apiUrl = $this->smsGatewayOption['whatsapp_api_url'] ?? 'https://dev-iptv-wa.appdewa.com';
            $this->session = $this->smsGatewayOption['whatsapp_session'] ?? 'mysession';
        }
    }

    public function status(): bool
    {
        $whatsappGateway = SmsGateway::where(['slug' => 'whatsapp', 'status' => Activity::ENABLE])->first();
        if ($whatsappGateway) {
            return true;
        }
        return false;
    }

    public function send($code, $phone, $message)
    {
        try {
            $phoneNumber = $code . $phone;
            
            $response = Http::post($this->apiUrl . '/message/send-text', [
                'session' => $this->session,
                'to' => $phoneNumber,
                'text' => $message
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully to ' . $phoneNumber);
            } else {
                Log::error('WhatsApp message failed to send: ' . $response->body());
            }
        } catch (Exception $exception) {
            Log::error('WhatsApp gateway error: ' . $exception->getMessage());
        }
    }
}
