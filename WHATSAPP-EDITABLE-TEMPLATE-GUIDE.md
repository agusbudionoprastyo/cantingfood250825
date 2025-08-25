# 📱 WHATSAPP EDITABLE TEMPLATE SYSTEM

## 🎯 **OVERVIEW**

Sistem template WhatsApp yang **100% editable** melalui admin panel dengan fitur lengkap untuk mengelola, preview, dan customisasi pesan WhatsApp.

---

## 🚀 **FITUR UTAMA**

### **1. Template Management**
- ✅ **Create** - Buat template baru
- ✅ **Edit** - Edit template existing
- ✅ **Delete** - Hapus template
- ✅ **Toggle Status** - Aktif/nonaktif template
- ✅ **Preview** - Preview dengan sample data
- ✅ **Search & Filter** - Cari dan filter template

### **2. Template Types**
- ✅ **Order Notification** - Notifikasi order baru
- ✅ **Custom Template** - Template kustom
- ✅ **Marketing Template** - Template promosi

### **3. Variable System**
- ✅ **Dynamic Variables** - {order_id}, {customer_name}, dll
- ✅ **Variable Validation** - Validasi variable yang digunakan
- ✅ **Sample Data** - Data sample untuk preview
- ✅ **Auto Replacement** - Otomatis replace variable

---

## 📋 **API ENDPOINTS**

### **Template Management**
```bash
# Get all templates
GET /api/admin/whatsapp-templates

# Get template by ID
GET /api/admin/whatsapp-templates/{id}

# Create new template
POST /api/admin/whatsapp-templates

# Update template
PUT /api/admin/whatsapp-templates/{id}

# Delete template
DELETE /api/admin/whatsapp-templates/{id}

# Toggle template status
POST /api/admin/whatsapp-templates/{id}/toggle-status

# Preview template
POST /api/admin/whatsapp-templates/{id}/preview

# Get template variables
GET /api/admin/whatsapp-templates/{id}/variables

# Get templates by type
GET /api/admin/whatsapp-templates/type/{type}
```

### **Request/Response Examples**

#### **Create Template**
```bash
POST /api/admin/whatsapp-templates
Content-Type: application/json

{
  "name": "Order Notification Template",
  "type": "order_notification",
  "description": "Template untuk notifikasi order baru",
  "template_content": "🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: {order_id}\n• Order Type: {order_type}\n• Total Amount: {total_amount}\n\n👤 Customer: {customer_name}\n📞 Phone: {customer_phone}\n\n⏰ {timestamp}\n🔗 {order_link}",
  "variables": ["order_id", "order_type", "total_amount", "customer_name", "customer_phone", "timestamp", "order_link"],
  "is_active": true
}
```

#### **Preview Template**
```bash
POST /api/admin/whatsapp-templates/1/preview
Content-Type: application/json

{
  "variables": {
    "order_id": "ORD123",
    "order_type": "dine-in",
    "total_amount": "Rp 150.000",
    "customer_name": "John Doe",
    "customer_phone": "08123456789",
    "timestamp": "25/08/2024 14:30:45",
    "order_link": "https://yourdomain.com/admin/orders/123"
  }
}
```

---

## 🎨 **VUE COMPONENT**

### **Features**
- ✅ **Responsive Design** - Mobile-friendly
- ✅ **Real-time Search** - Instant search
- ✅ **Modal Forms** - Clean UI
- ✅ **Preview System** - Live preview
- ✅ **Variable Helper** - Available variables list
- ✅ **Status Toggle** - Quick enable/disable
- ✅ **Usage Statistics** - Track template usage

### **Component Structure**
```vue
<template>
  <!-- Template List -->
  <div class="whatsapp-template-manager">
    <!-- Header with Add Button -->
    <!-- Filter Controls -->
    <!-- Template Table -->
    <!-- Create/Edit Modal -->
    <!-- Preview Modal -->
  </div>
</template>
```

---

## 🔧 **VARIABLE SYSTEM**

### **Order Notification Variables**
```php
[
    'order_id',           // Order serial number
    'order_type',         // dine-in, delivery, takeaway
    'order_date',         // Formatted date
    'payment_method',     // cash, online, etc
    'payment_status',     // pending, paid, failed
    'total_amount',       // Formatted amount
    'customer_name',      // Customer name
    'customer_phone',     // Customer phone
    'customer_email',     // Customer email
    'branch_name',        // Branch name
    'branch_address',     // Branch address
    'order_items',        // Formatted order items
    'delivery_address',   // Delivery address (if any)
    'timestamp',          // Current timestamp
    'order_link'          // Admin order link
]
```

### **Custom Template Variables**
```php
[
    'custom_message',     // Custom message
    'timestamp'           // Current timestamp
]
```

### **Marketing Template Variables**
```php
[
    'promo_title',        // Promotion title
    'promo_description',  // Promotion description
    'discount_amount',    // Discount amount
    'valid_until',        // Valid until date
    'promo_code',         // Promotion code
    'website_url'         // Website URL
]
```

---

## 📊 **DATABASE STRUCTURE**

### **whatsapp_templates Table**
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

### **whatsapp_logs Table (Enhanced)**
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
    metadata JSON NULL,  -- Contains template_used, recipient_type
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (order_id) REFERENCES frontend_orders(id) ON DELETE CASCADE,
    INDEX idx_order_status (order_id, status),
    INDEX idx_phone_status (phone_number, status),
    INDEX idx_sent_at (sent_at)
);
```

---

## 🎯 **USAGE EXAMPLES**

### **1. Create Order Notification Template**
```php
$template = WhatsappTemplate::create([
    'name' => 'Order Notification Template',
    'slug' => 'order_notification',
    'template_content' => "🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: {order_id}\n• Order Type: {order_type}\n• Total Amount: {total_amount}\n\n👤 Customer: {customer_name}\n📞 Phone: {customer_phone}\n\n⏰ {timestamp}\n🔗 {order_link}",
    'variables' => ['order_id', 'order_type', 'total_amount', 'customer_name', 'customer_phone', 'timestamp', 'order_link'],
    'type' => 'order_notification',
    'is_active' => true,
    'description' => 'Template untuk notifikasi order baru'
]);
```

### **2. Use Template in Service**
```php
$template = WhatsappTemplate::where('type', 'order_notification')
    ->where('is_active', true)
    ->first();

if ($template) {
    $data = [
        'order_id' => 'ORD123',
        'order_type' => 'dine-in',
        'total_amount' => 'Rp 150.000',
        'customer_name' => 'John Doe',
        'customer_phone' => '08123456789',
        'timestamp' => '25/08/2024 14:30:45',
        'order_link' => 'https://yourdomain.com/admin/orders/123'
    ];
    
    $message = $template->replaceVariables($data);
    // Send WhatsApp message
}
```

### **3. Preview Template**
```php
$template = WhatsappTemplate::find(1);
$sampleData = [
    'order_id' => 'ORD123',
    'order_type' => 'dine-in',
    'total_amount' => 'Rp 150.000',
    'customer_name' => 'John Doe',
    'customer_phone' => '08123456789',
    'timestamp' => '25/08/2024 14:30:45',
    'order_link' => 'https://yourdomain.com/admin/orders/123'
];

$preview = $template->replaceVariables($sampleData);
echo $preview;
```

---

## 🔍 **ADMIN PANEL INTEGRATION**

### **1. Add to Menu**
```php
// Add to admin menu
[
    'name' => 'WhatsApp Templates',
    'route' => 'admin.whatsapp-templates',
    'icon' => 'fab fa-whatsapp',
    'permission' => 'whatsapp-template-view'
]
```

### **2. Add Routes**
```php
// Add to web routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('whatsapp-templates', WhatsappTemplateController::class);
    Route::post('whatsapp-templates/{id}/toggle-status', [WhatsappTemplateController::class, 'toggleStatus']);
    Route::post('whatsapp-templates/{id}/preview', [WhatsappTemplateController::class, 'preview']);
});
```

### **3. Add Permissions**
```php
// Add permissions
[
    'whatsapp-template-view',
    'whatsapp-template-create',
    'whatsapp-template-edit',
    'whatsapp-template-delete'
]
```

---

## 🧪 **TESTING**

### **1. Test Template Creation**
```bash
# Test API
curl -X POST "https://yourdomain.com/api/admin/whatsapp-templates" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "name": "Test Template",
  "type": "order_notification",
  "template_content": "Test message: {order_id}",
  "variables": ["order_id"],
  "is_active": true
}'
```

### **2. Test Template Preview**
```bash
# Test preview
curl -X POST "https://yourdomain.com/api/admin/whatsapp-templates/1/preview" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "variables": {
    "order_id": "TEST123"
  }
}'
```

### **3. Test Template Usage**
```bash
# Test in service
php artisan tinker
$template = \App\Models\WhatsappTemplate::first();
$data = ['order_id' => 'TEST123'];
echo $template->replaceVariables($data);
```

---

## 📈 **MONITORING & ANALYTICS**

### **1. Template Usage Statistics**
```php
// Get usage count
$usageCount = WhatsappLog::where('metadata->template_used', $template->slug)->count();

// Get last used date
$lastUsed = WhatsappLog::where('metadata->template_used', $template->slug)
    ->orderBy('created_at', 'desc')
    ->first();
```

### **2. Success Rate Tracking**
```php
// Get success rate
$totalSent = WhatsappLog::where('metadata->template_used', $template->slug)->count();
$successCount = WhatsappLog::where('metadata->template_used', $template->slug)
    ->where('status', 'success')
    ->count();
$successRate = ($totalSent > 0) ? ($successCount / $totalSent) * 100 : 0;
```

---

## 🚨 **TROUBLESHOOTING**

### **1. Template Not Working**
```bash
# Check if template is active
php artisan tinker
$template = \App\Models\WhatsappTemplate::where('slug', 'order_notification')->first();
echo $template->is_active ? 'Active' : 'Inactive';

# Check variables
print_r($template->variables_list);
```

### **2. Variable Not Replaced**
```bash
# Check variable format
# Should be: {variable_name}
# Not: {variable_name} or {variable_name}

# Test replacement
$data = ['order_id' => 'TEST123'];
$result = $template->replaceVariables($data);
echo $result;
```

### **3. Preview Not Working**
```bash
# Check API endpoint
curl -X POST "https://yourdomain.com/api/admin/whatsapp-templates/1/preview" \
-H "Content-Type: application/json" \
-d '{"variables": {"order_id": "TEST123"}}'

# Check response
echo $response;
```

---

## 🎯 **BEST PRACTICES**

### **1. Template Design**
- ✅ **Keep it concise** - WhatsApp has character limits
- ✅ **Use emojis** - Make it visually appealing
- ✅ **Include key info** - Order ID, amount, customer
- ✅ **Add call-to-action** - Order link, contact info
- ✅ **Test thoroughly** - Preview before using

### **2. Variable Usage**
- ✅ **Use descriptive names** - {order_id} not {id}
- ✅ **Validate variables** - Check if required variables exist
- ✅ **Provide defaults** - Handle missing data gracefully
- ✅ **Document variables** - Keep list of available variables

### **3. Performance**
- ✅ **Cache templates** - Cache frequently used templates
- ✅ **Optimize queries** - Use proper indexes
- ✅ **Monitor usage** - Track template performance
- ✅ **Clean up logs** - Regular log cleanup

---

## ✅ **SUCCESS CRITERIA**

- [ ] Template CRUD operations working
- [ ] Variable replacement working
- [ ] Preview system functional
- [ ] Admin panel integration complete
- [ ] API endpoints tested
- [ ] Vue component responsive
- [ ] Error handling implemented
- [ ] Logging system active
- [ ] Performance optimized
- [ ] Documentation complete

---

**🎉 WhatsApp editable template system is now ready for production!**

Template WhatsApp sekarang **100% editable** melalui admin panel dengan fitur lengkap untuk mengelola, preview, dan customisasi pesan sesuai kebutuhan bisnis Anda! 🚀
