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

<!-- Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>Đã thêm link, sản phẩm đang được xử lý
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <div id="errorToast" class="toast align-items-center text-bg-danger border-0 mt-2" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="errorToastMsg">Có lỗi xảy ra</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
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
    const successToast = new bootstrap.Toast(document.getElementById('successToast'), { delay: 4000 });
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'), { delay: 4000 });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const url = urlInput.value.trim();
        if (!url) return;

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

            const data = await response.json();

            if (response.ok) {
                urlInput.value = '';
                successToast.show();
            } else {
                document.getElementById('errorToastMsg').textContent = data.error || 'Có lỗi xảy ra';
                errorToast.show();
            }
        } catch (error) {
            document.getElementById('errorToastMsg').textContent = 'Không thể kết nối với server';
            errorToast.show();
        } finally {
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
