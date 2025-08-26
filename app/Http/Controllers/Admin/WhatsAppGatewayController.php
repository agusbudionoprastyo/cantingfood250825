<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\WhatsAppGatewayRequest;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\Request;

class WhatsAppGatewayController extends Controller
{
    private WhatsAppGatewayService $whatsAppGatewayService;

    public function __construct(WhatsAppGatewayService $whatsAppGatewayService)
    {
        $this->whatsAppGatewayService = $whatsAppGatewayService;
    }

    public function index()
    {
        try {
            $settings = $this->whatsAppGatewayService->list();
            return response()->json([
                'status' => true,
                'data' => $settings
            ]);
        } catch (Exception $exception) {
            \Log::error('WhatsApp Gateway index error: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to load WhatsApp gateway settings',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(WhatsAppGatewayRequest $request)
    {
        try {
            $settings = $this->whatsAppGatewayService->update($request->validated());
            return response()->json([
                'status' => true,
                'data' => $settings,
                'message' => 'WhatsApp gateway settings updated successfully'
            ]);
        } catch (Exception $exception) {
            \Log::error('WhatsApp Gateway update error: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update WhatsApp gateway settings',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function testConnection()
    {
        try {
            $result = $this->whatsAppGatewayService->testConnection();
            return response()->json([
                'status' => true,
                'message' => 'WhatsApp connection test successful'
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 422);
        }
    }
}
