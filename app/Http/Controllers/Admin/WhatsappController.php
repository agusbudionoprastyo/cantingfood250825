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
                'phone' => 'required|string',
                'country_code' => 'required|string',
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
            
            $whatsappService->sendCustomMessage($request->country_code, $request->phone, $request->message ?? '');

            return response()->json([
                'status' => true,
                'message' => 'WhatsApp notification sent successfully'
            ]);

        } catch (Exception $exception) {
            Log::error('WhatsApp notification error: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to send WhatsApp notification: ' . $exception->getMessage()
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
