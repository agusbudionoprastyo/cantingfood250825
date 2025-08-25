# DEPLOYMENT CHECKLIST - CANTING FOOD LARAVEL PROJECT

## 📋 PRE-DEPLOYMENT CHECKLIST

### 1. Environment Configuration
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Set APP_URL=https://yourdomain.com
- [ ] Generate APP_KEY baru
- [ ] Set database credentials production
- [ ] Set cache dan session drivers
- [ ] Set queue connection

### 2. Asset Compilation
- [ ] Run npm run production
- [ ] Compile Vue components
- [ ] Minify CSS/JS
- [ ] Optimize images

### 3. Database
- [ ] Backup database local
- [ ] Export migrations
- [ ] Prepare seeders

### 4. File Permissions
- [ ] Set storage permissions (755)
- [ ] Set bootstrap/cache permissions (755)
- [ ] Set public permissions (755)

## 🚀 DEPLOYMENT STEPS

### Step 1: Upload Files
- [ ] Upload semua file ke server
- [ ] Exclude vendor/, node_modules/, .git/
- [ ] Upload .env production

### Step 2: Server Setup
- [ ] Install Composer dependencies
- [ ] Set file permissions
- [ ] Configure web server (Apache/Nginx)

### Step 3: Database Setup
- [ ] Import database
- [ ] Run migrations
- [ ] Run seeders

### Step 4: Final Configuration
- [ ] Clear caches
- [ ] Optimize autoloader
- [ ] Test application

## 🔧 POST-DEPLOYMENT

### 1. Testing
- [ ] Test homepage
- [ ] Test authentication
- [ ] Test payment gateways
- [ ] Test file uploads
- [ ] Test API endpoints

### 2. Performance
- [ ] Enable OPcache
- [ ] Configure Redis (optional)
- [ ] Set up CDN
- [ ] Enable compression

### 3. Security
- [ ] Set secure headers
- [ ] Configure SSL
- [ ] Set up firewall
- [ ] Monitor logs
