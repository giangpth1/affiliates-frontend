@extends('layouts.auth')

@section('title', 'Đăng ký')

@section('content')
<div class="card shadow-sm" style="max-width: 400px; width: 100%;">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                 style="width: 64px; height: 64px; background: var(--color-primary);">
                <i class="bi bi-bag-fill text-white fs-3"></i>
            </div>
            <h4 class="mb-1">Đăng ký</h4>
            <p class="text-muted small">Tạo tài khoản mới</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên hiển thị</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror"
                           placeholder="Nhập tên của bạn" value="{{ old('display_name') }}" required>
                </div>
                @error('display_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

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
                           placeholder="Tạo mật khẩu" required minlength="8">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <small class="text-muted">Tối thiểu 8 ký tự, gồm chữ hoa, chữ thường và số</small>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Đăng ký
            </button>
        </form>

        <p class="text-center text-muted mb-0">
            Đã có tài khoản?
            <a href="{{ route('login') }}" style="color: var(--color-primary);">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
