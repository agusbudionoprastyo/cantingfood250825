<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\WhatsAppGatewayService;
use Smartisan\Settings\Facades\Settings;

echo "=== Testing WhatsApp Gateway Controller ===\n\n";

try {
    echo "1. Testing WhatsAppGatewayService...\n";
    $service = new WhatsAppGatewayService();
    $settings = $service->list();
    echo "✅ Service working - Found " . count($settings) . " settings\n";
    
    foreach ($settings as $key => $value) {
        echo "   - {$key}: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value) . "\n";
    }
    
    echo "\n2. Testing Settings Facade directly...\n";
    $directSettings = Settings::group('whatsapp_gateway')->all();
    echo "✅ Settings Facade working - Found " . count($directSettings) . " settings\n";
    
    echo "\n3. Testing individual settings...\n";
    $enabled = Settings::group('whatsapp_gateway')->get('whatsapp_enabled', false);
    $apiUrl = Settings::group('whatsapp_gateway')->get('whatsapp_api_url', '');
    $session = Settings::group('whatsapp_gateway')->get('whatsapp_session', '');
    $phone = Settings::group('whatsapp_gateway')->get('whatsapp_phone', '');
    
    echo "   - whatsapp_enabled: " . ($enabled ? 'true' : 'false') . "\n";
    echo "   - whatsapp_api_url: {$apiUrl}\n";
    echo "   - whatsapp_session: {$session}\n";
    echo "   - whatsapp_phone: {$phone}\n";
    
    echo "\n✅ All tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
