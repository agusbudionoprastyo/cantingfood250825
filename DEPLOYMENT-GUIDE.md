# 🚀 DEPLOYMENT GUIDE - CANTING FOOD LARAVEL PROJECT

## 📋 **REQUIREMENTS SERVER**

### **PHP Requirements**
- PHP 8.0.2 atau lebih tinggi
- Extensions: exif, http, json, pdo, mbstring, openssl, tokenizer, xml, ctype, fileinfo, bcmath

### **Server Requirements**
- Apache 2.4+ atau Nginx 1.18+
- MySQL 5.7+ atau MariaDB 10.2+
- Composer 2.0+
- Node.js 16+ (untuk asset compilation)

### **Recommended Hosting**
- **Shared Hosting**: Hostinger, SiteGround, A2Hosting
- **VPS**: DigitalOcean, Linode, Vultr
- **Cloud**: AWS, Google Cloud, Azure

## 🚀 **STEP-BY-STEP DEPLOYMENT**

### **STEP 1: Persiapan Local Environment**

```bash
# Compile assets production
npm run production

# Test aplikasi local
php artisan serve
```

### **STEP 2: Upload Files ke Server**

#### **Via cPanel File Manager:**
1. Buka cPanel → File Manager
2. Navigasi ke `public_html` atau folder domain Anda
3. Upload semua file project (exclude: `vendor/`, `node_modules/`, `.git/`)

#### **Via FTP/SFTP:**
```bash
# Exclude folders yang tidak perlu
rsync -av --exclude='vendor/' --exclude='node_modules/' --exclude='.git/' ./ user@server:/path/to/domain/
```

#### **Via Git (Recommended):**
```bash
# Di server
cd /path/to/domain
git clone https://github.com/yourusername/cantingfood250825.git .
git checkout main
```

### **STEP 3: Server Configuration**

#### **A. Install Dependencies**
```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies (jika perlu recompile)
npm install
npm run production
```

#### **B. Set File Permissions**
```bash
# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
chmod 644 .env
```

#### **C. Configure .env File**
1. Copy `env-production.txt` ke `.env`
2. Update konfigurasi:
   - `APP_URL`: URL domain Anda
   - `DB_*`: Database credentials
   - `MAIL_*`: Email configuration
   - Payment gateway keys

### **STEP 4: Database Setup**

#### **A. Create Database**
```sql
CREATE DATABASE canting_food;
CREATE USER 'canting_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON canting_food.* TO 'canting_user'@'localhost';
FLUSH PRIVILEGES;
```

#### **B. Import & Migrate**
```bash
# Import database (jika ada backup)
mysql -u username -p canting_food < backup.sql

# Run migrations
php artisan migrate --force

# Run seeders
php artisan db:seed --force
```

### **STEP 5: Final Configuration**

#### **A. Generate App Key**
```bash
php artisan key:generate
```

#### **B. Clear & Cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### **C. Optimize**
```bash
composer dump-autoload --optimize
```

## 🔧 **SERVER CONFIGURATION**

### **Apache Configuration (.htaccess)**
- Copy `htaccess-production.txt` ke `.htaccess`
- Pastikan mod_rewrite enabled
- Enable mod_deflate dan mod_expires

### **Nginx Configuration**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/domain/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### **PHP Configuration**
```ini
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
max_input_vars = 3000
memory_limit = 256M
```

## 🧪 **TESTING POST-DEPLOYMENT**

### **1. Basic Functionality**
- [ ] Homepage loads
- [ ] User registration/login
- [ ] Database connections
- [ ] File uploads

### **2. Payment Gateways**
- [ ] Stripe integration
- [ ] Razorpay integration
- [ ] PayPal integration
- [ ] Bkash integration

### **3. Performance**
- [ ] Page load speed
- [ ] Image optimization
- [ ] CSS/JS minification
- [ ] Database queries

## 🚨 **TROUBLESHOOTING**

### **Common Issues**

#### **1. 500 Internal Server Error**
```bash
# Check error logs
tail -f storage/logs/laravel.log

# Check permissions
ls -la storage/
ls -la bootstrap/cache/
```

#### **2. Database Connection Error**
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

#### **3. Asset Loading Issues**
```bash
# Recompile assets
npm run production

# Check file permissions
chmod -R 755 public/
```

#### **4. Route Not Found**
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache

# Check .htaccess
cat .htaccess
```

## 📊 **PERFORMANCE OPTIMIZATION**

### **1. Enable OPcache**
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
```

### **2. Redis Cache (Optional)**
```bash
# Install Redis
sudo apt install redis-server

# Configure Laravel
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### **3. CDN Setup**
- Upload images ke CDN
- Configure asset URLs
- Enable compression

## 🔒 **SECURITY CHECKLIST**

- [ ] HTTPS enabled
- [ ] File permissions set correctly
- [ ] Environment variables secured
- [ ] Database credentials strong
- [ ] Firewall configured
- [ ] Regular backups scheduled
- [ ] Error reporting disabled
- [ ] Debug mode disabled

## 📞 **SUPPORT**

Jika mengalami masalah:
1. Check error logs di `storage/logs/`
2. Verify server requirements
3. Test step-by-step deployment
4. Contact hosting provider support

---

**🎯 TARGET: Deployment selesai dalam 30-60 menit dengan aplikasi berjalan optimal!**
