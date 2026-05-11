# Fix "Default Azure Page" Issue

## Vấn đề
Deployment thành công nhưng vẫn thấy trang default của Azure thay vì Laravel app.

## ✅ Solution 1: Check PHP Version (QUAN TRỌNG NHẤT)

### Azure Portal:
1. **Web App → Configuration → General settings**
2. Scroll xuống phần **Stack settings**
3. Kiểm tra:
   - **Stack**: PHP
   - **PHP version**: 8.2 (hoặc cao hơn)
4. Nếu chưa đúng → Chọn PHP 8.2
5. **Save** → **Restart**

**ĐÂY LÀ NGUYÊN NHÂN PHỔ BIẾN NHẤT!**

---

## ✅ Solution 2: Verify Files on Azure

### Via Kudu Console:
```
https://your-webapp-name.scm.azurewebsites.net
```

1. **Debug Console → CMD**
2. Navigate: `cd D:\home\site\wwwroot`
3. Check files:
   ```cmd
   dir
   ```

**Must see:**
```
✅ index.php          (từ public/)
✅ web.config         (IIS routing)
✅ vendor\            (Composer dependencies)
✅ bootstrap\
✅ storage\
✅ artisan
✅ .user.ini          (PHP settings)
```

**If missing index.php:**
- GitHub Actions failed to flatten structure
- Re-run deployment

**If missing vendor/:**
```cmd
cd D:\home\site\wwwroot
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev
```

---

## ✅ Solution 3: Check web.config Content

Via Kudu → **Debug Console → CMD**:
```cmd
cd D:\home\site\wwwroot
type web.config
```

**Should contain:**
```xml
<defaultDocument>
  <files>
    <clear />
    <add value="index.php" />
  </files>
</defaultDocument>
```

**If wrong or missing:**
```cmd
# Download correct web.config from GitHub
curl -o web.config https://raw.githubusercontent.com/your-username/your-repo/main/.github/workflows/web.config
```

---

## ✅ Solution 4: Set Default Document (Manual)

If web.config doesn't work, set via **Application Settings**:

1. **Web App → Configuration → General settings**
2. Scroll to **Default documents**
3. Ensure `index.php` is at the top
4. If not there → Add it
5. **Save** → **Restart**

---

## ✅ Solution 5: Enable Detailed Errors

To see actual error instead of default page:

### Via Application Settings:
```
WEBSITE_LOAD_USER_PROFILE=1
```

### Via Kudu console:
```cmd
cd D:\home\site\wwwroot

# Check PHP errors
php -v
php index.php
```

If error appears → Fix it

---

## ✅ Solution 6: Check Logs

### Azure Portal:
**Web App → Monitoring → Log stream**

Look for:
- ❌ "PHP Fatal error" → Missing dependencies
- ❌ "Class not found" → Run composer install
- ❌ "No input file specified" → index.php missing
- ❌ "Permission denied" → Set storage permissions

### Or download logs:
```cmd
# Via Kudu
https://your-webapp-name.scm.azurewebsites.net/api/logs/docker
```

---

## ✅ Solution 7: Force Restart App

### Via Portal:
**Web App → Overview → Restart**

### Via Kudu:
```cmd
# Restart site
net stop w3svc
net start w3svc
```

---

## 🔍 Debug Checklist

Run these via **Kudu console**:

```cmd
cd D:\home\site\wwwroot

REM 1. Check PHP version
php -v

REM 2. Check files exist
dir index.php
dir web.config
dir vendor

REM 3. Test index.php directly
php index.php

REM 4. Check permissions
icacls storage
icacls bootstrap\cache

REM 5. Check environment
php artisan config:show
```

---

## 🚀 Quick Fix Script (Run in Kudu)

```cmd
cd D:\home\site\wwwroot

REM Install dependencies if missing
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php composer.phar install --no-dev --optimize-autoloader

REM Set permissions
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap\cache /grant Everyone:(OI)(CI)F /T

REM Generate APP_KEY if missing
php artisan key:generate

REM Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

REM Cache for production
php artisan config:cache
php artisan route:cache
```

---

## 🎯 Most Common Issues & Fixes

| Issue | Fix |
|-------|-----|
| PHP version not set | Set to PHP 8.2 in General settings |
| index.php missing | Re-run GitHub Actions deployment |
| vendor/ missing | Run `composer install` via Kudu |
| web.config wrong | Re-deploy or create manually |
| APP_KEY not set | Set in Application settings |
| Permission errors | Run `icacls` commands above |

---

## ⚙️ Required Azure Configuration

**Web App → Configuration → General settings:**
```
Stack: PHP
PHP version: 8.2
Always On: On (recommended)
```

**Web App → Configuration → Application settings:**
```
APP_KEY=base64:xxx
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-webapp.azurewebsites.net
```

---

## 🧪 Test After Fix

```bash
# Test homepage
curl https://your-webapp-name.azurewebsites.net

# Should return HTML with Laravel content (not Azure default page)

# Test specific route
curl https://your-webapp-name.azurewebsites.net/login

# Should return login page HTML
```

---

## 📞 Next Steps

1. **Check PHP version** (Solution 1) - 90% là lỗi này
2. **Verify files exist** via Kudu (Solution 2)
3. **Check web.config** content (Solution 3)
4. **Run Quick Fix Script** (Solution 7)
5. **Restart Web App**
6. **Test URL again**

Nếu vẫn không work, cho tôi biết:
- Web App name (hoặc URL)
- Output của `dir` command trong Kudu
- Output của `php -v` trong Kudu
- Logs trong Log stream
