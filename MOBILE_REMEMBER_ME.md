# Mobile Responsive & Remember Me - Documentation

## ✅ Changes Made

### 1. Mobile Responsive (Products Page)

**Desktop View:**
- Shows full table with all columns
- Image | Product Name | Full Link (input + copy + open buttons) | Date

**Mobile View:**
- Hides "Date" column (`d-none d-md-table-cell`)
- Shows only Copy button for link (no input field visible)
- Compact layout optimized for small screens

**Responsive Classes:**
- `d-none d-md-table-cell` - Only show on medium+ screens (desktop)
- `d-md-none` - Only show on small screens (mobile)

### 2. Remember Me Feature

**Login Page:**
- Added checkbox "Ghi nhớ đăng nhập (30 ngày)"
- Preserves checkbox state with `{{ old('remember') }}`

**Session Handling:**
- Default session: 720 minutes (12 hours)
- With "Remember Me": 43200 minutes (30 days)
- Session refreshes on each visit (Laravel default behavior)

**Implementation:**
```php
// When "Remember Me" is checked:
if ($request->boolean('remember')) {
    config(['session.lifetime' => 43200]); // 30 days
    session()->put('remember_login', true);
}
```

## 📱 Mobile UI Changes

### Products Table (Mobile)

**Before:**
```
| Image | Name | [___________________][Copy][Open] | 12/05/2026 |
```

**After (Mobile):**
```
| Image | Name | [Copy] |
```

Hidden input field exists for clipboard functionality:
```html
<input type="text" value="..." id="link-xxx" 
       style="position: absolute; left: -9999px;">
```

## ⚙️ Configuration

### Session Config (`config/session.php`)

```php
'lifetime' => env('SESSION_LIFETIME', 720), // 12 hours default
```

Set in `.env`:
```env
SESSION_LIFETIME=720  # 12 hours (default)
SESSION_DRIVER=database  # Store sessions in DB
```

### Environment Variables

Add to `.env`:
```env
# Session configuration
SESSION_LIFETIME=720
SESSION_DRIVER=database
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=false  # Set true for HTTPS
```

## 🧪 Testing

### Mobile Responsive
1. Open browser DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Select mobile device (iPhone, Android)
4. Navigate to `/products`
5. Verify:
   - Date column hidden
   - Only Copy button visible
   - Table scrollable horizontally if needed

### Remember Me
1. Go to `/login`
2. Enter credentials
3. Check "Ghi nhớ đăng nhập"
4. Login
5. Close browser completely
6. Reopen browser
7. Visit app URL → Should still be logged in (for 30 days)

### Session Refresh
- Each page visit refreshes session timer
- With remember me: 30 days from last activity
- Without: 12 hours from last activity

## 📝 Files Modified

1. `frontend/resources/views/products/index.blade.php`
   - Added responsive classes
   - Separate desktop/mobile cells
   - Hidden input for mobile copy

2. `frontend/resources/views/auth/login.blade.php`
   - Added remember checkbox
   - Added form-check styling

3. `frontend/app/Http/Controllers/AuthController.php`
   - Handle remember checkbox
   - Set long session lifetime when checked

4. `frontend/config/session.php`
   - Increased default lifetime to 12 hours
   - Added comments about remember me

## 🎯 User Experience

**Mobile Users:**
- ✅ Cleaner interface (no date clutter)
- ✅ Faster copy action (one tap)
- ✅ More space for product info
- ✅ Stay logged in for 30 days

**Desktop Users:**
- ✅ Full functionality preserved
- ✅ See all information
- ✅ Multiple actions available
- ✅ Optional long session

## 🔐 Security Notes

- Session stored in database (more secure than cookies)
- HTTPS recommended for production (set `SESSION_SECURE_COOKIE=true`)
- Session ID regenerated on login (Laravel default)
- Old sessions cleaned up by Laravel scheduler

## 🚀 Deployment

No additional configuration needed. Changes are backward compatible.

For production, ensure:
```env
SESSION_SECURE_COOKIE=true  # HTTPS only
SESSION_HTTP_ONLY=true      # XSS protection
APP_ENV=production
```
