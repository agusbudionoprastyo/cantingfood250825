# ⚡ QUICK DEPLOYMENT GUIDE - HOSTING

## 🚀 **DEPLOYMENT CEPAT (5 MENIT)**

### **STEP 1: Upload Files**
1. Upload semua file yang sudah kita buat ke hosting
2. Pastikan struktur folder tetap sama
3. Exclude: `vendor/`, `node_modules/`, `.git/`

### **STEP 2: Run Script Deployment**
```bash
# Via cPanel Terminal atau SSH
chmod +x deploy-whatsapp-hosting.sh
./deploy-whatsapp-hosting.sh
```

### **STEP 3: Configure Environment**
```bash
# Update .env file
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### **STEP 4: Configure Admin Panel**
1. Buka admin panel
2. Settings > SMS Gateway > WhatsApp
3. Set API URL: `https://dev-iptv-wa.appdewa.com`
4. Set Session: `mysession`
5. Enable gateway

### **STEP 5: Test System**
```bash
# Test endpoint
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "Test deployment"
}'
```

---

## 📋 **FILE CHECKLIST**

### **✅ Upload These Files:**
- [ ] `database/migrations/2024_01_01_000000_add_whatsapp_to_notification_alerts_table.php`
- [ ] `database/migrations/2024_01_02_000000_create_whatsapp_logs_table.php`
- [ ] `database/migrations/2024_01_03_000000_create_whatsapp_templates_table.php`
- [ ] `database/seeders/WhatsappGatewaySeeder.php`
- [ ] `database/seeders/WhatsappTemplateSeeder.php`
- [ ] `database/seeders/NotificationAlertTableSeeder.php`
- [ ] `app/Models/WhatsappLog.php`
- [ ] `app/Models/WhatsappTemplate.php`
- [ ] `app/Http/Controllers/Admin/WhatsappController.php`
- [ ] `app/Http/Controllers/Admin/WhatsappTemplateController.php`
- [ ] `app/Services/WhatsappService.php`
- [ ] `app/Http/SmsGateways/Gateways/Whatsapp.php`
- [ ] `routes/api.php` (updated)
- [ ] `app/Http/Requests/WhatsappTemplateRequest.php`
- [ ] `app/Http/Resources/WhatsappTemplateResource.php`
- [ ] `resources/js/components/admin/whatsapp/WhatsappTemplateManager.vue`
- [ ] `deploy-whatsapp-hosting.sh`
- [ ] `test-whatsapp-endpoint.sh`

---

## 🔧 **HOSTING REQUIREMENTS**

### **PHP Configuration:**
- ✅ PHP 8.0.2+
- ✅ Extensions: exif, http, json, pdo, mbstring, openssl
- ✅ Memory Limit: 256M+
- ✅ Max Execution Time: 300s

### **Database:**
- ✅ MySQL 5.7+ atau MariaDB 10.2+
- ✅ Database user dengan privileges

### **SSL:**
- ✅ SSL certificate enabled
- ✅ HTTPS redirect

---

## 🧪 **QUICK TESTING**

### **1. Test Database:**
```bash
php artisan tinker --execute="
echo 'WhatsApp Tables:';
echo 'notification_alerts: ' . \App\Models\NotificationAlert::count();
echo 'whatsapp_logs: ' . \App\Models\WhatsappLog::count();
echo 'whatsapp_templates: ' . \App\Models\WhatsappTemplate::count();
"
```

### **2. Test Gateway:**
```bash
php artisan tinker --execute="
\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
echo 'Gateway: ' . \$gateway->name . ' (Status: ' . \$gateway->status . ')';
"
```

### **3. Test Endpoint:**
```bash
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "Quick test"
}'
```

---

## 🚨 **TROUBLESHOOTING**

### **Migration Error:**
```bash
php artisan migrate:status
php artisan migrate:rollback --step=1
php artisan migrate
```

### **Permission Error:**
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
```

### **Gateway Not Found:**
```bash
php artisan db:seed --class=WhatsappGatewaySeeder
```

---

## ✅ **SUCCESS INDICATORS**

- [ ] Script deployment berhasil
- [ ] Database tables terbuat
- [ ] WhatsApp gateway aktif
- [ ] Templates tersedia
- [ ] Endpoint test berhasil
- [ ] Admin panel bisa diakses

---

## 🎯 **POST-DEPLOYMENT**

1. **Configure Admin Panel**
   - Set up WhatsApp gateway
   - Add admin phone numbers
   - Enable notifications

2. **Test with Orders**
   - Create test order
   - Check WhatsApp notifications
   - Monitor logs

3. **Monitor Performance**
   - Check delivery rates
   - Monitor errors
   - Optimize if needed

---

**⚡ Deployment selesai dalam 5 menit!**

Sistem WhatsApp siap untuk production! 🚀
