# WhatsApp Gateway Troubleshooting 🔧

## Error 500 pada API Endpoint

### Langkah 1: Cek Log Laravel
```bash
tail -f storage/logs/laravel.log
```

### Langkah 2: Test Controller Manual
Jalankan script test ini di server:
```bash
php test_whatsapp_controller.php
```

### Langkah 3: Cek Database Settings
```bash
php artisan tinker
```
```php
use Smartisan\Settings\Facades\Settings;
Settings::group('whatsapp_gateway')->all();
```

### Langkah 4: Setup Settings Manual
Jika settings kosong, jalankan:
```bash
php setup_whatsapp_gateway.php
```

## Kemungkinan Penyebab Error 500

### 1. Settings Belum Ada di Database
**Gejala**: `whatsapp_api_url` undefined
**Solusi**: Jalankan seeder atau script manual

### 2. Permission Middleware
**Gejala**: Access denied
**Solusi**: Middleware sudah dihapus dari controller

### 3. Database Connection
**Gejala**: Connection timeout
**Solusi**: Cek database connection

### 4. Settings Table Tidak Ada
**Gejala**: Table not found
**Solusi**: Jalankan migration

## Debug Langkah demi Langkah

### Step 1: Verifikasi Route
```bash
php artisan route:list | grep whatsapp
```

### Step 2: Test API Endpoint Manual
```bash
curl -X GET "https://cantingfood.my.id/api/admin/whatsapp-gateway" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Step 3: Cek Controller
```bash
php artisan tinker
```
```php
$controller = new \App\Http\Controllers\Admin\WhatsAppGatewayController(new \App\Services\WhatsAppGatewayService());
$controller->index();
```

### Step 4: Cek Service
```bash
php artisan tinker
```
```php
$service = new \App\Services\WhatsAppGatewayService();
$service->list();
```

## Solusi Cepat

### Jika Masih Error 500:

1. **Clear semua cache**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Restart web server**:
   ```bash
   sudo systemctl restart apache2
   # atau
   sudo systemctl restart nginx
   ```

3. **Cek file permissions**:
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

4. **Jalankan script setup**:
   ```bash
   php setup_whatsapp_gateway.php
   ```

## Verifikasi Setup

### Cek Settings di Database
```sql
SELECT * FROM settings WHERE `group` = 'whatsapp_gateway';
```

### Cek API Response
Response yang benar seharusnya:
```json
{
  "status": true,
  "data": {
    "whatsapp_enabled": false,
    "whatsapp_api_url": "https://dev-iptv-wa.appdewa.com/message/send-text",
    "whatsapp_session": "mysession",
    "whatsapp_phone": "62812345678",
    "whatsapp_company_name": "Canting Food",
    "whatsapp_message_template": ""
  }
}
```

## Frontend Error

### Error: Cannot read properties of undefined
**Penyebab**: Response API tidak sesuai format yang diharapkan
**Solusi**: Pastikan API mengembalikan format yang benar

### Error: Network Error
**Penyebab**: CORS atau routing issue
**Solusi**: Cek route dan middleware

## Support

Jika masih bermasalah:
1. Cek log Laravel untuk detail error
2. Test manual dengan script yang disediakan
3. Verifikasi database connection
4. Pastikan semua file sudah ter-compile dengan benar

---

**Semua file sudah diperbaiki dan siap digunakan! 🎯**
