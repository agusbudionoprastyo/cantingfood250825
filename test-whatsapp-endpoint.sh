#!/bin/bash

echo "🧪 Testing WhatsApp Endpoint..."
echo ""

# Configuration
API_URL="https://dev-iptv-wa.appdewa.com/message/send-text"
SESSION="mysession"
PHONE="62812345678"
MESSAGE="🛒 TEST ORDER NOTIFICATION 🛒

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

echo "📱 Testing Direct Endpoint..."
echo "URL: $API_URL"
echo "Session: $SESSION"
echo "Phone: $PHONE"
echo "Message Length: ${#MESSAGE} characters"
echo ""

# Test direct endpoint
echo "🔄 Sending test message..."
response=$(curl -s -X POST "$API_URL" \
  -H "Content-Type: application/json" \
  -d "{
    \"session\": \"$SESSION\",
    \"to\": \"$PHONE\",
    \"text\": \"$MESSAGE\"
  }")

echo "📥 Response:"
echo "$response" | jq '.' 2>/dev/null || echo "$response"
echo ""

# Test Laravel API endpoint (if available)
if [ -n "$LARAVEL_URL" ]; then
    echo "🔄 Testing Laravel API endpoint..."
    laravel_response=$(curl -s -X POST "$LARAVEL_URL/api/admin/whatsapp/test-endpoint" \
      -H "Content-Type: application/json" \
      -H "Authorization: Bearer $LARAVEL_TOKEN" \
      -d "{
        \"phone\": \"812345678\",
        \"country_code\": \"62\",
        \"message\": \"Test message from Laravel API\"
      }")
    
    echo "📥 Laravel Response:"
    echo "$laravel_response" | jq '.' 2>/dev/null || echo "$laravel_response"
    echo ""
fi

echo "✅ Test completed!"
echo ""
echo "📊 Check your WhatsApp for the test message"
echo "📋 Check logs for detailed information"
