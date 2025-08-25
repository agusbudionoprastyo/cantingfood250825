# 📱 WHATSAPP ENDPOINT TEST GUIDE

## 🎯 **OVERVIEW**

Panduan lengkap untuk testing endpoint WhatsApp dengan order details yang lengkap menggunakan endpoint `https://dev-iptv-wa.appdewa.com/message/send-text`.

---

## 🚀 **ENDPOINT CONFIGURATION**

### **Endpoint Details**
```bash
URL: https://dev-iptv-wa.appdewa.com/message/send-text
Method: POST
Content-Type: application/json
```

### **Request Format**
```json
{
  "session": "mysession",
  "to": "62812345678",
  "text": "Order details message here"
}
```

---

## 🧪 **TESTING METHODS**

### **1. Direct Endpoint Test**

#### **Basic Test**
```bash
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "Hello World Test"
}'
```

#### **Order Details Test**
```bash
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "🛒 NEW ORDER RECEIVED 🛒\n\n📋 Order Details:\n• Order ID: ORD123\n• Order Type: dine-in\n• Order Date: 25/08/2024 14:30\n• Payment Method: cash\n• Payment Status: pending\n• Total Amount: Rp 150.000\n\n👤 Customer Info:\n• Name: John Doe\n• Phone: 08123456789\n• Email: john@example.com\n\n🏪 Branch Info:\n• Branch: Cabang Jakarta Pusat\n• Address: Jl. Sudirman No. 123\n\n🍽️ Order Items:\n1. Nasi Goreng Spesial (2x)\n2. Es Teh Manis (1x)\n\n⏰ Timestamp: 25/08/2024 14:30:45\n🔗 Order Link: https://yourdomain.com/admin/orders/123"
}'
```

### **2. Laravel API Test**

#### **Test Endpoint**
```bash
curl -X POST "https://yourdomain.com/api/admin/whatsapp/test-endpoint" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "phone": "812345678",
  "country_code": "62",
  "message": "Test message from Laravel API"
}'
```

#### **Order Notification Test**
```bash
curl -X POST "https://yourdomain.com/api/admin/whatsapp/send-order-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 1,
  "phone": "812345678",
  "country_code": "62",
  "message": "Custom order notification message"
}'
```

### **3. Using Script**

#### **Make Script Executable**
```bash
chmod +x test-whatsapp-endpoint.sh
```

#### **Run Test**
```bash
./test-whatsapp-endpoint.sh
```

#### **With Laravel Environment**
```bash
export LARAVEL_URL="https://yourdomain.com"
export LARAVEL_TOKEN="your_api_token"
./test-whatsapp-endpoint.sh
```

---

## 📋 **SAMPLE ORDER MESSAGE**

### **Complete Order Notification**
```
🛒 NEW ORDER RECEIVED 🛒

📋 Order Details:
• Order ID: ORD123
• Order Type: dine-in
• Order Date: 25/08/2024 14:30
• Payment Method: cash
• Payment Status: pending
• Total Amount: Rp 150.000

👤 Customer Info:
• Name: John Doe
• Phone: 08123456789
• Email: john@example.com

🏪 Branch Info:
• Branch: Cabang Jakarta Pusat
• Address: Jl. Sudirman No. 123

🍽️ Order Items:
1. Nasi Goreng Spesial
   Qty: 2 x Rp 45.000
   Variation: Pedas Level 2
   Extra: Telur Ceplok
   Subtotal: Rp 90.000

2. Es Teh Manis
   Qty: 1 x Rp 15.000
   Subtotal: Rp 15.000

📍 Delivery Address:
• Address: Jl. Thamrin No. 456, Jakarta
• City: Jakarta Pusat
• Postal Code: 10310

⏰ Timestamp: 25/08/2024 14:30:45
🔗 Order Link: https://yourdomain.com/admin/orders/123
```

### **Short Order Notification**
```
🛒 NEW ORDER: ORD123
💰 Amount: Rp 150.000
👤 Customer: John Doe
📞 Phone: 08123456789
🏪 Branch: Cabang Jakarta Pusat
⏰ 25/08/2024 14:30
🔗 https://yourdomain.com/admin/orders/123
```

---

## 🔧 **CONFIGURATION**

### **1. Gateway Configuration**
```php
// In admin panel > SMS Gateway > WhatsApp
'whatsapp_api_url' => 'https://dev-iptv-wa.appdewa.com'
'whatsapp_session' => 'mysession'
'whatsapp_status' => '1' // Enable
```

### **2. Environment Variables**
```bash
# For testing script
export LARAVEL_URL="https://yourdomain.com"
export LARAVEL_TOKEN="your_api_token"
export WHATSAPP_SESSION="mysession"
export WHATSAPP_PHONE="62812345678"
```

### **3. Test Phone Numbers**
```bash
# Test numbers (replace with real numbers)
PHONE_1="62812345678"
PHONE_2="62887654321"
PHONE_3="62811223344"
```

---

## 📊 **RESPONSE HANDLING**

### **Expected Success Response**
```json
{
  "status": true,
  "message": "Message sent successfully",
  "data": {
    "phone": "62812345678",
    "message": "Order details...",
    "result": {
      "status": true,
      "message": "WhatsApp message sent successfully",
      "response": {
        "success": true,
        "message": "Message delivered"
      }
    }
  }
}
```

### **Expected Error Response**
```json
{
  "status": false,
  "message": "Failed to send WhatsApp message",
  "error": "Invalid phone number format"
}
```

---

## 🚨 **TROUBLESHOOTING**

### **1. Connection Issues**
```bash
# Test connectivity
curl -I https://dev-iptv-wa.appdewa.com

# Test with timeout
curl --connect-timeout 10 --max-time 30 \
  -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
  -H "Content-Type: application/json" \
  -d '{"session": "mysession", "to": "62812345678", "text": "test"}'
```

### **2. Session Issues**
```bash
# Check session status
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "Session test"
}'
```

### **3. Phone Number Issues**
```bash
# Test with different phone formats
# Format 1: 62812345678
# Format 2: +62812345678
# Format 3: 0812345678

curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "0812345678",
  "text": "Phone format test"
}'
```

### **4. Message Length Issues**
```bash
# Test with short message
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "Short test"
}'

# Test with long message
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "Very long message with many characters..."
}'
```

---

## 📈 **MONITORING**

### **1. Check Logs**
```bash
# Laravel logs
tail -f storage/logs/laravel.log | grep -i whatsapp

# WhatsApp specific logs
tail -f storage/logs/laravel.log | grep -E "(WhatsApp|whatsapp)"
```

### **2. Check Database Logs**
```sql
-- Check WhatsApp logs
SELECT * FROM whatsapp_logs ORDER BY created_at DESC LIMIT 10;

-- Check success rate
SELECT 
    status,
    COUNT(*) as count,
    ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER (), 2) as percentage
FROM whatsapp_logs 
GROUP BY status;
```

### **3. Check Gateway Status**
```bash
# Test gateway status
php artisan tinker --execute="
\$gateway = \App\Models\SmsGateway::where('slug', 'whatsapp')->first();
echo 'Gateway: ' . \$gateway->name . ' (Status: ' . \$gateway->status . ')';
"
```

---

## 🎯 **BEST PRACTICES**

### **1. Message Formatting**
- ✅ **Use emojis** - Make it visually appealing
- ✅ **Keep it concise** - WhatsApp has character limits
- ✅ **Include key info** - Order ID, amount, customer
- ✅ **Add call-to-action** - Order link, contact info
- ✅ **Test thoroughly** - Preview before sending

### **2. Error Handling**
- ✅ **Validate phone numbers** - Check format
- ✅ **Handle timeouts** - Set appropriate timeouts
- ✅ **Log all attempts** - Track success/failure
- ✅ **Retry logic** - Implement retry for failed messages
- ✅ **Fallback options** - Alternative notification methods

### **3. Performance**
- ✅ **Async processing** - Don't block order flow
- ✅ **Rate limiting** - Respect API limits
- ✅ **Caching** - Cache gateway status
- ✅ **Monitoring** - Track delivery rates
- ✅ **Cleanup** - Regular log cleanup

---

## ✅ **SUCCESS CRITERIA**

- [ ] Direct endpoint test successful
- [ ] Laravel API test successful
- [ ] Order notification working
- [ ] Error handling implemented
- [ ] Logging system active
- [ ] Response handling correct
- [ ] Phone number validation working
- [ ] Message formatting correct
- [ ] Gateway configuration complete
- [ ] Monitoring system active

---

## 🚀 **QUICK TEST COMMANDS**

### **1. Basic Test**
```bash
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{"session": "mysession", "to": "62812345678", "text": "Test message"}'
```

### **2. Order Test**
```bash
curl -X POST "https://dev-iptv-wa.appdewa.com/message/send-text" \
-H "Content-Type: application/json" \
-d '{
  "session": "mysession",
  "to": "62812345678",
  "text": "🛒 NEW ORDER: ORD123\n💰 Amount: Rp 150.000\n👤 Customer: John Doe\n⏰ 25/08/2024 14:30"
}'
```

### **3. Laravel Test**
```bash
curl -X POST "https://yourdomain.com/api/admin/whatsapp/test-endpoint" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{"phone": "812345678", "country_code": "62", "message": "Test from Laravel"}'
```

---

**🎉 WhatsApp endpoint testing is now ready!**

Endpoint WhatsApp sudah dikonfigurasi dengan benar dan siap untuk testing dengan order details yang lengkap! 🚀
