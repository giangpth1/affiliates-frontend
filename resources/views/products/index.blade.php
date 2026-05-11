@extends('layouts.app')

@section('title', 'Link affiliate')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1">Link affiliate</h4>
        <p class="text-muted mb-0">
            {{ $total }} link
            @if($pendingCount > 0)
                <span class="text-warning ms-2">
                    <i class="bi bi-hourglass-split"></i> Đang xử lý {{ $pendingCount }} link
                </span>
            @endif
        </p>
    </div>
    <a href="{{ route('links.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Thêm link
    </a>
</div>

<form method="GET" action="{{ route('products.index') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="q" class="form-control"
               placeholder="Tìm kiếm link affiliate..."
               value="{{ $query ?? '' }}">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="bi bi-search"></i>
        </button>
        @if(!empty($query))
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Xóa tìm kiếm">
                <i class="bi bi-x-lg"></i>
            </a>
        @endif
    </div>
</form>

@if(!empty($searchError ?? null))
    <div class="alert alert-danger py-2 small mb-3">
        <i class="bi bi-exclamation-triangle me-1"></i> Lỗi tìm kiếm: {{ $searchError }}
    </div>
@elseif(!empty($query))
    <p class="text-muted small mb-3">Kết quả tìm kiếm cho "<strong>{{ $query }}</strong>" — {{ $total }} link</p>
@endif

@if(count($products) > 0)
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th class="d-none d-md-table-cell" style="width: 400px;">Link affiliate</th>
                        <th class="d-md-none" style="width: 60px;">Link</th>
                        <th class="d-none d-md-table-cell" style="width: 120px;">Ngày thêm</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product['thumbnail_url'])
                                    <img src="{{ $product['thumbnail_url'] }}" 
                                         alt="{{ $product['title'] }}"
                                         class="rounded"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-medium" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $product['title'] }}</div>
                                        <small class="text-muted d-block" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px;">
                                            <i class="bi bi-shop me-1"></i>
                                            @if(!empty($product['shop_name']))
                                                {{ $product['shop_name'] }}
                                            @else
                                                Shop ID: {{ $product['shop_id'] ?? '' }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <!-- Desktop: Show full link input + buttons -->
                            <td class="d-none d-md-table-cell">
                                <div class="input-group input-group-sm">
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           value="{{ $product['affiliate_url'] ?? $product['original_url'] }}" 
                                           id="link-{{ $product['id'] }}"
                                           readonly>
                                    <button class="btn btn-outline-secondary" 
                                            type="button"
                                            onclick="copyLink('{{ $product['id'] }}')"
                                            title="Copy link">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                    <a href="{{ $product['affiliate_url'] ?? $product['original_url'] }}" 
                                       target="_blank" 
                                       rel="noopener"
                                       class="btn btn-outline-secondary"
                                       title="Mở link">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </td>
                            
                            <!-- Mobile: Show only copy button -->
                            <td class="d-md-none">
                                <input type="text" 
                                       value="{{ $product['affiliate_url'] ?? $product['original_url'] }}" 
                                       id="link-{{ $product['id'] }}"
                                       style="position: absolute; left: -9999px;" 
                                       readonly>
                                <button class="btn btn-primary btn-sm" 
                                        onclick="copyLink('{{ $product['id'] }}')"
                                        title="Copy link">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </td>
                            
                            <!-- Desktop: Show date -->
                            <td class="d-none d-md-table-cell">
                                @if(!empty($product['created_at']))
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($product['created_at'])->format('d/m/Y') }}
                                        <br>
                                        {{ \Carbon\Carbon::parse($product['created_at'])->format('H:i') }}
                                    </small>
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($totalPages > 1)
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ $page - 1 }}">Trước</a>
                </li>
                <li class="page-item disabled">
                    <span class="page-link">Trang {{ $page }} / {{ $totalPages }}</span>
                </li>
                <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ $page + 1 }}">Sau</a>
                </li>
            </ul>
        </nav>
    @endif

    <script>
    function copyLink(productId) {
        const input = document.getElementById('link-' + productId);
        const linkText = input.value;
        const btn = event.target.closest('button');
        
        // Create temporary textarea for reliable copying
        const tempTextarea = document.createElement('textarea');
        tempTextarea.value = linkText;
        tempTextarea.style.position = 'fixed';
        tempTextarea.style.left = '-9999px';
        tempTextarea.style.top = '0';
        document.body.appendChild(tempTextarea);
        
        try {
            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(linkText)
                    .then(() => {
                        showSuccess(btn);
                        document.body.removeChild(tempTextarea);
                    })
                    .catch(() => {
                        // Fallback to execCommand
                        useFallback(tempTextarea, btn);
                    });
            } else {
                // Use fallback directly
                useFallback(tempTextarea, btn);
            }
        } catch (e) {
            console.error('Copy failed:', e);
            document.body.removeChild(tempTextarea);
            alert('Lỗi khi copy. Vui lòng copy thủ công từ ô bên cạnh.');
        }
        
        function useFallback(textarea, button) {
            try {
                textarea.focus();
                textarea.select();
                const success = document.execCommand('copy');
                document.body.removeChild(textarea);
                
                if (success) {
                    showSuccess(button);
                } else {
                    alert('Không thể copy tự động. Vui lòng copy thủ công từ ô bên cạnh.');
                }
            } catch (e) {
                document.body.removeChild(textarea);
                alert('Lỗi khi copy. Vui lòng copy thủ công từ ô bên cạnh.');
            }
        }
        
        function showSuccess(button) {
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.disabled = false;
            }, 1500);
        }
    }
    </script>
@else
    <div class="card text-center py-5">
        <div class="card-body">
            <i class="bi bi-link-45deg display-1 text-muted mb-3"></i>
            <h5>Chưa có link affiliate</h5>
            <p class="text-muted mb-4">Thêm link Shopee để bắt đầu quản lý link affiliate</p>
            <a href="{{ route('links.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Thêm link đầu tiên
            </a>
        </div>
    </div>
@endif
@endsection
