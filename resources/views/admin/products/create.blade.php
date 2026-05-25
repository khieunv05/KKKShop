@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Thêm sản phẩm</h2>
        <div class="text-secondary">Tạo sản phẩm mới</div>
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

<form action="{{ route('admin.products.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" min="0" value="{{ old('price', 0) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Giá cũ</label>
            <input type="number" name="old_price" class="form-control" min="0" value="{{ old('old_price') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Kho</label>
            <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', 0) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Bảo hành</label>
            <input type="text" name="warranty" class="form-control" value="{{ old('warranty') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Ảnh (URL/đường dẫn)</label>
            <input type="text" name="image" class="form-control" value="{{ old('image') }}">
        </div>
        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Danh mục</label>
            <select name="category_ids[]" class="form-select" multiple>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <div class="form-text">Giữ Ctrl (Windows) để chọn nhiều.</div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" name="is_builder" value="1" {{ old('is_builder') ? 'checked' : '' }}>
                <label class="form-check-label">Builder</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Lưu</button>
    </div>
</form>
@endsection