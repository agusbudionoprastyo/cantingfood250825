# Server Setup WhatsApp Gateway 🚀

## Langkah-langkah di Server

### 1. Jalankan Database Seeder
```bash
php artisan db:seed --class=WhatsAppGatewayTableSeeder
```

### 2. Atau Jalankan Script Manual (Alternatif)
Jika seeder tidak berhasil, jalankan script ini:
```bash
php setup_whatsapp_gateway.php
```

### 3. Clear Cache Laravel
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 4. Clear Browser Cache
- Tekan `Ctrl+F5` (Windows) atau `Cmd+Shift+R` (Mac)
- Atau buka Developer Tools → Network → Disable cache

## Verifikasi Setup

### Cek Settings di Database
```bash
php artisan tinker
```
Lalu jalankan:
```php
use Smartisan\Settings\Facades\Settings;
Settings::group('whatsapp_gateway')->all();
```

### Cek API Endpoint
```bash
curl -X GET "https://cantingfood.my.id/api/admin/whatsapp-gateway" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

## Troubleshooting

### Jika Error 500 Masih Muncul:

1. **Cek Log Laravel**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Cek Database Connection**:
   ```bash
   php artisan migrate:status
   ```

3. **Cek Settings Table**:
   ```bash
   php artisan tinker
   ```
   ```php
   DB::table('settings')->where('group', 'whatsapp_gateway')->get();
   ```

### Jika Settings Tidak Ada:

Jalankan script manual ini di server:
```bash
php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Smartisan\Settings\Facades\Settings;

\$settings = [
    'whatsapp_enabled' => false,
    'whatsapp_api_url' => 'https://dev-iptv-wa.appdewa.com/message/send-text',
    'whatsapp_session' => 'mysession',
    'whatsapp_phone' => '62812345678',
    'whatsapp_company_name' => 'Canting Food',
    'whatsapp_message_template' => ''
];

foreach (\$settings as \$key => \$value) {
    Settings::group('whatsapp_gateway')->set(\$key, \$value);
    echo \"Set {\$key} = {\$value}\n\";
}

echo \"Settings setup completed!\n\";
"
```

## Menu WhatsApp Gateway

Setelah setup berhasil, menu akan muncul di:
**Admin Panel → Settings → WhatsApp Gateway**

## Konfigurasi

1. **Enable WhatsApp Notifications** - Aktifkan checkbox
2. **API URL** - `https://dev-iptv-wa.appdewa.com/message/send-text`
3. **Session** - `mysession`
4. **Phone Number** - `62812345678` (atau nomor yang diinginkan)
5. **Company Name** - `Canting Food`
6. **Message Template** - Template pesan kustom (opsional)

## Test Connection

Klik tombol "Test Connection" untuk memverifikasi koneksi WhatsApp.

---

**Semua file sudah siap dan build berhasil. Tinggal jalankan di server! 🎯**
