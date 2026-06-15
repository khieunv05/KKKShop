@extends('master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Danh sách sản phẩm</h1>
        @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Vào trang admin</a>
        @endif
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm kiếm sản phẩm...">
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">-- Tất cả danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Lọc</button>
        </div>
    </form>

    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height:220px; object-fit:cover;" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ number_format($product->price, 0, ',', '.') }} ₫</strong>
                            @if((int) $product->stock === 0)
                                <span class="badge bg-danger">Hết hàng</span>
                            @else
                                <span class="text-muted small">Tồn kho: {{ $product->stock }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning mb-0">Chưa có sản phẩm nào.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
