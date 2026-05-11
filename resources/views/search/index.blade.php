@extends('layouts.app')

@section('title', 'Tìm kiếm')

@section('content')
<h4 class="mb-2">Tìm kiếm</h4>
<p class="text-muted mb-4">Tìm kiếm sản phẩm với AI</p>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('search.index') }}">
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sản phẩm..."
                               value="{{ $query }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <input type="number" name="min_price" class="form-control" placeholder="Giá từ"
                           value="{{ request('min_price') }}">
                </div>
                <div class="col-6 col-md-2">
                    <input type="number" name="max_price" class="form-control" placeholder="Giá đến"
                           value="{{ request('max_price') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">
                <i class="bi bi-search me-1"></i>Tìm
            </button>
        </form>
    </div>
</div>

@if($query)
    <p class="text-muted mb-4">Tìm thấy {{ $total }} kết quả cho "{{ $query }}"</p>

    @if(count($results) > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @foreach($results as $product)
                <div class="col">
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    @else
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="bi bi-search display-1 text-muted mb-3"></i>
                <p class="text-muted mb-0">Không tìm thấy sản phẩm phù hợp</p>
            </div>
        </div>
    @endif
@else
    <div class="card text-center py-5">
        <div class="card-body">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <p class="text-muted mb-0">Nhập từ khóa để tìm kiếm sản phẩm</p>
        </div>
    </div>
@endif
@endsection
