# Fix Autoload Issues on Server 🔧

## Masalah yang Terjadi
```
Class "App\Services\WhatsAppService" not found
```

## Penyebab
- File duplikat dengan nama case yang berbeda
- Autoloader belum di-regenerate
- Cache composer belum di-clear

## Solusi di Server

### Step 1: Regenerate Autoload
```bash
composer dump-autoload
```

### Step 2: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 3: Test dengan Script
```bash
php fix_autoload.php
```

### Step 4: Test Order Service
```bash
php test_order_whatsapp.php
```

## Jika Masih Error

### Cek File Existence
```bash
ls -la app/Services/WhatsApp*
```

### Hapus File Duplikat (jika ada)
```bash
rm app/Services/WhatsAppService.php
```

### Regenerate Autoload Lagi
```bash
composer dump-autoload
```

## Verifikasi Fix

### Test Class Loading
```bash
php artisan tinker
```
```php
$service = new \App\Services\WhatsAppService();
echo "WhatsAppService loaded successfully";
```

### Test Order Service
```bash
php artisan tinker
```
```php
$orderService = new \App\Services\OrderService();
echo "OrderService loaded successfully";
```

## Troubleshooting

### Jika Composer Tidak Ada
```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar dump-autoload
```

### Jika Masih Error
1. Cek file permissions
2. Restart web server
3. Cek PHP version compatibility

---

**Langkah selanjutnya**: Jalankan `composer dump-autoload` di server.
