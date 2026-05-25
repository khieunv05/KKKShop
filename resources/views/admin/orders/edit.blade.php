@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Cập nhật giao dịch #{{ $order->id }}</h2>
        <div class="text-secondary">Khách hàng: {{ $order->user?->name ?? 'N/A' }}</div>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('admin.orders.show', $order) }}">Quay lại</a>
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

<form action="{{ route('admin.orders.update', $order) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select" required>
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>pending</option>
                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>paid</option>
                <option value="failed" {{ $order->status === 'failed' ? 'selected' : '' }}>failed</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Phương thức</label>
            <input type="text" name="payment_method" class="form-control" value="{{ $order->payment_method }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ $order->phone }}">
        </div>
        <div class="col-md-8">
            <label class="form-label">Địa chỉ giao hàng</label>
            <input type="text" name="shipping_address" class="form-control" value="{{ $order->shipping_address }}">
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </div>
</form>
@endsection