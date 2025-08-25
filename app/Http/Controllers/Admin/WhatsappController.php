<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendOrder;
use App\Services\WhatsappService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    public function sendOrderNotification(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:frontend_orders,id',
                'phone' => 'nullable|string',
                'country_code' => 'nullable|string',
                'message' => 'nullable|string'
            ]);

            $order = FrontendOrder::find($request->order_id);
            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            $whatsappService = new WhatsappService($request->order_id);
            
            $phone = $request->phone ?? 'default';
            $countryCode = $request->country_code ?? 'default';
            
            $result = $whatsappService->sendCustomMessage($countryCode, $phone, $request->message ?? '');

            return response()->json([
                'status' => true,
                'message' => 'WhatsApp notification sent successfully',
                'data' => $result
            ]);

        } catch (Exception $exception) {
            Log::error('WhatsApp notification error: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to send WhatsApp notification: ' . $exception->getMessage()
            ], 500);
        }
    }

    public function testEndpoint(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string',
                'country_code' => 'required|string',
                'message' => 'required|string'
            ]);

            $phoneNumber = $request->country_code . $request->phone;
            
            $smsManagerService = new \App\Services\SmsManagerService();
            $smsService = new \App\Services\SmsService();
            
            if ($smsService->gateway() && $smsManagerService->gateway($smsService->gateway())->status()) {
                $result = $smsManagerService->gateway($smsService->gateway())->send(
                    $request->country_code, 
                    $request->phone, 
                    $request->message
                );
                
                return response()->json([
                    'status' => true,
                    'message' => 'Test message sent',
                    'data' => [
                        'phone' => $phoneNumber,
                        'message' => $request->message,
                        'result' => $result
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'WhatsApp gateway not available or disabled'
                ], 400);
            }

        } catch (Exception $exception) {
            Log::error('WhatsApp test error: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to send test message: ' . $exception->getMessage()
            ], 500);
        }
    }

    public function sendBulkNotification(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:frontend_orders,id',
                'phones' => 'required|array',
                'phones.*.phone' => 'required|string',
                'phones.*.country_code' => 'required|string',
                'message' => 'nullable|string'
            ]);

            $order = FrontendOrder::find($request->order_id);
            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            $whatsappService = new WhatsappService($request->order_id);
            
            $successCount = 0;
            $failedCount = 0;

            foreach ($request->phones as $phoneData) {
                try {
                    $whatsappService->sendCustomMessage($phoneData['country_code'], $phoneData['phone'], $request->message ?? '');
                    $successCount++;
                } catch (Exception $e) {
                    $failedCount++;
                    Log::error('Failed to send WhatsApp to ' . $phoneData['country_code'] . $phoneData['phone'] . ': ' . $e->getMessage());
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Bulk WhatsApp notifications sent',
                'data' => [
                    'success_count' => $successCount,
                    'failed_count' => $failedCount,
                    'total_count' => count($request->phones)
                ]
            ]);

        } catch (Exception $exception) {
            Log::error('Bulk WhatsApp notification error: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to send bulk WhatsApp notifications: ' . $exception->getMessage()
            ], 500);
        }
    }
}
