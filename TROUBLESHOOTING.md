# WhatsApp Gateway Troubleshooting Guide

## Masalah: Menu WhatsApp Gateway Tidak Muncul

### Solusi 1: Jalankan Seeder
```bash
php artisan db:seed --class=WhatsAppGatewayTableSeeder
```

### Solusi 2: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Solusi 3: Rebuild Frontend
```bash
npm run prod
```

### Solusi 4: Clear Browser Cache
- Tekan `Ctrl+F5` (Windows) atau `Cmd+Shift+R` (Mac)
- Atau buka Developer Tools → Network → Disable cache

## Masalah: Error 500 pada Test Connection

### Solusi 1: Periksa Log Laravel
```bash
tail -f storage/logs/laravel.log
```

### Solusi 2: Periksa Settings
Pastikan semua pengaturan WhatsApp Gateway sudah diisi:
- WhatsApp Enabled: true
- API URL: https://dev-iptv-wa.appdewa.com/message/send-text
- Session: mysession
- Phone Number: 62812345678

### Solusi 3: Test Manual
Jalankan script test:
```bash
php test_whatsapp_gateway.php
```

## Masalah: Route Tidak Ditemukan

### Solusi 1: Periksa Route List
```bash
php artisan route:list | grep whatsapp
```

### Solusi 2: Periksa File Routes
Pastikan file `routes/api.php` memiliki:
```php
Route::prefix('whatsapp-gateway')->name('whatsapp-gateway.')->group(function () {
    Route::get('/', [WhatsAppGatewayController::class, 'index']);
    Route::post('/', [WhatsAppGatewayController::class, 'update']);
    Route::post('/test-connection', [WhatsAppGatewayController::class, 'testConnection']);
});
```

## Masalah: Component Tidak Load

### Solusi 1: Periksa Import
Pastikan file `resources/js/router/modules/settingRoutes.js` memiliki:
```javascript
import WhatsAppGatewayComponent from "../../components/admin/settings/WhatsAppGateway/WhatsAppGatewayComponent";
```

### Solusi 2: Periksa Route Definition
Pastikan ada route definition:
```javascript
{
    path: "whatsapp-gateway",
    component: WhatsAppGatewayComponent,
    name: "admin.settings.whatsappGateway",
    meta: {
        isFrontend: false,
        auth: true,
        permissionUrl: "settings",
        breadcrumb: "whatsapp_gateway",
    },
}
```

## Masalah: Store Module Tidak Load

### Solusi 1: Periksa Store Import
Pastikan file `resources/js/store/index.js` memiliki:
```javascript
import { whatsappGateway } from './modules/whatsappGateway';
```

### Solusi 2: Periksa Store Registration
Pastikan store terdaftar:
```javascript
modules: {
    // ... other modules
    whatsappGateway
}
```

## Verifikasi Implementasi

### Langkah 1: Jalankan Setup Script
```bash
php setup_whatsapp_gateway.php
```

### Langkah 2: Periksa Database
```sql
SELECT * FROM settings WHERE `group` = 'whatsapp_gateway';
```

### Langkah 3: Test API Endpoint
```bash
curl -X GET "http://your-domain.com/api/admin/whatsapp-gateway" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Debug Mode

### Aktifkan Debug Laravel
Di file `.env`:
```
APP_DEBUG=true
```

### Periksa Error Log
```bash
tail -f storage/logs/laravel.log
```

### Periksa Browser Console
- Buka Developer Tools (F12)
- Lihat tab Console untuk error JavaScript
- Lihat tab Network untuk error API

## Common Issues

### Issue 1: Permission Denied
Pastikan user memiliki permission `settings`

### Issue 2: CORS Error
Pastikan CORS dikonfigurasi dengan benar di `config/cors.php`

### Issue 3: CSRF Token Error
Pastikan CSRF token terkirim dengan benar

### Issue 4: Database Connection
Pastikan koneksi database berfungsi dengan baik

## Support

Jika masih mengalami masalah, periksa:
1. Laravel version compatibility
2. PHP version compatibility
3. Node.js version compatibility
4. Database connection
5. File permissions
