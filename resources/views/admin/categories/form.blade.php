@extends('master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">{{ isset($category) ? 'Chỉnh sửa danh mục' : 'Thêm danh mục' }}</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Quay lại</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" method="POST" class="row g-3">
                @csrf
                @if(isset($category))
                    @method('PUT')
                @endif

                <div class="col-12">
                    <label class="form-label">Tên danh mục</label>
                    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Quay lại danh mục</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
