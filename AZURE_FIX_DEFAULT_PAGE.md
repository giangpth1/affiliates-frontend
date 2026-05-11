# 🔧 Azure Web App - Fix "Default Page" Issue

## Nguyên nhân
Azure Web App không tìm thấy index.php hoặc chưa chạy Laravel app đúng cách.

## ✅ Solution 1: Set Virtual Application Path (KHUYẾN NGHỊ)

### Azure Portal:
1. **Web App → Configuration → Path mappings**
2. Click **+ New virtual application or directory**
3. Set:
   - Virtual path: `/`
   - Physical path: `site\wwwroot\public`
   - Type: `Application`
4. **Save**
5. **Restart Web App**

### Azure CLI:
```bash
az webapp config set \
  --resource-group YourResourceGroup \
  --name YourWebAppName \
  --virtual-applications '[{"virtualPath":"/","physicalPath":"site\\wwwroot\\public","preloadEnabled":true}]'

az webapp restart \
  --resource-group YourResourceGroup \
  --name YourWebAppName
```

---

## ✅ Solution 2: Check Deployment Status

### Via Kudu Console:
```
https://your-webapp-name.scm.azurewebsites.net
```

1. Click **Debug console** → **CMD**
2. Navigate: `cd D:\home\site\wwwroot`
3. Check files exist:
   ```cmd
   dir
   dir public
   ```
4. Should see:
   - ✅ `artisan` file
   - ✅ `public/` folder
   - ✅ `public/index.php`
   - ✅ `vendor/` folder
   - ✅ `web.config`

### If files missing:
- Re-deploy: Git push / FTP upload / ZIP deploy

---

## ✅ Solution 3: Install Composer Dependencies

### Via Kudu Console (CMD):
```bash
cd D:\home\site\wwwroot
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader
```

### Or via SSH (if Linux Web App):
```bash
cd /home/site/wwwroot
composer install --no-dev --optimize-autoloader
```

---

## ✅ Solution 4: Set Environment Variables

### Via Azure Portal:
**Web App → Configuration → Application settings → + New**

**CRITICAL (Bắt buộc):**
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
```

**Generate APP_KEY** (via Kudu console):
```bash
cd D:\home\site\wwwroot
php artisan key:generate --show
```

Copy output và add vào Application Settings.

---

## ✅ Solution 5: Enable PHP & Set Version

### Azure Portal:
1. **Configuration → General settings**
2. Set:
   - **Stack**: PHP
   - **PHP version**: 8.2 (hoặc cao hơn)
3. **Save** → **Restart**

### Azure CLI:
```bash
az webapp config set \
  --resource-group YourResourceGroup \
  --name YourWebAppName \
  --php-version 8.2
```

---

## ✅ Solution 6: Check Logs

### Stream logs:
```bash
az webapp log tail \
  --resource-group YourResourceGroup \
  --name YourWebAppName
```

### Or via Kudu:
```
https://your-webapp-name.scm.azurewebsites.net/api/logs/docker
```

Look for errors like:
- ❌ "Class not found" → Run `composer install`
- ❌ "No application encryption key" → Set `APP_KEY`
- ❌ "Permission denied" → Set storage permissions

---

## ✅ Solution 7: Set Storage Permissions

### Via Kudu Console:
```bash
cd D:\home\site\wwwroot
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap\cache /grant Everyone:(OI)(CI)F /T
```

### Or via SSH (Linux):
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 🧪 Test Steps

### 1. Check file structure via Kudu:
```
D:\home\site\wwwroot\
├── artisan
├── public/
│   └── index.php
├── vendor/ (should exist after composer install)
├── web.config
└── ...
```

### 2. Test direct access to index.php:
```
https://your-app.azurewebsites.net/public/index.php
```

If this works → Virtual path problem → Use Solution 1

### 3. Check environment:
```bash
# Via Kudu console
php -v
composer --version
```

---

## 🎯 Complete Checklist

- [ ] Files uploaded to `/site/wwwroot`
- [ ] `public/index.php` exists
- [ ] `vendor/` folder exists (run `composer install`)
- [ ] `web.config` exists at root
- [ ] Virtual application path set to `site\wwwroot\public`
- [ ] PHP version 8.2+
- [ ] Environment variables set (APP_KEY, APP_ENV, APP_DEBUG)
- [ ] Storage permissions set
- [ ] Web app restarted

---

## 🚨 Emergency Fix (Nuclear Option)

If nothing works, try this:

### 1. Clear everything and redeploy:
```bash
# Via Kudu console
cd D:\home\site\wwwroot
rmdir /s /q *
```

### 2. Re-upload via ZIP:
```bash
# Local
cd frontend
zip -r deploy.zip . -x "*.git*" "node_modules/*" "tests/*"

# Deploy
az webapp deployment source config-zip \
  --resource-group YourRG \
  --name YourWebApp \
  --src deploy.zip
```

### 3. Set virtual path (CRITICAL):
```bash
az webapp config set \
  --resource-group YourRG \
  --name YourWebApp \
  --virtual-applications '[{"virtualPath":"/","physicalPath":"site\\wwwroot\\public","preloadEnabled":true}]'
```

### 4. Install dependencies via Kudu:
```bash
cd D:\home\site\wwwroot
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev
php artisan key:generate
php artisan config:cache
```

### 5. Restart:
```bash
az webapp restart --resource-group YourRG --name YourWebApp
```

---

## 📞 Next Steps

**Bạn có thể:**
1. Vào Kudu console kiểm tra files
2. Set virtual application path (Solution 1) - **ĐÂY LÀ VẤN ĐỀ PHỔ BIẾN NHẤT**
3. Run composer install
4. Check logs để biết lỗi cụ thể

**Hãy cho tôi biết:**
- Web app name là gì? (để tôi tạo lệnh cụ thể)
- Resource group name?
- Bạn đã set virtual application path chưa?
- Logs có lỗi gì không?
