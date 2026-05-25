@extends('master')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Lịch sử đơn hàng</h2>

    @if($orders->isEmpty())
    <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    @else
    <div class="list-group">
        @foreach($orders as $order)
        <div class="list-group-item list-group-item-action mb-3">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Đơn hàng #{{ $order->id }}</h5>
                <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
            </div>
            <p class="mb-1">Trạng thái: <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($order->status) }}</span></p>
            <p class="mb-1">Tổng tiền: <strong>{{ number_format($order->total, 0, ',', '.') }} đ</strong></p>
            <details class="mt-2">
                <summary>Xem chi tiết</summary>
                <ul class="list-unstyled mt-2">
                    @foreach($order->items as $item)
                    <li class="d-flex justify-content-between">
                        <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                        <span>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</span>
                    </li>
                    @endforeach
                </ul>
            </details>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection