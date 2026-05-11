<aside class="sidebar d-none d-lg-flex flex-column">
    <div class="p-4 border-bottom">
        <a href="{{ route('products.index') }}" class="d-flex align-items-center text-decoration-none">
            <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                 style="width: 40px; height: 40px; background: var(--color-primary);">
                <i class="bi bi-bag-fill text-white"></i>
            </div>
            <span class="fw-bold fs-5 text-dark">Shopee Affiliate</span>
        </a>
    </div>

    <nav class="flex-grow-1 py-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('products.index') }}"
                   class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-link-45deg me-2"></i>
                    Link affiliate
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('links.create') }}"
                   class="nav-link {{ request()->routeIs('links.*') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle me-2"></i>
                    Thêm link
                </a>
            </li>
        </ul>
    </nav>

    <div class="p-3 border-top">
        <div class="d-flex align-items-center mb-3 px-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                 style="width: 40px; height: 40px; background: #FEF2F0;">
                <span style="color: var(--color-primary); font-weight: 600;">
                    {{ strtoupper(substr($currentUser['display_name'] ?? 'U', 0, 1)) }}
                </span>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-medium text-truncate">{{ $currentUser['display_name'] ?? 'User' }}</div>
                <small class="text-muted text-truncate d-block">{{ $currentUser['email'] ?? '' }}</small>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i>
                Đăng xuất
            </button>
        </form>
    </div>
</aside>
