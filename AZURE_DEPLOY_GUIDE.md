# Azure Web App - Laravel Deployment Guide

## 📋 Files Created

1. **`web.config`** (root) - IIS routing config
2. **`public/web.config`** - Public folder routing
3. **`.deployment`** - Azure build config
4. **`deploy.sh`** - Optional deployment script

## 🚀 Deployment Methods

### Method 1: Git Deploy (Recommended)

```bash
# 1. Initialize git (if not already)
cd frontend
git init
git add .
git commit -m "Initial Laravel app"

# 2. Get Azure git URL
az webapp deployment source config-local-git \
  --resource-group YourResourceGroup \
  --name YourWebAppName

# Output: https://YourWebAppName.scm.azurewebsites.net/YourWebAppName.git

# 3. Add remote and push
git remote add azure https://YourWebAppName.scm.azurewebsites.net/YourWebAppName.git
git push azure main

# 4. Wait for deployment (auto-runs composer install)
```

### Method 2: FTP Upload

```bash
# 1. Get FTP credentials
az webapp deployment list-publishing-profiles \
  --resource-group YourResourceGroup \
  --name YourWebAppName

# 2. Upload frontend folder to /site/wwwroot via FTP client (FileZilla)

# 3. SSH to web app and run:
cd /home/site/wwwroot
php artisan config:cache
php artisan route:cache
chmod -R 755 storage bootstrap/cache
```

### Method 3: ZIP Deploy

```bash
# 1. Create deployment package
cd frontend
zip -r ../frontend-deploy.zip . -x "*.git*" "node_modules/*" "tests/*"

# 2. Deploy ZIP
az webapp deployment source config-zip \
  --resource-group YourResourceGroup \
  --name YourWebAppName \
  --src ../frontend-deploy.zip
```

## ⚙️ Azure Web App Configuration

### 1. Set PHP Version
```bash
az webapp config set \
  --resource-group YourResourceGroup \
  --name YourWebAppName \
  --php-version 8.2
```

### 2. Configure Application Settings
```bash
az webapp config appsettings set \
  --resource-group YourResourceGroup \
  --name YourWebAppName \
  --settings \
    APP_ENV=production \
    APP_DEBUG=false \
    POST_BUILD_COMMAND="php artisan config:cache && php artisan route:cache"
```

### 3. Enable Logging
```bash
az webapp log config \
  --resource-group YourResourceGroup \
  --name YourWebAppName \
  --application-logging filesystem \
  --level error
```

## 🔧 Post-Deployment Steps

### 1. Set Environment Variables (Azure Portal)

Go to: **Web App → Configuration → Application Settings**

Add these:
```
APP_NAME=Shopee Aff Manager
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.azurewebsites.net
APP_LOCALE=vi
SESSION_DRIVER=database
SESSION_LIFETIME=720
API_BASE_URL=https://your-backend.azurewebsites.net/api
DB_CONNECTION=sqlite
```

### 2. Generate APP_KEY (if not set)

```bash
# SSH to Azure Web App
az webapp ssh --resource-group YourRG --name YourWebApp

# Generate key
cd /home/site/wwwroot
php artisan key:generate --show

# Copy output and add to Application Settings
```

### 3. Run Migrations (if using database sessions)

```bash
# SSH to web app
az webapp ssh --resource-group YourRG --name YourWebApp

# Run migrations
cd /home/site/wwwroot
php artisan migrate --force
```

### 4. Set Permissions

```bash
# SSH to web app
chmod -R 755 /home/site/wwwroot/storage
chmod -R 755 /home/site/wwwroot/bootstrap/cache
```

## 🧪 Testing

### 1. Check Web App URL
```bash
# Open browser
https://your-app.azurewebsites.net

# Should see Laravel login page
```

### 2. Check Logs
```bash
# Stream logs
az webapp log tail \
  --resource-group YourResourceGroup \
  --name YourWebAppName

# Download logs
az webapp log download \
  --resource-group YourResourceGroup \
  --name YourWebAppName \
  --log-file logs.zip
```

### 3. Test Routes
```bash
# Health check (if you have one)
curl https://your-app.azurewebsites.net/

# Login page
curl https://your-app.azurewebsites.net/login

# Check response headers
curl -I https://your-app.azurewebsites.net/
```

## 🐛 Troubleshooting

### Issue 1: "HTTP 500 Internal Server Error"

**Cause**: Missing `.env` or wrong permissions

**Fix**:
```bash
# SSH to web app
cd /home/site/wwwroot
cp .env.example .env
php artisan key:generate
chmod -R 755 storage bootstrap/cache
```

### Issue 2: "The page isn't working"

**Cause**: Document root not set to `public/`

**Fix**: web.config files already handle this. If still broken:
```bash
# Check web.config exists
ls -la /home/site/wwwroot/web.config
ls -la /home/site/wwwroot/public/web.config

# Restart web app
az webapp restart --resource-group YourRG --name YourWebApp
```

### Issue 3: "Class 'XXX' not found"

**Cause**: Composer autoload not generated

**Fix**:
```bash
# SSH to web app
cd /home/site/wwwroot
composer install --optimize-autoloader --no-dev
php artisan config:cache
```

### Issue 4: Routes not working

**Cause**: URL rewrite not enabled

**Fix**: web.config enables it. If still broken:
```bash
# Clear route cache
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# Recache
php artisan config:cache
php artisan route:cache
```

## 📊 Performance Optimization

### 1. Enable OPcache

Add to Application Settings:
```
PHP_INI_SCAN_DIR=/usr/local/etc/php/conf.d:/home/site/ini
```

Create `ini/opcache.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

### 2. Cache Everything

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Use CDN for Assets

Update `.env`:
```
ASSET_URL=https://yourcdn.azureedge.net
```

## 🔐 Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production`
- [ ] Generated new `APP_KEY`
- [ ] HTTPS enforced (Azure does this automatically)
- [ ] `.env` not in git (check `.gitignore`)
- [ ] Storage folder not publicly accessible (web.config handles this)
- [ ] Session driver set to `database` (not `file`)
- [ ] CORS configured properly

## 📝 Quick Reference

```bash
# Restart web app
az webapp restart --resource-group YourRG --name YourWebApp

# SSH to web app
az webapp ssh --resource-group YourRG --name YourWebApp

# View environment variables
az webapp config appsettings list --resource-group YourRG --name YourWebApp

# Clear all caches
php artisan optimize:clear

# Recache everything
php artisan optimize
```

## 🎯 Complete Deployment Checklist

- [ ] Create `web.config` files (✅ Done)
- [ ] Set PHP version to 8.2+
- [ ] Upload code (Git/FTP/ZIP)
- [ ] Set environment variables
- [ ] Generate `APP_KEY`
- [ ] Run `composer install --no-dev`
- [ ] Set permissions (755 for storage)
- [ ] Run migrations (if database sessions)
- [ ] Cache config/routes/views
- [ ] Test login page
- [ ] Test API connection to backend
- [ ] Enable logging
- [ ] Monitor for errors

---

**Next Steps**: Deploy backend first, then frontend can connect to backend API.
