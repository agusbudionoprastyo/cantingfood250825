#!/bin/bash

echo "📱 Testing WhatsApp Default Phone Configuration..."
echo ""

# Configuration
API_URL="https://dev-iptv-wa.appdewa.com/message/send-text"
SESSION="mysession"
DEFAULT_PHONE="812345678"
COUNTRY_CODE="62"
FULL_PHONE="${COUNTRY_CODE}${DEFAULT_PHONE}"

MESSAGE="🛒 TEST DEFAULT PHONE CONFIGURATION 🛒

📋 Order Details:
• Order ID: TEST123
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
   Subtotal: Rp 90.000

2. Es Teh Manis
   Qty: 1 x Rp 15.000
   Subtotal: Rp 15.000

⏰ Timestamp: 25/08/2024 14:30:45
🔗 Order Link: https://yourdomain.com/admin/orders/123"

echo "📱 Configuration:"
echo "API URL: $API_URL"
echo "Session: $SESSION"
echo "Default Phone: $DEFAULT_PHONE"
echo "Country Code: $COUNTRY_CODE"
echo "Full Phone: $FULL_PHONE"
echo "Message Length: ${#MESSAGE} characters"
echo ""

# Test 1: Direct endpoint with default phone
echo "🔄 Test 1: Direct endpoint with default phone..."
response1=$(curl -s -X POST "$API_URL" \
  -H "Content-Type: application/json" \
  -d "{
    \"session\": \"$SESSION\",
    \"to\": \"$FULL_PHONE\",
    \"text\": \"$MESSAGE\"
  }")

echo "📥 Response 1:"
echo "$response1" | jq '.' 2>/dev/null || echo "$response1"
echo ""

# Test 2: Laravel API without phone (should use default)
if [ -n "$LARAVEL_URL" ]; then
    echo "🔄 Test 2: Laravel API without phone (should use default)..."
    response2=$(curl -s -X POST "$LARAVEL_URL/api/admin/whatsapp/send-order-notification" \
      -H "Content-Type: application/json" \
      -H "Authorization: Bearer $LARAVEL_TOKEN" \
      -d "{
        \"order_id\": 1
      }")
    
    echo "📥 Response 2:"
    echo "$response2" | jq '.' 2>/dev/null || echo "$response2"
    echo ""
fi

# Test 3: Laravel API with custom phone
if [ -n "$LARAVEL_URL" ]; then
    echo "🔄 Test 3: Laravel API with custom phone..."
    response3=$(curl -s -X POST "$LARAVEL_URL/api/admin/whatsapp/send-order-notification" \
      -H "Content-Type: application/json" \
      -H "Authorization: Bearer $LARAVEL_TOKEN" \
      -d "{
        \"order_id\": 1,
        \"phone\": \"87654321\",
        \"country_code\": \"62\"
      }")
    
    echo "📥 Response 3:"
    echo "$response3" | jq '.' 2>/dev/null || echo "$response3"
    echo ""
fi

# Test 4: Check gateway options
echo "🔄 Test 4: Checking gateway options..."
if [ -n "$LARAVEL_URL" ]; then
    gateway_check=$(curl -s -X GET "$LARAVEL_URL/api/admin/sms-gateways" \
      -H "Authorization: Bearer $LARAVEL_TOKEN")
    
    echo "📥 Gateway Options:"
    echo "$gateway_check" | jq '.' 2>/dev/null || echo "$gateway_check"
    echo ""
fi

echo "✅ Default phone configuration test completed!"
echo ""
echo "📊 Expected Results:"
echo "1. Direct endpoint should send to $FULL_PHONE"
echo "2. Laravel API without phone should use default"
echo "3. Laravel API with custom phone should use custom"
echo "4. Gateway options should show default phone settings"
echo ""
echo "📋 Check your WhatsApp for the test messages"
echo "📋 Check logs for detailed information"
