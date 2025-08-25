# 📱 WHATSAPP DEFAULT PHONE CONFIGURATION GUIDE

## 🎯 **OVERVIEW**

Panduan lengkap untuk mengkonfigurasi nomor telepon default untuk notifikasi WhatsApp. Sekarang admin bisa mengatur nomor tujuan default di form WhatsApp settings.

---

## 🔧 **KONFIGURASI FORM WHATSAPP**

### **Field Baru yang Ditambahkan:**

1. **LABEL.WHATSAPP_DEFAULT_PHONE** - Nomor telepon default untuk notifikasi
2. **LABEL.WHATSAPP_COUNTRY_CODE** - Kode negara default

### **Form WhatsApp Settings:**
```
┌─────────────────────────────────────────────────────────┐
│                    WhatsApp Settings                    │
├─────────────────────────────────────────────────────────┤
│ LABEL.WHATSAPP_API_URL:                                │
│ [https://dev-iptv-wa.appdewa.com]                      │
│                                                         │
│ LABEL.WHATSAPP_SESSION:                                │
│ [mysession]                                            │
│                                                         │
│ LABEL.WHATSAPP_DEFAULT_PHONE:                          │
│ [812345678]                                            │
│                                                         │
│ LABEL.WHATSAPP_COUNTRY_CODE:                           │
│ [62]                                                   │
│                                                         │
│ LABEL.WHATSAPP_STATUS:                                 │
│ [Enable ▼]                                             │
│                                                         │
│                    [✓ Save]                            │
└─────────────────────────────────────────────────────────┘
```

---

## 🚀 **CARA PENGGUNAAN**

### **1. Konfigurasi Default Phone**

#### **Via Admin Panel:**
1. Buka admin panel
2. Settings > SMS Gateway > WhatsApp
3. Isi field baru:
   - **Default Phone**: `812345678` (tanpa kode negara)
   - **Country Code**: `62` (untuk Indonesia)
4. Save settings

#### **Via Database:**
```sql
-- Update default phone
UPDATE gateway_options 
SET value = '812345678' 
WHERE option = 'whatsapp_default_phone' 
AND model_type = 'App\\Models\\SmsGateway';

-- Update country code
UPDATE gateway_options 
SET value = '62' 
WHERE option = 'whatsapp_country_code' 
AND model_type = 'App\\Models\\SmsGateway';
```

### **2. Penggunaan dalam Kode**

#### **Manual Send dengan Default Phone:**
```php
// Menggunakan default phone dari settings
$whatsappService = new WhatsappService($orderId);
$result = $whatsappService->sendCustomMessage('default', 'default', $message);
```

#### **API Call dengan Default Phone:**
```bash
# Tanpa phone & country_code (akan menggunakan default)
curl -X POST "https://yourdomain.com/api/admin/whatsapp/send-order-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 123,
  "message": "Custom message"
}'
```

#### **API Call dengan Phone Custom:**
```bash
# Dengan phone & country_code custom
curl -X POST "https://yourdomain.com/api/admin/whatsapp/send-order-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 123,
  "phone": "87654321",
  "country_code": "62",
  "message": "Custom message"
}'
```

---

## 📋 **VALIDASI INPUT**

### **Validation Rules:**
```php
'whatsapp_default_phone' => ['required', 'string', 'regex:/^[0-9]+$/']
'whatsapp_country_code' => ['required', 'string', 'regex:/^[0-9]+$/']
```

### **Format yang Diterima:**
- **Default Phone**: `812345678` (hanya angka, tanpa kode negara)
- **Country Code**: `62` (hanya angka)

### **Format yang Tidak Diterima:**
- **Default Phone**: `+62812345678`, `62812345678`, `0812345678`
- **Country Code**: `+62`, `+62`, `62+`

---

## 🔄 **ALUR KERJA**

### **1. Pengiriman dengan Default Phone:**
```
1. User call API tanpa phone/country_code
2. System check gateway options
3. Get default_phone: "812345678"
4. Get country_code: "62"
5. Combine: "62812345678"
6. Send to WhatsApp API
```

### **2. Pengiriman dengan Custom Phone:**
```
1. User call API dengan phone/country_code
2. System use provided values
3. Combine: country_code + phone
4. Send to WhatsApp API
```

---

## 🧪 **TESTING**

### **1. Test Default Phone Configuration:**
```bash
# Test tanpa phone (gunakan default)
curl -X POST "https://yourdomain.com/api/admin/whatsapp/send-order-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 1
}'
```

### **2. Test Custom Phone:**
```bash
# Test dengan phone custom
curl -X POST "https://yourdomain.com/api/admin/whatsapp/send-order-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 1,
  "phone": "87654321",
  "country_code": "62"
}'
```

### **3. Test Direct Endpoint:**
```bash
# Test endpoint langsung
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "Test default phone configuration"
}'
```

---

## 📊 **MONITORING**

### **1. Check Gateway Options:**
```bash
php artisan tinker --execute="
\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
\$options = \$gateway->gatewayOptions->pluck('value', 'option');
echo 'Default Phone: ' . (\$options['whatsapp_default_phone'] ?? 'Not set') . PHP_EOL;
echo 'Country Code: ' . (\$options['whatsapp_country_code'] ?? 'Not set') . PHP_EOL;
"
```

### **2. Check WhatsApp Logs:**
```sql
-- Check recent WhatsApp logs
SELECT 
    phone_number,
    country_code,
    status,
    created_at
FROM whatsapp_logs 
ORDER BY created_at DESC 
LIMIT 10;
```

---

## 🚨 **TROUBLESHOOTING**

### **1. Default Phone Not Working:**
```bash
# Check gateway options
php artisan tinker --execute="
\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
print_r(\$gateway->gatewayOptions->pluck('value', 'option')->toArray());
"
```

### **2. Validation Error:**
```bash
# Check validation rules
php artisan tinker --execute="
\$request = new \App\Http\SmsGateways\Requests\Whatsapp();
print_r(\$request->rules());
"
```

### **3. Phone Format Error:**
```bash
# Test phone format
php artisan tinker --execute="
\$phone = '812345678';
\$countryCode = '62';
\$fullPhone = \$countryCode . \$phone;
echo 'Full Phone: ' . \$fullPhone;
"
```

---

## ✅ **SUCCESS CRITERIA**

- [ ] Form WhatsApp settings menampilkan field baru
- [ ] Default phone bisa disimpan dan diambil
- [ ] API call tanpa phone menggunakan default
- [ ] API call dengan phone menggunakan custom
- [ ] Validation berfungsi dengan benar
- [ ] WhatsApp terkirim ke nomor yang benar
- [ ] Logs mencatat pengiriman dengan benar

---

## 🎯 **BENEFITS**

### **1. Fleksibilitas:**
- ✅ Bisa set default phone untuk semua notifikasi
- ✅ Bisa override dengan phone custom per request
- ✅ Tidak perlu input phone setiap kali

### **2. Kemudahan:**
- ✅ Admin tinggal set sekali di settings
- ✅ Otomatis digunakan jika tidak ada phone custom
- ✅ Format phone yang konsisten

### **3. Keamanan:**
- ✅ Validasi format phone
- ✅ Validasi country code
- ✅ Error handling yang proper

---

**🎉 Default phone configuration siap digunakan!**

Sekarang admin bisa dengan mudah mengatur nomor tujuan default untuk notifikasi WhatsApp! 📱
