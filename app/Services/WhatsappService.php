<?php

namespace App\Services;

use App\Enums\Role;
use App\Enums\SwitchBox;
use App\Models\FrontendOrder;
use App\Models\NotificationAlert;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public int $orderId;
    public object $order;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
        $this->order = FrontendOrder::find($orderId);
    }

    public function send()
    {
        if (!blank($this->order)) {
            $whatsappAllAdmins = User::role(Role::ADMIN)->where(['branch_id' => 0])->whereNotNull('phone')->get();
            $whatsappBranchAdmins = User::role(Role::ADMIN)->where(['branch_id' => $this->order->branch_id])->whereNotNull('phone')->get();
            $whatsappBranchManagers = User::role(Role::BRANCH_MANAGER)->where(['branch_id' => $this->order->branch_id])->whereNotNull('phone')->get();

            $i = 0;
            $whatsappArrays = [];
            if (!blank($whatsappAllAdmins)) {
                foreach ($whatsappAllAdmins as $whatsappAllAdmin) {
                    $whatsappArrays[$i] = [
                        'code' => $whatsappAllAdmin->country_code,
                        'phone' => $whatsappAllAdmin->phone,
                    ];
                    $i++;
                }
            }

            if (!blank($whatsappBranchAdmins)) {
                foreach ($whatsappBranchAdmins as $whatsappBranchAdmin) {
                    $whatsappArrays[$i] = [
                        'code' => $whatsappBranchAdmin->country_code,
                        'phone' => $whatsappBranchAdmin->phone,
                    ];
                    $i++;
                }
            }

            if (!blank($whatsappBranchManagers)) {
                foreach ($whatsappBranchManagers as $whatsappBranchManager) {
                    $whatsappArrays[$i] = [
                        'code' => $whatsappBranchManager->country_code,
                        'phone' => $whatsappBranchManager->phone,
                    ];
                    $i++;
                }
            }

            if (count($whatsappArrays) > 0) {
                try {
                    $notificationAlert = NotificationAlert::where(['language' => 'admin_and_branch_manager_new_order_message'])->first();
                    if ($notificationAlert && $notificationAlert->whatsapp == SwitchBox::ON) {
                        $message = $this->buildOrderMessage($notificationAlert->whatsapp_message);
                        foreach ($whatsappArrays as $whatsappArray) {
                            $this->sendWhatsapp($whatsappArray['code'], $whatsappArray['phone'], $message);
                        }
                    }
                } catch (Exception $e) {
                    Log::info($e->getMessage());
                }
            }
        }
    }

    private function sendWhatsapp($code, $phone, $message): void
    {
        try {
            $smsManagerService = new SmsManagerService();
            $smsService = new SmsService();
            
            if ($smsService->gateway() && $smsManagerService->gateway($smsService->gateway())->status()) {
                $result = $smsManagerService->gateway($smsService->gateway())->send($code, $phone, $message);
                
                $this->logWhatsappMessage($code, $phone, $message, $result);
            } else {
                Log::error('WhatsApp gateway not available or disabled');
                $this->logWhatsappMessage($code, $phone, $message, [
                    'status' => false,
                    'message' => 'WhatsApp gateway not available'
                ]);
            }
        } catch (Exception $e) {
            Log::error('WhatsApp service error: ' . $e->getMessage());
            $this->logWhatsappMessage($code, $phone, $message, [
                'status' => false,
                'message' => 'WhatsApp service error: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        }
    }

    private function logWhatsappMessage($code, $phone, $message, $result): void
    {
        try {
            $status = $result['status'] ?? false;
            $response = $result['response'] ?? null;
            $errorMessage = $result['error'] ?? null;

            \App\Models\WhatsappLog::create([
                'order_id' => $this->orderId,
                'phone_number' => $phone,
                'country_code' => $code,
                'message' => $message,
                'status' => $status ? 'success' : 'failed',
                'response' => $response ? json_encode($response) : null,
                'error_message' => $errorMessage,
                'gateway_used' => 'whatsapp',
                'metadata' => json_encode([
                    'template_used' => 'order_notification',
                    'recipient_type' => 'admin',
                    'order_id' => $this->orderId
                ]),
                'sent_at' => $status ? now() : null
            ]);
        } catch (Exception $e) {
            Log::error('Failed to log WhatsApp message: ' . $e->getMessage());
        }
    }

    public function sendCustomMessage($code, $phone, $customMessage = ''): array
    {
        try {
            $message = $this->buildOrderMessage($customMessage);
            $smsManagerService = new SmsManagerService();
            $smsService = new SmsService();
            
            if ($smsService->gateway() && $smsManagerService->gateway($smsService->gateway())->status()) {
                $result = $smsManagerService->gateway($smsService->gateway())->send($code, $phone, $message);
                $this->logWhatsappMessage($code, $phone, $message, $result);
                return $result;
            } else {
                $errorResult = [
                    'status' => false,
                    'message' => 'WhatsApp gateway not available'
                ];
                $this->logWhatsappMessage($code, $phone, $message, $errorResult);
                return $errorResult;
            }
        } catch (Exception $e) {
            Log::error('WhatsApp custom message error: ' . $e->getMessage());
            $errorResult = [
                'status' => false,
                'message' => 'WhatsApp service error: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
            $this->logWhatsappMessage($code, $phone, $message ?? '', $errorResult);
            return $errorResult;
        }
    }

    private function buildOrderMessage($customMessage = ''): string
    {
        $order = $this->order;
        
        if ($customMessage) {
            return $this->buildCustomMessage($customMessage, $order);
        }
        
        return $this->buildTemplateMessage($order);
    }

    private function buildTemplateMessage($order): string
    {
        $template = \App\Models\WhatsappTemplate::where('type', 'order_notification')
            ->where('is_active', true)
            ->first();
            
        if (!$template) {
            return $this->buildDefaultMessage($order);
        }
        
        $data = $this->prepareOrderData($order);
        return $template->replaceVariables($data);
    }

    private function buildCustomMessage($customMessage, $order): string
    {
        $data = $this->prepareOrderData($order);
        
        foreach ($data as $key => $value) {
            $customMessage = str_replace('{' . $key . '}', $value, $customMessage);
        }
        
        return $customMessage;
    }

    private function buildDefaultMessage($order): string
    {
        $message = "🛒 *NEW ORDER RECEIVED* 🛒\n\n";
        $message .= "📋 *Order Details:*\n";
        $message .= "• Order ID: `{$order->order_serial_no}`\n";
        $message .= "• Order Type: {$order->order_type}\n";
        $message .= "• Order Date: " . date('d/m/Y H:i', strtotime($order->order_datetime)) . "\n";
        $message .= "• Payment Method: {$order->payment_method}\n";
        $message .= "• Payment Status: {$order->payment_status}\n";
        $message .= "• Total Amount: Rp " . number_format($order->total, 0, ',', '.') . "\n\n";
        
        if ($order->user) {
            $message .= "👤 *Customer Info:*\n";
            $message .= "• Name: {$order->user->name}\n";
            $message .= "• Phone: {$order->user->phone}\n";
            $message .= "• Email: {$order->user->email}\n\n";
        }
        
        if ($order->branch) {
            $message .= "🏪 *Branch Info:*\n";
            $message .= "• Branch: {$order->branch->name}\n";
            $message .= "• Address: {$order->branch->address}\n\n";
        }
        
        if ($order->orderItems && count($order->orderItems) > 0) {
            $message .= "🍽️ *Order Items:*\n";
            foreach ($order->orderItems as $index => $item) {
                $message .= ($index + 1) . ". {$item->item_name}\n";
                $message .= "   Qty: {$item->quantity} x Rp " . number_format($item->price, 0, ',', '.') . "\n";
                if ($item->variation_name) {
                    $message .= "   Variation: {$item->variation_name}\n";
                }
                if ($item->extra_name) {
                    $message .= "   Extra: {$item->extra_name}\n";
                }
                $message .= "   Subtotal: Rp " . number_format($item->total, 0, ',', '.') . "\n\n";
            }
        }
        
        if ($order->delivery_address) {
            $message .= "📍 *Delivery Address:*\n";
            $message .= "• Address: {$order->delivery_address->address}\n";
            $message .= "• City: {$order->delivery_address->city}\n";
            $message .= "• Postal Code: {$order->delivery_address->postal_code}\n\n";
        }
        
        $message .= "⏰ *Timestamp:* " . date('d/m/Y H:i:s') . "\n";
        $message .= "🔗 *Order Link:* " . url('/admin/orders/' . $order->id);
        
        return $message;
    }

    private function prepareOrderData($order): array
    {
        $orderItems = '';
        if ($order->orderItems && count($order->orderItems) > 0) {
            foreach ($order->orderItems as $index => $item) {
                $orderItems .= ($index + 1) . ". {$item->item_name}\n";
                $orderItems .= "   Qty: {$item->quantity} x Rp " . number_format($item->price, 0, ',', '.') . "\n";
                if ($item->variation_name) {
                    $orderItems .= "   Variation: {$item->variation_name}\n";
                }
                if ($item->extra_name) {
                    $orderItems .= "   Extra: {$item->extra_name}\n";
                }
                $orderItems .= "   Subtotal: Rp " . number_format($item->total, 0, ',', '.') . "\n\n";
            }
        }

        $deliveryAddress = '';
        if ($order->delivery_address) {
            $deliveryAddress .= "• Address: {$order->delivery_address->address}\n";
            $deliveryAddress .= "• City: {$order->delivery_address->city}\n";
            $deliveryAddress .= "• Postal Code: {$order->delivery_address->postal_code}";
        }

        return [
            'order_id' => $order->order_serial_no,
            'order_type' => $order->order_type,
            'order_date' => date('d/m/Y H:i', strtotime($order->order_datetime)),
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'total_amount' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            'customer_name' => $order->user ? $order->user->name : 'N/A',
            'customer_phone' => $order->user ? $order->user->phone : 'N/A',
            'customer_email' => $order->user ? $order->user->email : 'N/A',
            'branch_name' => $order->branch ? $order->branch->name : 'N/A',
            'branch_address' => $order->branch ? $order->branch->address : 'N/A',
            'order_items' => $orderItems,
            'delivery_address' => $deliveryAddress,
            'timestamp' => date('d/m/Y H:i:s'),
            'order_link' => url('/admin/orders/' . $order->id)
        ];
    }
}
