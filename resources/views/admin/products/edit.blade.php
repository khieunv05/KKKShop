@extends('master')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Chỉnh sửa sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Quay lại</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-12">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Mô tả chi tiết</label>
                    <textarea id="description_editor" name="description" rows="6" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Giá bán</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Giá cũ</label>
                    <input type="number" name="old_price" value="{{ old('old_price', $product->old_price) }}" min="0" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Bảo hành (tháng)</label>
                    <input type="number" name="warranty" value="{{ old('warranty', $product->warranty) }}" min="1" class="form-control" required>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Ảnh sản phẩm</label>
                    <input type="file" id="image_input" name="image" accept="image/*" class="form-control">
                    <div class="mt-2">
                        @if($product->image)
                            <img id="image_preview" src="{{ asset('storage/' . $product->image) }}" alt="preview" style="max-width:180px; max-height:180px; object-fit:cover;">
                        @else
                            <img id="image_preview" src="" alt="preview" style="display:none; max-width:180px; max-height:180px; object-fit:cover;">
                        @endif
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Danh mục</label>
                    <div class="row g-2">
                        @foreach($categories as $category)
                            <div class="col-md-4">
                                <label class="form-check">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input" {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <span class="form-check-label">{{ $category->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description_editor');

    const imageInput = document.getElementById('image_input');
    const preview = document.getElementById('image_preview');

    if (imageInput) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'inline-block';
        });
    }
</script>
@endsection
