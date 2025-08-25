# CONTOH PESAN WHATSAPP ORDER DETAILS

## **📱 FORMAT PESAN WHATSAPP YANG AKAN DIKIRIM**

### **Pesan Otomatis (Setiap Order Baru)**

```
🛒 NEW ORDER RECEIVED 🛒

📋 Order Details:
• Order ID: `ORD123`
• Order Type: delivery
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
• Address: Jl. Sudirman No. 123, Jakarta

🍽️ Order Items:
1. Nasi Goreng Spesial
   Qty: 2 x Rp 45.000
   Variation: Pedas Level 2
   Extra: Telur Ceplok
   Subtotal: Rp 90.000

2. Es Teh Manis
   Qty: 1 x Rp 15.000
   Subtotal: Rp 15.000

3. Ayam Goreng
   Qty: 1 x Rp 45.000
   Subtotal: Rp 45.000

📍 Delivery Address:
• Address: Jl. Thamrin No. 456, Jakarta
• City: Jakarta Pusat
• Postal Code: 10310

💬 Additional Message:
Ada order baru masuk!

⏰ Timestamp: 25/08/2024 14:30:45
🔗 Order Link: https://yourdomain.com/admin/orders/123
```

### **Pesan Manual via API (Dengan Custom Message)**

```bash
curl -X POST "https://yourdomain.com/api/admin/whatsapp/send-order-notification" \
-H "Content-Type: application/json" \
-H "Authorization: Bearer YOUR_TOKEN" \
-d '{
  "order_id": 123,
  "phone": "62812345678",
  "country_code": "62",
  "message": "Order ini urgent, mohon diproses segera!"
}'
```

**Hasil Pesan:**
```
🛒 NEW ORDER RECEIVED 🛒

📋 Order Details:
• Order ID: `ORD123`
• Order Type: delivery
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
• Address: Jl. Sudirman No. 123, Jakarta

🍽️ Order Items:
1. Nasi Goreng Spesial
   Qty: 2 x Rp 45.000
   Variation: Pedas Level 2
   Extra: Telur Ceplok
   Subtotal: Rp 90.000

2. Es Teh Manis
   Qty: 1 x Rp 15.000
   Subtotal: Rp 15.000

3. Ayam Goreng
   Qty: 1 x Rp 45.000
   Subtotal: Rp 45.000

📍 Delivery Address:
• Address: Jl. Thamrin No. 456, Jakarta
• City: Jakarta Pusat
• Postal Code: 10310

💬 Additional Message:
Order ini urgent, mohon diproses segera!

⏰ Timestamp: 25/08/2024 14:30:45
🔗 Order Link: https://yourdomain.com/admin/orders/123
```

## **🔧 FITUR PESAN DETAILS**

### **1. Order Information**
- ✅ Order ID dengan format yang jelas
- ✅ Tipe order (delivery, pickup, dine-in)
- ✅ Tanggal dan waktu order
- ✅ Metode pembayaran
- ✅ Status pembayaran
- ✅ Total amount dengan format rupiah

### **2. Customer Information**
- ✅ Nama customer
- ✅ Nomor telepon
- ✅ Email address

### **3. Branch Information**
- ✅ Nama cabang
- ✅ Alamat cabang

### **4. Order Items**
- ✅ Nama item
- ✅ Quantity dan harga per item
- ✅ Variation (jika ada)
- ✅ Extra/addon (jika ada)
- ✅ Subtotal per item

### **5. Delivery Information**
- ✅ Alamat pengiriman
- ✅ Kota
- ✅ Kode pos

### **6. Additional Features**
- ✅ Custom message (opsional)
- ✅ Timestamp pengiriman
- ✅ Link langsung ke order di admin panel

## **📊 CONTOH DATA ORDER**

### **Order Data Structure**
```php
$order = [
    'id' => 123,
    'order_serial_no' => 'ORD123',
    'order_type' => 'delivery',
    'order_datetime' => '2024-08-25 14:30:00',
    'payment_method' => 'cash',
    'payment_status' => 'pending',
    'total' => 150000,
    'user' => [
        'name' => 'John Doe',
        'phone' => '08123456789',
        'email' => 'john@example.com'
    ],
    'branch' => [
        'name' => 'Cabang Jakarta Pusat',
        'address' => 'Jl. Sudirman No. 123, Jakarta'
    ],
    'orderItems' => [
        [
            'item_name' => 'Nasi Goreng Spesial',
            'quantity' => 2,
            'price' => 45000,
            'variation_name' => 'Pedas Level 2',
            'extra_name' => 'Telur Ceplok',
            'total' => 90000
        ],
        [
            'item_name' => 'Es Teh Manis',
            'quantity' => 1,
            'price' => 15000,
            'total' => 15000
        ]
    ],
    'delivery_address' => [
        'address' => 'Jl. Thamrin No. 456, Jakarta',
        'city' => 'Jakarta Pusat',
        'postal_code' => '10310'
    ]
];
```

## **🚀 KEUNTUNGAN PESAN DETAILS**

### **1. Informasi Lengkap**
- Admin mendapat semua informasi order dalam 1 pesan
- Tidak perlu buka sistem untuk lihat detail
- Bisa langsung action berdasarkan informasi yang ada

### **2. Format yang Rapi**
- Menggunakan emoji untuk visual yang menarik
- Informasi terstruktur dengan baik
- Mudah dibaca di WhatsApp

### **3. Actionable Information**
- Link langsung ke order di admin panel
- Informasi customer untuk follow-up
- Detail item untuk persiapan order

### **4. Customizable**
- Bisa tambah custom message
- Format bisa disesuaikan kebutuhan
- Support multiple language

## **✅ KESIMPULAN**

Dengan implementasi ini, setiap order baru akan otomatis mengirim WhatsApp dengan format yang lengkap dan informatif ke admin dan branch manager. Pesan berisi semua detail order yang diperlukan untuk proses order dengan efisien.
