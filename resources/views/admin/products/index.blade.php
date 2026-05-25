@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Quản trị sản phẩm</h2>
        <div class="text-secondary">Danh sách sản phẩm</div>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.products.create') }}">
        <i class="fa-solid fa-plus me-2"></i>Thêm sản phẩm
    </a>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>Kho</th>
                <th>Trạng thái</th>
                <th>Danh mục</th>
                <th class="text-end">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td class="fw-semibold">{{ $product->name }}</td>
                <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <span class="badge text-bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                        {{ $product->is_active ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                </td>
                <td>
                    @if ($product->categories->isNotEmpty())
                    {{ $product->categories->pluck('name')->join(', ') }}
                    @else
                    <span class="text-secondary">Chưa gán</span>
                    @endif
                </td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.products.edit', $product) }}">Sửa</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa sản phẩm này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-secondary">Chưa có sản phẩm.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $products->links() }}
</div>
@endsection