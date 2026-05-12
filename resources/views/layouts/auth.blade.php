<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Đăng nhập') - Shopee Affiliate Manager</title>
    
    <!-- PWA Meta Tags -->
    <meta name="description" content="Quản lý link affiliate Shopee - Tự động lấy thông tin sản phẩm">
    <meta name="theme-color" content="#EE4D2D">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- iOS Safari Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Shopee Aff">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-152x152.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }
        input[type="password"]::-webkit-credentials-auto-fill-button { display: none; }
    </style>
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => console.log('✅ Service Worker registered'))
                    .catch(error => console.error('❌ Service Worker registration failed:', error));
            });
        }
    </script>
</body>
</html>
