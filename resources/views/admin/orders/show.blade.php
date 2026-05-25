@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Chi tiết giao dịch #{{ $order->id }}</h2>
        <div class="text-secondary">Khách hàng: {{ $order->user?->name ?? 'N/A' }} ({{ $order->user?->email }})</div>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('admin.orders.edit', $order) }}">Sửa</a>
        <a class="btn btn-outline-primary" href="{{ route('admin.orders.index') }}">Quay lại</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card p-3">
            <div class="mb-2"><strong>Trạng thái:</strong> {{ strtoupper($order->status) }}</div>
            <div class="mb-2"><strong>Tổng:</strong> {{ number_format($order->total, 0, ',', '.') }} đ</div>
            <div class="mb-2"><strong>Phương thức:</strong> {{ $order->payment_method ?? 'N/A' }}</div>
            <div class="mb-2"><strong>SĐT:</strong> {{ $order->phone ?? 'N/A' }}</div>
            <div class="mb-2"><strong>Địa chỉ:</strong> {{ $order->shipping_address ?? 'N/A' }}</div>
            <div><strong>Ngày tạo:</strong> {{ $order->created_at?->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card p-3">
            <h5 class="fw-bold mb-3">Sản phẩm</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product?->name ?? 'N/A' }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->total, 0, ',', '.') }} đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection