@extends('layouts.app')

@section('title', 'Thêm link')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <h4 class="mb-2">Thêm link</h4>
        <p class="text-muted mb-4">Paste link Shopee để tự động lấy thông tin sản phẩm</p>

        <div class="card">
            <div class="card-body">
                <form id="add-link-form" method="POST" action="{{ route('links.store') }}">
                    @csrf

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="url" id="link-url" class="form-control"
                                   placeholder="https://shopee.vn/... hoặc https://shp.ee/..."
                                   value="{{ old('url') }}" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="pasteFromClipboard()">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        <div id="url-error" class="text-danger small mt-1" style="display: none;"></div>
                    </div>

                    <button type="submit" id="submit-btn" class="btn btn-primary w-100">
                        <span id="submit-text">Thêm sản phẩm</span>
                        <span id="submit-loading" class="spinner-border spinner-border-sm" style="display: none;"></span>
                    </button>
                </form>

                <hr>

                <p class="text-muted small mb-2">Định dạng được hỗ trợ:</p>
                <ul class="text-muted small mb-0">
                    <li>https://shopee.vn/product/...</li>
                    <li>https://shp.ee/...</li>
                    <li>https://vn.shp.ee/...</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <h5 class="mt-3 mb-2">Đã thêm link!</h5>
                <p class="text-muted mb-4">Sản phẩm đang được xử lý, bạn có thể thêm link khác</p>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('add-link-form');
    const urlInput = document.getElementById('link-url');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');
    const urlError = document.getElementById('url-error');
    
    // Check if Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap not loaded!');
        return;
    }
    
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log('Form submitted via AJAX');
        
        const url = urlInput.value.trim();
        if (!url) return;
        
        // Disable submit button
        submitBtn.disabled = true;
        submitText.style.display = 'none';
        submitLoading.style.display = 'inline-block';
        urlError.style.display = 'none';
        
        try {
            const response = await fetch('{{ route('links.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ url: url })
            });
            
            console.log('Response status:', response.status);
            const data = await response.json();
            console.log('Response data:', data);
            
            if (response.ok) {
                // Show success modal
                successModal.show();
                // Reset form
                urlInput.value = '';
                urlError.style.display = 'none';
            } else {
                // Show error
                urlError.textContent = data.error || 'Có lỗi xảy ra';
                urlError.style.display = 'block';
            }
        } catch (error) {
            console.error('Fetch error:', error);
            urlError.textContent = 'Không thể kết nối với server';
            urlError.style.display = 'block';
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitText.style.display = 'inline';
            submitLoading.style.display = 'none';
        }
    });
});

function pasteFromClipboard() {
    navigator.clipboard.readText().then(text => {
        document.getElementById('link-url').value = text;
        document.getElementById('link-url').focus();
    }).catch(err => {
        console.error('Failed to read clipboard:', err);
    });
}
</script>
@endsection
