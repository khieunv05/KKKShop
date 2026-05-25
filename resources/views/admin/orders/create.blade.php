@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Tạo giao dịch</h2>
        <div class="text-secondary">Thêm đơn hàng thủ công</div>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">Quay lại</a>
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

<form action="{{ route('admin.orders.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Khách hàng</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Chọn khách hàng --</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select" required>
                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>pending</option>
                <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>paid</option>
                <option value="failed" {{ old('status') === 'failed' ? 'selected' : '' }}>failed</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Phương thức</label>
            <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Địa chỉ giao hàng</label>
            <input type="text" name="shipping_address" class="form-control" value="{{ old('shipping_address') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
    </div>

    <hr class="my-4">

    <h5 class="fw-bold mb-3">Sản phẩm</h5>
    <div id="items-container" class="vstack gap-3">
        <div class="row g-2 align-items-end item-row">
            <div class="col-md-8">
                <label class="form-label">Sản phẩm</label>
                <select name="items[0][product_id]" class="form-select" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-outline-danger btn-remove-row" disabled>Xóa</button>
            </div>
        </div>
    </div>

    <button type="button" id="add-item" class="btn btn-outline-primary mt-3">+ Thêm sản phẩm</button>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Tạo giao dịch</button>
    </div>
</form>

<script>
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item');

    addItemBtn.addEventListener('click', () => {
        const rows = itemsContainer.querySelectorAll('.item-row');
        const index = rows.length;
        const template = rows[0].cloneNode(true);

        template.querySelectorAll('select, input').forEach((el) => {
            if (el.name.includes('product_id')) {
                el.name = `items[${index}][product_id]`;
                el.value = '';
            }
            if (el.name.includes('quantity')) {
                el.name = `items[${index}][quantity]`;
                el.value = 1;
            }
        });

        template.querySelector('.btn-remove-row').disabled = false;
        itemsContainer.appendChild(template);
    });

    itemsContainer.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-remove-row')) {
            const row = event.target.closest('.item-row');
            if (row) {
                row.remove();
            }
        }
    });
</script>
@endsection