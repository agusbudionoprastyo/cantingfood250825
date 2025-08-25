<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendOrderGotWhatsapp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $info;
    
    public function __construct($info)
    {
        $this->info = $info;
    }
}
