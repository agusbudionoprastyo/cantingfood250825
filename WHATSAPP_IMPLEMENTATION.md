# IMPLEMENTASI WHATSAPP NOTIFIKASI UNTUK ORDER

## **OVERVIEW**
Sistem notifikasi WhatsApp telah diimplementasikan untuk mengirim notifikasi otomatis saat ada order baru. Sistem menggunakan endpoint custom WhatsApp API yang disediakan.

## **FILE YANG DIBUAT/DIMODIFIKASI**

### **1. WhatsApp Gateway**
- **File**: `app/Http/SmsGateways/Gateways/Whatsapp.php`
- **Fungsi**: Implementasi gateway WhatsApp menggunakan endpoint custom
- **Endpoint**: `https://dev-iptv-wa.appdewa.com/message/send-text`
- **Method**: POST
- **Body**: 
  ```json
  {
    "session": "mysession",
    "to": "62812345678",
    "text": "message content"
  }
  ```

### **2. Request Validation**
- **File**: `app/Http/SmsGateways/Requests/Whatsapp.php`
- **Validasi**: API URL dan session ID

### **3. WhatsApp Service**
- **File**: `app/Services/WhatsappService.php`
- **Fungsi**: Service untuk mengirim notifikasi WhatsApp ke admin dan branch manager

### **4. Event & Listener**
- **Event**: `app/Events/SendOrderGotWhatsapp.php`
- **Listener**: `app/Listeners/SendOrderGotWhatsappNotification.php`
- **Fungsi**: Trigger otomatis saat order dibuat

### **5. Controller**
- **File**: `app/Http/Controllers/Admin/WhatsappController.php`
- **Endpoint Manual**:
  - `POST /api/admin/whatsapp/send-order-notification`
  - `POST /api/admin/whatsapp/send-bulk-notification`

### **6. Routes**
- **File**: `routes/api.php`
- **Routes**: 
  ```php
  Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
      Route::post('/send-order-notification', [WhatsappController::class, 'sendOrderNotification']);
      Route::post('/send-bulk-notification', [WhatsappController::class, 'sendBulkNotification']);
  });
  ```

### **7. Event Service Provider**
- **File**: `app/Providers/EventServiceProvider.php`
- **Registrasi**: Event WhatsApp dengan listener

### **8. Order Service**
- **File**: `app/Services/OrderService.php`
- **Modifikasi**: Dispatch event WhatsApp saat order dibuat

### **9. Migration**
- **File**: `database/migrations/2024_01_01_000000_add_whatsapp_to_notification_alerts_table.php`
- **Fungsi**: Tambah kolom WhatsApp di tabel notification_alerts

### **10. Seeder**
- **File**: `database/seeders/WhatsappGatewaySeeder.php`
- **Fungsi**: Setup gateway WhatsApp default

## **CARA PENGGUNAAN**

### **1. Setup Database**
```bash
php artisan migrate
php artisan db:seed --class=WhatsappGatewaySeeder
```

### **2. Konfigurasi WhatsApp Gateway**
- Buka admin panel
- Masuk ke Settings > SMS Gateway
- Pilih WhatsApp
- Isi:
  - API URL: `https://dev-iptv-wa.appdewa.com`
  - Session: `mysession` (atau session yang diinginkan)
  - Status: Enable

### **3. Konfigurasi Notifikasi Alert**
- Buka admin panel
- Masuk ke Settings > Notification Alert
- Aktifkan WhatsApp untuk "admin_and_branch_manager_new_order_message"
- Isi pesan WhatsApp yang diinginkan

### **4. Penggunaan Manual via API**

#### **Kirim Notifikasi Single**
```bash
curl -X POST "https://yourdomain.com/api/admin/whatsapp/send-order-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 123,
  "phone": "62812345678",
  "country_code": "62",
  "message": "Pesan custom"
}'
```

#### **Kirim Notifikasi Bulk**
```bash
curl -X POST "https://yourdomain.com/api/admin/whatsapp/send-bulk-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 123,
  "phones": [
    {
      "phone": "62812345678",
      "country_code": "62"
    },
    {
      "phone": "62887654321",
      "country_code": "62"
    }
  ],
  "message": "Pesan custom"
}'
```

## **ALUR KERJA OTOMATIS**

1. **Order dibuat** → `OrderService::store()`
2. **Event di-dispatch** → `SendOrderGotWhatsapp::dispatch()`
3. **Listener dipanggil** → `SendOrderGotWhatsappNotification::handle()`
4. **WhatsApp dikirim** → `WhatsappService::send()`
5. **Gateway dipanggil** → `Whatsapp::send()`
6. **API dipanggil** → `https://dev-iptv-wa.appdewa.com/message/send-text`

## **KONFIGURASI ENDPOINT**

### **Endpoint WhatsApp API**
- **URL**: `https://dev-iptv-wa.appdewa.com/message/send-text`
- **Method**: POST
- **Headers**: `Content-Type: application/json`
- **Body**:
  ```json
  {
    "session": "mysession",
    "to": "62812345678",
    "text": "Order ID : ORD123 - New order received"
  }
  ```

### **Response Success**
```json
{
  "status": true,
  "message": "WhatsApp notification sent successfully"
}
```

### **Response Error**
```json
{
  "status": false,
  "message": "Failed to send WhatsApp notification: Error details"
}
```

## **KEAMANAN**

- **Authentication**: Menggunakan middleware `auth:sanctum`
- **Permission**: Hanya admin yang bisa akses
- **Validation**: Validasi input untuk order_id, phone, country_code
- **Logging**: Semua error dan success di-log

## **TROUBLESHOOTING**

### **1. WhatsApp tidak terkirim**
- Cek status gateway WhatsApp (Enable/Disable)
- Cek konfigurasi API URL dan session
- Cek log error di `storage/logs/laravel.log`

### **2. Permission denied**
- Pastikan user sudah login dengan token valid
- Pastikan user memiliki role admin

### **3. Order not found**
- Pastikan order_id valid dan ada di database
- Cek tabel `frontend_orders`

## **TESTING**

### **Test Endpoint Manual**
```bash
# Test single notification
curl -X POST "http://localhost:8000/api/admin/whatsapp/send-order-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 1,
  "phone": "62812345678",
  "country_code": "62"
}'
```

### **Test Otomatis**
1. Buat order baru melalui sistem
2. Cek apakah event WhatsApp ter-trigger
3. Cek log untuk konfirmasi pengiriman

## **PERFORMA**

- **Async**: Event dan listener berjalan async
- **Bulk**: Support pengiriman bulk untuk multiple phone
- **Error Handling**: Graceful error handling dengan logging
- **Rate Limiting**: Bisa ditambahkan middleware rate limiting jika diperlukan

## **MONITORING**

- **Logs**: Semua aktivitas di-log di `storage/logs/laravel.log`
- **Success Rate**: Track success/failed count untuk bulk notification
- **Response Time**: Monitor response time dari WhatsApp API

## **KESIMPULAN**

Sistem notifikasi WhatsApp telah berhasil diimplementasikan dengan:
- ✅ Gateway WhatsApp custom
- ✅ Event-driven architecture
- ✅ Manual dan otomatis notification
- ✅ Bulk notification support
- ✅ Proper error handling
- ✅ Security middleware
- ✅ Comprehensive logging
- ✅ Easy configuration

Sistem siap digunakan untuk production dengan konfigurasi yang tepat.
