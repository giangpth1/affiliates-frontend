@extends('layouts.app')

@section('title', 'Đang xử lý')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card text-center py-5" id="statusCard">
            <div class="card-body">
                <div class="mb-4" id="statusIcon">
                    @if($link['status'] === 'done')
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background: #E8F5E9;">
                            <i class="bi bi-check-circle-fill text-success display-4"></i>
                        </div>
                    @elseif($link['status'] === 'failed')
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background: #FFEBEE;">
                            <i class="bi bi-x-circle-fill text-danger display-4"></i>
                        </div>
                    @else
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px; background: #E3F2FD;">
                            <div class="spinner-border spinner-shopee" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div id="statusText">
                    @if($link['status'] === 'done')
                        <span class="badge bg-success mb-3">Hoàn thành</span>
                        <p class="text-muted mb-4">Sản phẩm đã được thêm thành công!</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('products.show', $link['product_id']) }}" class="btn btn-primary">
                                Xem sản phẩm
                            </a>
                            <a href="{{ route('links.create') }}" class="btn btn-outline-secondary">
                                Thêm link khác
                            </a>
                        </div>
                    @elseif($link['status'] === 'failed')
                        <span class="badge bg-danger mb-3">Thất bại</span>
                        <p class="text-danger mb-4">{{ $link['error_message'] ?? 'Không thể xử lý link này' }}</p>
                        <a href="{{ route('links.create') }}" class="btn btn-outline-secondary">
                            Thử lại
                        </a>
                    @else
                        <span class="badge bg-info mb-3" id="statusBadge">
                            {{ $link['status'] === 'pending' ? 'Đang chờ xử lý' : 'Đang scrape...' }}
                        </span>
                        <p class="text-muted mb-0" id="pollCount">Đang xử lý...</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($link['status'] !== 'done' && $link['status'] !== 'failed')
@push('scripts')
<script>
let pollCount = 0;
const maxPolls = 20;
const linkId = '{{ $link['id'] }}';

function poll() {
    pollCount++;
    document.getElementById('pollCount').textContent = `Đang xử lý... (${pollCount}/${maxPolls})`;

    if (pollCount >= maxPolls) {
        document.getElementById('statusBadge').className = 'badge bg-warning mb-3';
        document.getElementById('statusBadge').textContent = 'Timeout';
        document.getElementById('pollCount').innerHTML =
            'Quá thời gian chờ. <a href="{{ route('links.create') }}">Thử lại</a>';
        return;
    }

    fetch(`/links/${linkId}/status`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(link => {
        if (link.status === 'done' || link.status === 'failed') {
            location.reload();
        } else {
            setTimeout(poll, 3000);
        }
    })
    .catch(err => {
        console.error('Poll error:', err);
        setTimeout(poll, 3000);
    });
}

setTimeout(poll, 3000);
</script>
@endpush
@endif
@endsection
