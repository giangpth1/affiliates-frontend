@extends('layouts.auth')

@section('title', 'Đăng nhập')

@section('content')
<div class="card shadow-sm" style="max-width: 400px; width: 100%;">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                 style="width: 64px; height: 64px; background: var(--color-primary);">
                <i class="bi bi-bag-fill text-white fs-3"></i>
            </div>
            <h4 class="mb-1">Đăng nhập</h4>
            <p class="text-muted small">Quản lý link affiliate Shopee</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="email@example.com" value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Nhập mật khẩu" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember" 
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Ghi nhớ đăng nhập (30 ngày)
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Đăng nhập
            </button>
        </form>

        <p class="text-center text-muted mb-0">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" style="color: var(--color-primary);">Đăng ký ngay</a>
        </p>
    </div>
</div>
@endsection
