<?php

echo "=== Fixing Autoload Issues ===\n\n";

try {
    echo "1. Regenerating autoload...\n";
    exec('composer dump-autoload', $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Autoload regenerated successfully\n";
    } else {
        echo "❌ Failed to regenerate autoload\n";
        echo "Output: " . implode("\n", $output) . "\n";
    }
    
    echo "\n2. Testing WhatsApp Service class...\n";
    
    if (class_exists('App\Services\WhatsAppService')) {
        echo "✅ WhatsAppService class found\n";
        
        $service = new \App\Services\WhatsAppService();
        echo "✅ WhatsAppService instantiated successfully\n";
        
        echo "\n3. Testing Order Service class...\n";
        if (class_exists('App\Services\OrderService')) {
            echo "✅ OrderService class found\n";
            
            $orderService = new \App\Services\OrderService();
            echo "✅ OrderService instantiated successfully\n";
            
            echo "\n✅ All classes working properly!\n";
        } else {
            echo "❌ OrderService class not found\n";
        }
    } else {
        echo "❌ WhatsAppService class not found\n";
        
        echo "\n4. Checking file existence...\n";
        $files = [
            'app/Services/WhatsAppService.php',
            'app/Services/WhatsappService.php'
        ];
        
        foreach ($files as $file) {
            if (file_exists($file)) {
                echo "✅ File exists: {$file}\n";
            } else {
                echo "❌ File not found: {$file}\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
