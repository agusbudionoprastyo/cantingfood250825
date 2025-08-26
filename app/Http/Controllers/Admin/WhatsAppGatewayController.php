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
        parent::__construct();
        $this->whatsAppGatewayService = $whatsAppGatewayService;
        $this->middleware(['permission:settings'])->only(['index', 'update', 'testConnection']);
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
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 422);
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
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 422);
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
