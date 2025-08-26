<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Smartisan\Settings\Facades\Settings;

try {
    echo "Setting up WhatsApp Gateway settings...\n";
    
    $settings = [
        'whatsapp_enabled' => false,
        'whatsapp_api_url' => 'https://dev-iptv-wa.appdewa.com/message/send-text',
        'whatsapp_session' => 'mysession',
        'whatsapp_phone' => '62812345678',
        'whatsapp_company_name' => 'Canting Food',
        'whatsapp_message_template' => ''
    ];
    
    foreach ($settings as $key => $value) {
        Settings::group('whatsapp_gateway')->set($key, $value);
        echo "✓ Set {$key} = " . (is_bool($value) ? ($value ? 'true' : 'false') : $value) . "\n";
    }
    
    echo "\n✅ WhatsApp Gateway settings setup completed!\n";
    
    $allSettings = Settings::group('whatsapp_gateway')->all();
    echo "\nCurrent settings:\n";
    foreach ($allSettings as $key => $value) {
        echo "- {$key}: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
