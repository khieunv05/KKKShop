@extends('master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">Danh sách sản phẩm</h1>
            <div class="text-muted">Tên sản phẩm, ảnh thu nhỏ, danh mục, đơn giá, tồn kho</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.add_product') }}" class="btn btn-primary">Thêm sản phẩm</a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Danh mục</a>
        </div>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm kiếm theo tên hoặc mô tả">
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
            <button class="btn btn-outline-primary">Lọc</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Danh mục</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td style="width:96px;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded" style="width:72px; height:72px; object-fit:cover;">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width:72px; height:72px;">
                                        <i class="fa-solid fa-image fa-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @foreach($product->categories as $category)
                                    <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
                            <td>
                                @if((int) $product->stock === 0)
                                    <span class="badge bg-danger">Hết hàng</span>
                                @else
                                    {{ $product->stock }}
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Chưa có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
