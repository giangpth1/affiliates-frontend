# PWA Icons Generation Guide

## Quick Method: Online Tool (Recommended)

1. **Visit:** https://www.pwabuilder.com/imageGenerator
2. **Upload:** Một logo vuông (512x512px recommended, min 192x192px)
3. **Download:** Tất cả sizes (sẽ generate đủ các kích thước)
4. **Copy:** Tất cả files vào thư mục `public/icons/`

## Alternative: RealFaviconGenerator

1. **Visit:** https://realfavicongenerator.net/
2. **Upload logo** → Configure iOS, Android settings
3. **Generate** → Download package
4. **Extract** → Copy icons to `public/icons/`

## Required Sizes

```
icons/
├── icon-16x16.png      (favicon)
├── icon-32x32.png      (favicon)
├── icon-72x72.png      (iOS)
├── icon-96x96.png      (Android)
├── icon-128x128.png    (Android)
├── icon-144x144.png    (iOS)
├── icon-152x152.png    (iOS - primary)
├── icon-192x192.png    (Android - primary, maskable)
├── icon-384x384.png    (Android)
└── icon-512x512.png    (Android - maskable, splash)
```

## Manual Generation (ImageMagick)

If you have a high-res PNG logo:

```bash
# Install ImageMagick first
# macOS: brew install imagemagick
# Windows: choco install imagemagick

# Generate all sizes
convert logo.png -resize 16x16 icon-16x16.png
convert logo.png -resize 32x32 icon-32x32.png
convert logo.png -resize 72x72 icon-72x72.png
convert logo.png -resize 96x96 icon-96x96.png
convert logo.png -resize 128x128 icon-128x128.png
convert logo.png -resize 144x144 icon-144x144.png
convert logo.png -resize 152x152 icon-152x152.png
convert logo.png -resize 192x192 icon-192x192.png
convert logo.png -resize 384x384 icon-384x384.png
convert logo.png -resize 512x512 icon-512x512.png
```

## PowerShell Script (Automated)

Save this as `generate-icons.ps1`:

```powershell
param(
    [string]$SourceImage = "logo.png"
)

$sizes = @(16, 32, 72, 96, 128, 144, 152, 192, 384, 512)

foreach ($size in $sizes) {
    $output = "icon-${size}x${size}.png"
    magick convert "$SourceImage" -resize "${size}x${size}" "$output"
    Write-Host "✅ Generated $output"
}

Write-Host "`n🎉 All icons generated! Move them to public/icons/"
```

Run:
```powershell
.\generate-icons.ps1 -SourceImage "path\to\your\logo.png"
```

## Design Tips

### Logo Requirements
- **Format:** PNG with transparency
- **Size:** Min 512x512px (1024x1024px recommended)
- **Style:** Simple, recognizable at small sizes
- **Colors:** Match your brand (theme-color: #EE4D2D)

### Maskable Icons (icon-192x192.png, icon-512x512.png)
- Add **safe zone** (10% padding from edges)
- Important content in center 80% of canvas
- Works on Android adaptive icons

### iOS Specifics
- **icon-152x152.png:** Main app icon on iOS
- **No transparency:** iOS replaces with black background
- **Rounded corners:** iOS applies automatically

## Testing

After generating icons:

1. **Clear cache:** Hard refresh (Ctrl+Shift+R)
2. **Check manifest:** Open `/manifest.json`
3. **DevTools:** Application → Manifest → Check icons load
4. **iOS Safari:** Share → Add to Home Screen → Check icon appears
5. **Android Chrome:** Menu → Install app → Check icon

## Temporary Placeholder

If you don't have a logo yet, use a placeholder:

```html
<!-- Use emoji as temporary icon -->
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🛒</text></svg>">
```

Or download free icons from:
- https://icons8.com/
- https://www.flaticon.com/
- https://heroicons.com/

---

**Current Status:** Icons folder created at `public/icons/`  
**Next Step:** Generate and upload icons
