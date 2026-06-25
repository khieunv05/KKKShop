@extends('master')

@section('content')
<div class="admin-store-page py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <h1 class="h3 mb-2" style="color: #0a3a5c;">Chỉnh sửa sản phẩm</h1>
                    <p class="text-muted mb-0">Cập nhật thông tin sản phẩm và gắn danh mục phù hợp.</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách sản phẩm
                </a>
            </div>

            @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <div class="fw-semibold mb-2">Vui lòng kiểm tra lại các trường sau:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 24px;">
                <div class="row g-0">
                    <div class="col-lg-4" style="background: linear-gradient(160deg, #0a3a5c 0%, #153e65 55%, #ff6b35 100%); color: #fff;">
                        <div class="h-100 p-4 p-lg-5 d-flex flex-column justify-content-between">
                            <div>
                                <div class="mb-5">
                                    <h2 class="h4 fw-bold mb-2">Chỉnh sửa sản phẩm</h2>
                                    <p class="mb-0" style="opacity: .9;">Cập nhật thông tin cần thiết cho sản phẩm</p>
                                </div>

                                <div class="d-grid gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box"><i class="fa-solid fa-box-open"></i></div>
                                        <div>
                                            <div class="fw-semibold">Thông tin sản phẩm</div>
                                            <small style="opacity:.85;">Tên, giá, tồn kho, bảo hành</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box"><i class="fa-solid fa-image"></i></div>
                                        <div>
                                            <div class="fw-semibold">Ảnh đại diện</div>
                                            <small style="opacity:.85;">Hỗ trợ jpeg, png, jpg, gif, webp</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box"><i class="fa-solid fa-tags"></i></div>
                                        <div>
                                            <div class="fw-semibold">Danh mục</div>
                                            <small style="opacity:.85;">Chọn ít nhất một danh mục</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 bg-white">
                        <div class="p-4 p-lg-5">
                            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="row g-4">
                                @csrf
                                @method('PUT')

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control form-control-lg @error('name') is-invalid @enderror" placeholder="Ví dụ: PC Gaming Ryzen 5 5600 + RTX 4060">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Mô tả chi tiết</label>
                                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Mô tả chi tiết về sản phẩm">{{ old('description', $product->description) }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Giá bán <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">VNĐ</span>
                                        <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" class="form-control @error('price') is-invalid @enderror" placeholder="0">
                                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Giá cũ</label>
                                    <div class="input-group">
                                        <span class="input-group-text">VNĐ</span>
                                        <input type="number" name="old_price" value="{{ old('old_price', $product->old_price) }}" min="0" class="form-control @error('old_price') is-invalid @enderror" placeholder="0">
                                        @error('old_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tồn kho <span class="text-danger">*</span></label>
                                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="form-control @error('stock') is-invalid @enderror" placeholder="0">
                                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Bảo hành (tháng) <span class="text-danger">*</span></label>
                                    <input type="number" name="warranty" value="{{ old('warranty', $product->warranty) }}" min="1" class="form-control @error('warranty') is-invalid @enderror" placeholder="12">
                                    @error('warranty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Ảnh sản phẩm</label>
                                    <input type="file" id="image_input" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="mt-2">
                                        @if($product->image_url)
                                        <img id="image_preview" src="{{ $product->image_url }}" alt="preview" style="max-width:180px; max-height:180px; object-fit:cover; border-radius:12px;">
                                        @else
                                        <img id="image_preview" src="" alt="preview" style="display:none; max-width:180px; max-height:180px; object-fit:cover; border-radius:12px;">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-semibold mb-0">Danh mục <span class="text-danger">*</span></label>
                                        <small class="text-muted">Chọn một hoặc nhiều danh mục</small>
                                    </div>

                                    <div class="row g-3">
                                        @forelse ($categories as $category)
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <label class="category-card h-100 w-100">
                                                <input
                                                    type="checkbox"
                                                    name="categories[]"
                                                    value="{{ $category->id }}"
                                                    class="category-check"
                                                    {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <span class="category-card-body">
                                                    <span class="d-flex align-items-start justify-content-between gap-3">
                                                        <span>
                                                            <span class="d-block fw-semibold mb-1">{{ $category->name }}</span>
                                                            <span class="text-muted small">{{ $category->description ?? 'Không có mô tả' }}</span>
                                                        </span>
                                                        <span class="check-indicator"><i class="fa-solid fa-check"></i></span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                        @empty
                                        <div class="col-12">
                                            <div class="alert alert-warning mb-0">Chưa có danh mục nào trong database.</div>
                                        </div>
                                        @endforelse
                                    </div>
                                    @error('categories')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 d-flex flex-wrap gap-3 pt-2">
                                    <button type="submit" class="btn btn-primary btn-lg px-4" style="background-color:#0a3a5c; border-color:#0a3a5c;">
                                        <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-lg px-4">Hủy</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-store-page {
        min-height: 100%;
    }

    .icon-box {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        flex: 0 0 auto;
        transition: none;
    }

    .category-card {
        display: block;
        margin: 0;
        cursor: pointer;
    }

    .category-card-body {
        display: block;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #fff;
        height: 100%;
    }

    .category-check {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .category-check:checked+.category-card-body {
        border-color: #0a3a5c;
        box-shadow: 0 10px 24px rgba(10, 58, 92, 0.12);
        transition: none;
    }

    .check-indicator {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef2f7;
        color: transparent;
        transition: none;
        flex: 0 0 auto;
    }

    .category-check:checked+.category-card-body .check-indicator {
        background: #0a3a5c;
        color: #fff;
        transition: none;
    }
</style>
@endsection

@section('scripts')
<script>
    const imageInput = document.getElementById('image_input');
    const preview = document.getElementById('image_preview');

    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'inline-block';
        });
    }
</script>
@endsection