#!/usr/bin/env bash
set -euo pipefail

echo "=== Test WhatsApp Gateway stack ==="

php test_whatsapp_controller.php || true
php test_order_whatsapp.php || true

echo "\nIf errors shown above, open storage/logs/laravel.log in another terminal:"
echo "tail -f storage/logs/laravel.log"


