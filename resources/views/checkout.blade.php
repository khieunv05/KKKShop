@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Thanh toán</h2>
    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Quay lại giỏ hàng</a>
</div>

@if (count($cartItems) === 0)
<div class="alert alert-info">Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.</div>
@else
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Thông tin giao hàng</h5>
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" placeholder="Nguyễn Văn A">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" placeholder="0123456789">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Địa chỉ giao hàng</label>
                        <input type="text" class="form-control" placeholder="Số nhà, đường, quận/huyện">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" rows="3" placeholder="Ghi chú cho đơn hàng"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-4">
            <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>
            <div class="vstack gap-3">
                @foreach ($cartItems as $item)
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="fw-semibold">{{ $item['name'] }}</div>
                        <div class="text-secondary small">{{ $item['quantity'] }} x {{ number_format($item['price'], 0, ',', '.') }} đ</div>
                    </div>
                    <div class="fw-semibold">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ</div>
                </div>
                @endforeach
            </div>
            <hr>
            <div class="d-flex justify-content-between mb-2">
                <span>Tổng cộng</span>
                <strong>{{ number_format($total, 0, ',', '.') }} đ</strong>
            </div>
            <button class="btn btn-success w-100" type="button" disabled>Đặt hàng (chưa tích hợp)</button>
        </div>
    </div>
</div>
@endif
@endsection