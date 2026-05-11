<header class="d-lg-none bg-white border-bottom sticky-top">
    <div class="d-flex align-items-center justify-content-between p-3">
        <a href="{{ route('products.index') }}" class="d-flex align-items-center text-decoration-none">
            <div class="rounded-2 d-flex align-items-center justify-content-center me-2"
                 style="width: 32px; height: 32px; background: var(--color-primary);">
                <i class="bi bi-bag-fill text-white"></i>
            </div>
            <span class="fw-bold text-dark">Shopee Affiliate</span>
        </a>

        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
            <i class="bi bi-list fs-5"></i>
        </button>
    </div>
</header>

<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-link-45deg me-2"></i>Link affiliate
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('links.create') }}" class="nav-link {{ request()->routeIs('links.*') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle me-2"></i>Thêm link
                </a>
            </li>
        </ul>

        <hr>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 text-start text-danger">
                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
            </button>
        </form>
    </div>
</div>
