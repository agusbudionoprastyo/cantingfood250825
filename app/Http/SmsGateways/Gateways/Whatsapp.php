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
            
            $payload = [
                'session' => $this->session,
                'to' => $phoneNumber,
                'text' => $message
            ];

            Log::info('Sending WhatsApp message', [
                'to' => $phoneNumber,
                'session' => $this->session,
                'message_length' => strlen($message)
            ]);

            $response = Http::timeout(30)->post($this->apiUrl . '/message/send-text', $payload);

            $responseData = $response->json();
            $responseBody = $response->body();

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $phoneNumber,
                    'response' => $responseData,
                    'status_code' => $response->status()
                ]);
                
                return [
                    'status' => true,
                    'message' => 'WhatsApp message sent successfully',
                    'response' => $responseData
                ];
            } else {
                Log::error('WhatsApp message failed to send', [
                    'to' => $phoneNumber,
                    'response' => $responseData,
                    'status_code' => $response->status(),
                    'body' => $responseBody
                ]);
                
                return [
                    'status' => false,
                    'message' => 'WhatsApp message failed to send',
                    'response' => $responseData,
                    'error' => $responseBody
                ];
            }
        } catch (Exception $exception) {
            Log::error('WhatsApp gateway error', [
                'to' => $phoneNumber ?? 'unknown',
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);
            
            return [
                'status' => false,
                'message' => 'WhatsApp gateway error: ' . $exception->getMessage(),
                'error' => $exception->getMessage()
            ];
        }
    }
}
