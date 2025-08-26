# Checkout Error 500 Troubleshooting 🔧

## Error yang Terjadi
```
POST https://cantingfood.my.id/api/table/dining-order 500 (Internal Server Error)
Error placing order: Request failed with status code 500
```

## Kemungkinan Penyebab

### 1. WhatsApp Notification Error
- Method `sendWhatsAppNotification` di `OrderService` error
- Settings WhatsApp belum dikonfigurasi dengan benar
- WhatsApp Service tidak bisa di-instantiate

### 2. Database Error
- Transaction rollback karena error
- Foreign key constraint violation
- Database connection issue

### 3. Validation Error
- Request validation gagal
- Required fields tidak terisi

## Langkah Troubleshooting

### Step 1: Cek Log Laravel
```bash
tail -f storage/logs/laravel.log
```

### Step 2: Test Order Service Manual
Jalankan script test ini di server:
```bash
php test_order_whatsapp.php
```

### Step 3: Disable WhatsApp Notification Sementara
Jika WhatsApp notification yang bermasalah, bisa di-disable sementara dengan mengubah settings:
```bash
php artisan tinker
```
```php
use Smartisan\Settings\Facades\Settings;
Settings::group('whatsapp_gateway')->set('whatsapp_enabled', false);
```

### Step 4: Test Checkout Tanpa WhatsApp
1. Pastikan WhatsApp notifications disabled
2. Coba checkout lagi
3. Jika berhasil, berarti masalah di WhatsApp notification

## Solusi Cepat

### Jika Masalah di WhatsApp Notification:

1. **Disable WhatsApp sementara**:
   ```bash
   php artisan tinker
   ```
   ```php
   use Smartisan\Settings\Facades\Settings;
   Settings::group('whatsapp_gateway')->set('whatsapp_enabled', false);
   ```

2. **Test checkout** - Seharusnya berhasil

3. **Konfigurasi WhatsApp dengan benar**:
   - Buka Admin Panel → Settings → WhatsApp Gateway
   - Pastikan semua field terisi dengan benar
   - Test connection
   - Enable notifications

### Jika Masalah di Database:

1. **Cek database connection**:
   ```bash
   php artisan migrate:status
   ```

2. **Cek foreign key constraints**:
   ```bash
   php artisan tinker
   ```
   ```php
   DB::select('SHOW CREATE TABLE orders');
   ```

3. **Cek table structure**:
   ```bash
   php artisan tinker
   ```
   ```php
   Schema::getColumnListing('orders');
   ```

## Debug Detail

### Cek Request Data
```bash
tail -f storage/logs/laravel.log | grep "dining-order"
```

### Cek WhatsApp Settings
```bash
php artisan tinker
```
```php
use Smartisan\Settings\Facades\Settings;
Settings::group('whatsapp_gateway')->all();
```

### Cek Order Service
```bash
php artisan tinker
```
```php
$service = new \App\Services\OrderService();
echo "Order Service created successfully";
```

## Verifikasi Fix

### Setelah Perbaikan:
1. **Test checkout** - Order harus berhasil dibuat
2. **Cek log** - Tidak ada error 500
3. **Test WhatsApp** - Jika enabled, notification harus terkirim

### Jika Masih Error:
1. **Cek log detail** untuk error spesifik
2. **Test manual** dengan script yang disediakan
3. **Disable fitur bermasalah** sementara

## Prevention

### Untuk Menghindari Error Serupa:
1. **Test WhatsApp settings** sebelum enable
2. **Validasi semua required fields**
3. **Error handling yang robust**
4. **Logging yang detail**

---

**Langkah selanjutnya**: Jalankan script test di server untuk identifikasi masalah spesifik.
