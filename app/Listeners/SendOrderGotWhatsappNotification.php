<?php

namespace App\Listeners;

use App\Events\SendOrderGotWhatsapp;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class SendOrderGotWhatsappNotification
{
    public function handle(SendOrderGotWhatsapp $event): void
    {
        try {
            $whatsappService = new WhatsappService($event->info['order_id']);
            $whatsappService->send();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
