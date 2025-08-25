# 🚀 DEPLOYMENT CHECKLIST - HOSTING

## 📋 **FILE YANG PERLU DIUPLOAD**

### **1. Migrations (Database)**
- [ ] `database/migrations/2024_01_01_000000_add_whatsapp_to_notification_alerts_table.php`
- [ ] `database/migrations/2024_01_02_000000_create_whatsapp_logs_table.php`
- [ ] `database/migrations/2024_01_03_000000_create_whatsapp_templates_table.php`

### **2. Seeders (Data)**
- [ ] `database/seeders/WhatsappGatewaySeeder.php`
- [ ] `database/seeders/WhatsappTemplateSeeder.php`
- [ ] `database/seeders/WhatsappTestDataSeeder.php`
- [ ] `database/seeders/NotificationAlertTableSeeder.php` (updated)

### **3. Models**
- [ ] `app/Models/WhatsappLog.php`
- [ ] `app/Models/WhatsappTemplate.php`

### **4. Controllers**
- [ ] `app/Http/Controllers/Admin/WhatsappController.php` (updated)
- [ ] `app/Http/Controllers/Admin/WhatsappTemplateController.php`

### **5. Services**
- [ ] `app/Services/WhatsappService.php` (updated)

### **6. Gateway**
- [ ] `app/Http/SmsGateways/Gateways/Whatsapp.php` (updated)

### **7. Routes**
- [ ] `routes/api.php` (updated)

### **8. Request & Resources**
- [ ] `app/Http/Requests/WhatsappTemplateRequest.php`
- [ ] `app/Http/Resources/WhatsappTemplateResource.php`

### **9. Frontend (Vue)**
- [ ] `resources/js/components/admin/whatsapp/WhatsappTemplateManager.vue`

### **10. Scripts & Documentation**
- [ ] `whatsapp-migration-seeder.sh`
- [ ] `test-whatsapp-endpoint.sh`
- [ ] `WHATSAPP-MIGRATION-GUIDE.md`
- [ ] `WHATSAPP-EDITABLE-TEMPLATE-GUIDE.md`
- [ ] `WHATSAPP-ENDPOINT-TEST-GUIDE.md`

## 🚀 **STEP-BY-STEP DEPLOYMENT**

### **STEP 1: Upload Files**
1. Upload semua file di atas ke hosting
2. Pastikan struktur folder tetap sama
3. Exclude: `vendor/`, `node_modules/`, `.git/`

### **STEP 2: Run Migrations**
```bash
# Via cPanel Terminal atau SSH
php artisan migrate

# Atau run specific migrations
php artisan migrate --path=database/migrations/2024_01_01_000000_add_whatsapp_to_notification_alerts_table.php
php artisan migrate --path=database/migrations/2024_01_02_000000_create_whatsapp_logs_table.php
php artisan migrate --path=database/migrations/2024_01_03_000000_create_whatsapp_templates_table.php
```

### **STEP 3: Run Seeders**
```bash
# Run WhatsApp seeders
php artisan db:seed --class=WhatsappGatewaySeeder
php artisan db:seed --class=WhatsappTemplateSeeder
php artisan db:seed --class=NotificationAlertTableSeeder

# Optional: Test data
php artisan db:seed --class=WhatsappTestDataSeeder
```

### **STEP 4: Set Permissions**
```bash
# Set file permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
chmod 644 .env
```

### **STEP 5: Clear & Cache**
```bash
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **STEP 6: Configure Environment**
```bash
# Update .env file
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# WhatsApp Configuration
WHATSAPP_API_URL=https://dev-iptv-wa.appdewa.com
WHATSAPP_SESSION=mysession
```

## 🔧 **HOSTING CONFIGURATION**

### **1. cPanel Configuration**
- [ ] PHP Version: 8.0.2+
- [ ] Extensions: exif, http, json, pdo, mbstring, openssl
- [ ] Memory Limit: 256M+
- [ ] Max Execution Time: 300s
- [ ] Upload Max Filesize: 64M

### **2. Database Configuration**
- [ ] MySQL 5.7+ atau MariaDB 10.2+
- [ ] Create database user dengan privileges
- [ ] Update .env dengan database credentials

### **3. SSL Configuration**
- [ ] Enable SSL certificate
- [ ] Force HTTPS redirect
- [ ] Update APP_URL di .env

## 🧪 **POST-DEPLOYMENT TESTING**

### **1. Test Database Tables**
```bash
php artisan tinker --execute="
echo 'WhatsApp Tables Status:';
echo 'notification_alerts: ' . \App\Models\NotificationAlert::count() . ' records';
echo 'whatsapp_logs: ' . \App\Models\WhatsappLog::count() . ' records';
echo 'whatsapp_templates: ' . \App\Models\WhatsappTemplate::count() . ' records';
echo 'sms_gateways: ' . \App\Models\SmsGateway::where('slug', 'whatsapp')->count() . ' records';
"
```

### **2. Test WhatsApp Gateway**
```bash
php artisan tinker --execute="
\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
if (\$gateway) {
    echo 'WhatsApp Gateway: ' . \$gateway->name . ' (Status: ' . \$gateway->status . ')';
} else {
    echo 'WhatsApp Gateway not found!';
}
"
```

### **3. Test API Endpoints**
```bash
# Test WhatsApp endpoint
curl -X POST "https://yourdomain.com/api/admin/whatsapp/test-endpoint" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "phone": "812345678",
  "country_code": "62",
  "message": "Test message from hosting"
}'
```

### **4. Test Direct Endpoint**
```bash
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "Test from hosting deployment"
}'
```

## 📊 **MONITORING**

### **1. Check Logs**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log | grep -i whatsapp

# Check error logs
tail -f storage/logs/laravel.log | grep -i error
```

### **2. Check Database**
```sql
-- Check WhatsApp logs
SELECT * FROM whatsapp_logs ORDER BY created_at DESC LIMIT 5;

-- Check success rate
SELECT 
    status,
    COUNT(*) as count
FROM whatsapp_logs 
GROUP BY status;
```

### **3. Check Gateway Status**
```bash
php artisan tinker --execute="
\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
echo 'Gateway Status: ' . (\$gateway->status ? 'Active' : 'Inactive');
"
```

## 🚨 **TROUBLESHOOTING**

### **1. Migration Errors**
```bash
# Check migration status
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback --step=1

# Reset if needed
php artisan migrate:reset
php artisan migrate
```

### **2. Permission Errors**
```bash
# Fix permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
```

### **3. Database Connection**
```bash
# Test database connection
php artisan tinker --execute="DB::connection()->getPdo();"

# Check .env configuration
cat .env | grep DB_
```

### **4. Gateway Issues**
```bash
# Check gateway configuration
php artisan tinker --execute="
\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
print_r(\$gateway->gatewayOptions->pluck('value', 'option')->toArray());
"
```

## ✅ **SUCCESS CRITERIA**

- [ ] All files uploaded successfully
- [ ] Migrations run without errors
- [ ] Seeders run without errors
- [ ] Permissions set correctly
- [ ] Caches cleared and optimized
- [ ] Environment configured
- [ ] Database tables created
- [ ] WhatsApp gateway configured
- [ ] API endpoints working
- [ ] Direct endpoint working
- [ ] Logs showing activity
- [ ] System ready for production

## 🎯 **NEXT STEPS**

1. **Configure Admin Panel**
   - Set up WhatsApp gateway settings
   - Configure notification alerts
   - Add admin phone numbers

2. **Test with Real Orders**
   - Create test orders
   - Verify WhatsApp notifications
   - Monitor logs

3. **Monitor Performance**
   - Check delivery rates
   - Monitor error logs
   - Optimize if needed

---

**🎉 Deployment checklist siap untuk hosting!**

Semua file dan konfigurasi sudah siap untuk deployment ke hosting dengan sistem WhatsApp yang lengkap! 🚀
