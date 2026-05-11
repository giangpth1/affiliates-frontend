<div class="card product-card h-100">
    <div class="position-relative">
        @if($product['thumbnail_url'])
            <img src="{{ $product['thumbnail_url'] }}" alt="{{ $product['title'] }}"
                 class="card-img-top product-image">
        @else
            <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                <span class="text-muted">No Image</span>
            </div>
        @endif

        <a href="{{ $product['original_url'] }}" target="_blank" rel="noopener"
           class="position-absolute top-0 end-0 m-2 btn btn-sm btn-light rounded-circle">
            <i class="bi bi-box-arrow-up-right"></i>
        </a>
    </div>

    <div class="card-body">
        <h6 class="card-title text-dark mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            {{ $product['title'] }}
        </h6>

        <div class="d-flex justify-content-between align-items-center">
            <span class="badge bg-success">{{ $product['status'] }}</span>
            <small class="text-muted">
                {{ \Carbon\Carbon::parse($product['created_at'])->diffForHumans() }}
            </small>
        </div>
    </div>
</div>
