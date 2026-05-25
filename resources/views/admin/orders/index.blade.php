@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Quản trị giao dịch</h2>
        <div class="text-secondary">Danh sách đơn hàng / giao dịch</div>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.orders.create') }}">
        <i class="fa-solid fa-plus me-2"></i>Tạo giao dịch
    </a>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card p-3 h-100">
            <div class="text-secondary small">Tổng giao dịch</div>
            <div class="fs-4 fw-bold">{{ $stats['total_orders'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card p-3 h-100">
            <div class="text-secondary small">Đã thanh toán</div>
            <div class="fs-4 fw-bold text-success">{{ $stats['paid_orders'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card p-3 h-100">
            <div class="text-secondary small">Đang chờ</div>
            <div class="fs-4 fw-bold text-warning">{{ $stats['pending_orders'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card p-3 h-100">
            <div class="text-secondary small">Doanh thu</div>
            <div class="fs-5 fw-bold">{{ number_format($stats['revenue'], 0, ',', '.') }} đ</div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Khách hàng</th>
                <th>Tổng</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th class="text-end">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>
                    <div class="fw-semibold">{{ $order->user?->name ?? 'N/A' }}</div>
                    <div class="text-secondary small">{{ $order->user?->email }}</div>
                </td>
                <td>{{ number_format($order->total, 0, ',', '.') }} đ</td>
                <td>
                    <span class="badge text-bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'failed' ? 'danger' : 'secondary') }}">
                        {{ strtoupper($order->status) }}
                    </span>
                </td>
                <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $order) }}">Xem</a>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.edit', $order) }}">Sửa</a>
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa giao dịch này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-secondary">Chưa có giao dịch.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $orders->links() }}
</div>
@endsection