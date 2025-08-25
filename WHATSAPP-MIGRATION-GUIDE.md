# 📱 WHATSAPP MIGRATION & SEEDER GUIDE

## 🎯 **OVERVIEW**

Panduan lengkap untuk setup sistem notifikasi WhatsApp di project Canting Food Laravel dengan migration dan seeder yang proper.

---

## 📋 **FILE YANG DIBUAT**

### **1. Migrations**
- `2024_01_01_000000_add_whatsapp_to_notification_alerts_table.php`
- `2024_01_02_000000_create_whatsapp_logs_table.php`
- `2024_01_03_000000_create_whatsapp_templates_table.php`

### **2. Seeders**
- `WhatsappGatewaySeeder.php`
- `WhatsappTemplateSeeder.php`
- `WhatsappTestDataSeeder.php`
- Updated `NotificationAlertTableSeeder.php`

### **3. Models**
- `WhatsappLog.php`
- `WhatsappTemplate.php`

### **4. Scripts**
- `whatsapp-migration-seeder.sh`

---

## 🚀 **STEP-BY-STEP EXECUTION**

### **STEP 1: Backup Database**
```bash
# Backup database sebelum migration
php artisan db:backup
# atau
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
```

### **STEP 2: Run Migrations**
```bash
# Run semua migration WhatsApp
php artisan migrate --path=database/migrations/2024_01_01_000000_add_whatsapp_to_notification_alerts_table.php
php artisan migrate --path=database/migrations/2024_01_02_000000_create_whatsapp_logs_table.php
php artisan migrate --path=database/migrations/2024_01_03_000000_create_whatsapp_templates_table.php

# Atau run semua migration
php artisan migrate
```

### **STEP 3: Run Seeders**
```bash
# Run WhatsApp Gateway Seeder
php artisan db:seed --class=WhatsappGatewaySeeder

# Run WhatsApp Template Seeder
php artisan db:seed --class=WhatsappTemplateSeeder

# Run Updated Notification Alert Seeder
php artisan db:seed --class=NotificationAlertTableSeeder

# Run Test Data Seeder (Optional)
php artisan db:seed --class=WhatsappTestDataSeeder
```

### **STEP 4: Using Script Otomatis**
```bash
# Make script executable
chmod +x whatsapp-migration-seeder.sh

# Run script
./whatsapp-migration-seeder.sh
```

---

## 📊 **DATABASE STRUCTURE**

### **1. notification_alerts Table (Updated)**
```sql
ALTER TABLE notification_alerts 
ADD COLUMN whatsapp BOOLEAN DEFAULT FALSE AFTER push_notification,
ADD COLUMN whatsapp_message TEXT NULL AFTER push_notification_message;
```

### **2. whatsapp_logs Table (New)**
```sql
CREATE TABLE whatsapp_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NULL,
    phone_number VARCHAR(255) NOT NULL,
    country_code VARCHAR(5) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('success', 'failed', 'pending') DEFAULT 'pending',
    response TEXT NULL,
    error_message TEXT NULL,
    gateway_used VARCHAR(255) DEFAULT 'whatsapp',
    metadata JSON NULL,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (order_id) REFERENCES frontend_orders(id) ON DELETE CASCADE,
    INDEX idx_order_status (order_id, status),
    INDEX idx_phone_status (phone_number, status),
    INDEX idx_sent_at (sent_at)
);
```

### **3. whatsapp_templates Table (New)**
```sql
CREATE TABLE whatsapp_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    template_content TEXT NOT NULL,
    variables JSON NULL,
    type ENUM('order_notification', 'custom', 'marketing') DEFAULT 'order_notification',
    is_active BOOLEAN DEFAULT TRUE,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_slug_active (slug, is_active),
    INDEX idx_type (type)
);
```

---

## 🌱 **SEEDER DATA**

### **1. WhatsApp Gateway Data**
```php
// SmsGateway
[
    'name' => 'WhatsApp',
    'slug' => 'whatsapp',
    'status' => 1
]

// GatewayOption
[
    'whatsapp_api_url' => 'https://dev-iptv-wa.appdewa.com',
    'whatsapp_session' => 'mysession',
    'whatsapp_status' => 1
]
```

### **2. WhatsApp Templates Data**
```php
// Order Notification Template
[
    'name' => 'Order Notification Template',
    'slug' => 'order_notification',
    'template_content' => "🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: {order_id}\n• Order Type: {order_type}\n• Order Date: {order_date}\n• Payment Method: {payment_method}\n• Payment Status: {payment_status}\n• Total Amount: {total_amount}\n\n👤 Customer Info:\n• Name: {customer_name}\n• Phone: {customer_phone}\n• Email: {customer_email}\n\n🏪 Branch Info:\n• Branch: {branch_name}\n• Address: {branch_address}\n\n🍽️ Order Items:\n{order_items}\n\n📍 Delivery Address:\n{delivery_address}\n\n⏰ Timestamp: {timestamp}\n🔗 Order Link: {order_link}",
    'variables' => ['order_id', 'order_type', 'order_date', 'payment_method', 'payment_status', 'total_amount', 'customer_name', 'customer_phone', 'customer_email', 'branch_name', 'branch_address', 'order_items', 'delivery_address', 'timestamp', 'order_link'],
    'type' => 'order_notification',
    'is_active' => true
]
```

### **3. Notification Alert Data**
```php
// Updated notification_alerts
[
    'name' => 'Admin And Branch Manager New Order Message',
    'language' => 'admin_and_branch_manager_new_order_message',
    'whatsapp_message' => "🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: {order_id}\n• Order Type: {order_type}\n• Order Date: {order_date}\n• Payment Method: {payment_method}\n• Payment Status: {payment_status}\n• Total Amount: {total_amount}\n\n👤 Customer Info:\n• Name: {customer_name}\n• Phone: {customer_phone}\n• Email: {customer_email}\n\n🏪 Branch Info:\n• Branch: {branch_name}\n• Address: {branch_address}\n\n🍽️ Order Items:\n{order_items}\n\n📍 Delivery Address:\n{delivery_address}\n\n⏰ Timestamp: {timestamp}\n🔗 Order Link: {order_link}",
    'whatsapp' => 1
]
```

---

## 🔧 **VERIFICATION STEPS**

### **1. Check Database Tables**
```bash
php artisan tinker --execute="
echo '=== WhatsApp Tables Status ===';
echo 'notification_alerts: ' . \App\Models\NotificationAlert::count() . ' records';
echo 'whatsapp_logs: ' . \App\Models\WhatsappLog::count() . ' records';
echo 'whatsapp_templates: ' . \App\Models\WhatsappTemplate::count() . ' records';
echo 'sms_gateways (whatsapp): ' . \App\Models\SmsGateway::where('slug', 'whatsapp')->count() . ' records';
"
```

### **2. Check WhatsApp Gateway**
```bash
php artisan tinker --execute="
\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
if (\$gateway) {
    echo 'WhatsApp Gateway: ' . \$gateway->name . ' (Status: ' . \$gateway->status . ')';
    \$options = \$gateway->gatewayOptions;
    foreach (\$options as \$option) {
        echo \$option->option . ': ' . \$option->value;
    }
} else {
    echo 'WhatsApp Gateway not found!';
}
"
```

### **3. Check Templates**
```bash
php artisan tinker --execute="
\$templates = \App\Models\WhatsappTemplate::active()->get();
echo 'Active Templates: ' . \$templates->count();
foreach (\$templates as \$template) {
    echo \$template->name . ' (' . \$template->slug . ')';
}
"
```

### **4. Check Notification Alerts**
```bash
php artisan tinker --execute="
\$alerts = \App\Models\NotificationAlert::where('whatsapp', 1)->get();
echo 'WhatsApp Enabled Alerts: ' . \$alerts->count();
foreach (\$alerts as \$alert) {
    echo \$alert->name . ' - WhatsApp: ' . \$alert->whatsapp;
}
"
```

---

## 🧪 **TESTING**

### **1. Test Template Replacement**
```bash
php artisan tinker --execute="
\$template = \App\Models\WhatsappTemplate::where('slug', 'order_notification')->first();
\$data = [
    'order_id' => 'ORD123',
    'order_type' => 'dine-in',
    'order_date' => '25/08/2024 14:30',
    'payment_method' => 'cash',
    'payment_status' => 'pending',
    'total_amount' => 'Rp 150.000',
    'customer_name' => 'John Doe',
    'customer_phone' => '08123456789',
    'customer_email' => 'john@example.com',
    'branch_name' => 'Cabang Jakarta Pusat',
    'branch_address' => 'Jl. Sudirman No. 123',
    'order_items' => '1. Nasi Goreng Spesial (2x)\n2. Es Teh Manis (1x)',
    'delivery_address' => 'Jl. Thamrin No. 456, Jakarta',
    'timestamp' => '25/08/2024 14:30:45',
    'order_link' => 'https://yourdomain.com/admin/orders/123'
];
echo \$template->replaceVariables(\$data);
"
```

### **2. Test WhatsApp Service**
```bash
php artisan tinker --execute="
\$service = new \App\Services\WhatsappService(1);
echo 'WhatsApp Service initialized for Order ID: 1';
"
```

### **3. Test Log Creation**
```bash
php artisan tinker --execute="
\$log = \App\Models\WhatsappLog::create([
    'order_id' => 1,
    'phone_number' => '08123456789',
    'country_code' => '62',
    'message' => 'Test WhatsApp message',
    'status' => 'success',
    'response' => '{"status": true}',
    'gateway_used' => 'whatsapp'
]);
echo 'WhatsApp Log created with ID: ' . \$log->id;
"
```

---

## 🚨 **TROUBLESHOOTING**

### **1. Migration Errors**
```bash
# Check migration status
php artisan migrate:status

# Rollback specific migration
php artisan migrate:rollback --step=1

# Reset all migrations
php artisan migrate:reset
php artisan migrate
```

### **2. Seeder Errors**
```bash
# Check seeder status
php artisan db:seed --class=WhatsappGatewaySeeder --verbose

# Clear cache if needed
php artisan config:clear
php artisan cache:clear
```

### **3. Database Connection Issues**
```bash
# Test database connection
php artisan tinker --execute="DB::connection()->getPdo();"

# Check .env configuration
cat .env | grep DB_
```

---

## 📊 **MONITORING**

### **1. Check WhatsApp Logs**
```bash
php artisan tinker --execute="
echo '=== WhatsApp Logs Summary ===';
echo 'Total: ' . \App\Models\WhatsappLog::count();
echo 'Success: ' . \App\Models\WhatsappLog::success()->count();
echo 'Failed: ' . \App\Models\WhatsappLog::failed()->count();
echo 'Pending: ' . \App\Models\WhatsappLog::pending()->count();
echo 'Today: ' . \App\Models\WhatsappLog::today()->count();
"
```

### **2. Check Recent Logs**
```bash
php artisan tinker --execute="
\$logs = \App\Models\WhatsappLog::latest()->take(5)->get();
foreach (\$logs as \$log) {
    echo 'ID: ' . \$log->id . ' | Phone: ' . \$log->phone_number . ' | Status: ' . \$log->status . ' | Date: ' . \$log->created_at;
}
"
```

---

## 🎯 **NEXT STEPS**

### **1. Configuration**
- Configure WhatsApp Gateway in admin panel
- Set up notification alerts
- Test gateway connection

### **2. Integration**
- Test with real orders
- Monitor logs
- Optimize performance

### **3. Maintenance**
- Regular log cleanup
- Monitor success rates
- Update templates as needed

---

## ✅ **SUCCESS CRITERIA**

- [ ] All migrations run successfully
- [ ] All seeders run without errors
- [ ] WhatsApp Gateway configured
- [ ] Templates created and active
- [ ] Notification alerts updated
- [ ] Test data verified
- [ ] System ready for production

---

**🎉 WhatsApp notification system is now ready for deployment!**
