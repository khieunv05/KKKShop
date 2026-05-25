@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Cập nhật sản phẩm</h2>
        <div class="text-secondary">{{ $product->name }}</div>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('admin.products.index') }}">Quay lại</a>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.products.update', $product) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" min="0" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Giá cũ</label>
            <input type="number" name="old_price" class="form-control" min="0" value="{{ old('old_price', $product->old_price) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Kho</label>
            <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Bảo hành</label>
            <input type="text" name="warranty" class="form-control" value="{{ old('warranty', $product->warranty) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Ảnh (URL/đường dẫn)</label>
            <input type="text" name="image" class="form-control" value="{{ old('image', $product->image) }}">
        </div>
        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Danh mục</label>
            <select name="category_ids[]" class="form-select" multiple>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $product->categories->contains($category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            <div class="form-text">Giữ Ctrl (Windows) để chọn nhiều.</div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="is_builder" value="1" {{ old('is_builder', $product->is_builder) ? 'checked' : '' }}>
                <label class="form-check-label">Builder</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </div>
</form>
@endsection