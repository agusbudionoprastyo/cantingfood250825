<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class WhatsAppService
{
    private string $apiUrl;
    private string $session;
    private bool $enabled;
    private string $phone;
    private string $companyName;
    private string $messageTemplate;

    public function __construct()
    {
        $this->enabled = Settings::group('whatsapp_gateway')->get('whatsapp_enabled', false);
        $this->apiUrl = Settings::group('whatsapp_gateway')->get('whatsapp_api_url', '');
        $this->session = Settings::group('whatsapp_gateway')->get('whatsapp_session', '');
        $this->phone = Settings::group('whatsapp_gateway')->get('whatsapp_phone', '');
        $this->companyName = Settings::group('whatsapp_gateway')->get('whatsapp_company_name', 'Canting Food');
        $this->messageTemplate = Settings::group('whatsapp_gateway')->get('whatsapp_message_template', '');
    }

    public function sendMessage(string $to, string $text): bool
    {
        if (!$this->enabled) {
            Log::info('WhatsApp notifications are disabled');
            return false;
        }

        if (empty($this->apiUrl) || empty($this->session)) {
            Log::error('WhatsApp API URL or session not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'session' => $this->session,
                'to' => $to,
                'text' => $text
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('WhatsApp message failed to send', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return false;
            }
        } catch (Exception $exception) {
            Log::error('WhatsApp service error', [
                'message' => $exception->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }

    public function sendOrderNotification(string $to, array $orderData): bool
    {
        $message = $this->formatOrderMessage($orderData);
        return $this->sendMessage($to, $message);
    }

    private function formatOrderMessage(array $orderData): string
    {
        if (empty($this->messageTemplate)) {
            return $this->getDefaultMessage($orderData);
        }

        $items = '';
        if (isset($orderData['items']) && is_array($orderData['items'])) {
            foreach ($orderData['items'] as $item) {
                $itemText = $item['name'] . ' ' . $item['quantity'];
                
                if (!empty($item['variations'])) {
                    $itemText .= " *_Varian_* " . $item['variations'];
                }
                
                if (!empty($item['extras'])) {
                    $itemText .= " *_Extra_* " . $item['extras'];
                }
                
                if (!empty($item['instruction'])) {
                    $itemText .= " *_Note_* " . $item['instruction'];
                }
                
                $items .= $itemText . "\n";
            }
        }

        $replacements = [
            '{company_name}' => $this->companyName,
            '{table_name}' => $orderData['table_name'] ?? 'Unknown Table',
            '{items}' => $items,
            '{subtotal}' => $orderData['subtotal'] ?? '',
            '{tax}' => $orderData['tax'] ?? '',
            '{total}' => $orderData['total'] ?? ''
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $this->messageTemplate);
    }

    private function getDefaultMessage(array $orderData): string
    {
        $message = "*Hai {$this->companyName}, ada pesanan baru nih!*\n";
        $message .= "_Klik tautan berikut untuk mengkonfirmasi pesanan_ cantingfood.my.id\n\n";
        
        if (isset($orderData['table_name'])) {
            $message .= "*Room/Table*\n{$orderData['table_name']}\n\n";
        }
        
        if (isset($orderData['items']) && is_array($orderData['items'])) {
            $message .= "*Order Items*\n";
            foreach ($orderData['items'] as $item) {
                $itemText = $item['name'] . ' ' . $item['quantity'];
                
                if (!empty($item['variations'])) {
                    $itemText .= " *_Varian_* " . $item['variations'];
                }
                
                if (!empty($item['extras'])) {
                    $itemText .= " *_Extra_* " . $item['extras'];
                }
                
                if (!empty($item['instruction'])) {
                    $itemText .= " *_Note_* " . $item['instruction'];
                }
                
                $message .= $itemText . "\n";
            }
            $message .= "\n";
        }
        
        if (isset($orderData['subtotal'])) {
            $message .= "*Subtotal*\n{$orderData['subtotal']}\n";
        }
        
        if (isset($orderData['tax'])) {
            $message .= "*Tax & Service*\n{$orderData['tax']}\n";
        }
        
        if (isset($orderData['total'])) {
            $message .= "*Total*\n{$orderData['total']}\n";
        }
        
        $message .= "\n_Thank's, happy working_";
        
        return $message;
    }
}
