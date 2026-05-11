@extends('layouts.app')

@section('title', $product['title'])

@section('content')
<a href="{{ route('products.index') }}" class="btn btn-link text-muted mb-4 ps-0">
    <i class="bi bi-arrow-left me-1"></i>Quay lại
</a>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            @if($product['thumbnail_url'])
                <img src="{{ $product['thumbnail_url'] }}" alt="{{ $product['title'] }}"
                     class="card-img-top" style="aspect-ratio: 1; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="aspect-ratio: 1;">
                    <span class="text-muted fs-4">No Image</span>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="mb-3">{{ $product['title'] }}</h4>

                @if($product['price'])
                    <p class="product-price fs-3 mb-4">
                        {{ number_format($product['price'], 0, ',', '.') }}đ
                    </p>
                @endif

                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted">Trạng thái</td>
                        <td><span class="badge bg-success">{{ $product['status'] }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Shop ID</td>
                        <td>{{ $product['shop_id'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Item ID</td>
                        <td>{{ $product['item_id'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Ngày tạo</td>
                        <td>{{ \Carbon\Carbon::parse($product['created_at'])->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2 mb-4">
            <button class="btn btn-outline-secondary flex-fill" onclick="copyUrl('{{ $product['original_url'] }}')">
                <i class="bi bi-clipboard me-1"></i>Copy link
            </button>
            <a href="{{ $product['original_url'] }}" target="_blank" rel="noopener"
               class="btn btn-primary flex-fill">
                <i class="bi bi-box-arrow-up-right me-1"></i>Mở Shopee
            </a>
        </div>

        <form action="{{ route('products.destroy', $product['id']) }}" method="POST"
              onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
            @csrf
            @method('DELETE')
            <input type="hidden" name="shop_id" value="{{ $product['shop_id'] }}">
            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="bi bi-trash me-1"></i>Xóa sản phẩm
            </button>
        </form>
    </div>
</div>
@endsection
