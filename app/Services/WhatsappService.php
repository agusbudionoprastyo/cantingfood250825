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
                $smsManagerService->gateway($smsService->gateway())->send($code, $phone, $message);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function sendCustomMessage($code, $phone, $customMessage = ''): void
    {
        try {
            $message = $this->buildOrderMessage($customMessage);
            $smsManagerService = new SmsManagerService();
            $smsService = new SmsService();
            if ($smsService->gateway() && $smsManagerService->gateway($smsService->gateway())->status()) {
                $smsManagerService->gateway($smsService->gateway())->send($code, $phone, $message);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function buildOrderMessage($customMessage = ''): string
    {
        $order = $this->order;
        
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
        
        if ($customMessage) {
            $message .= "💬 *Additional Message:*\n";
            $message .= "{$customMessage}\n\n";
        }
        
        $message .= "⏰ *Timestamp:* " . date('d/m/Y H:i:s') . "\n";
        $message .= "🔗 *Order Link:* " . url('/admin/orders/' . $order->id);
        
        return $message;
    }
}
