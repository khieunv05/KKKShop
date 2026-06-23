@extends('master')

@section('content')
<div class="container mt-4">
    <h2>Thông tin thanh toán</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf

        <div class="mb-3">
            <label>Tên người nhận</label>
            <input type="text" name="receiver_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Địa chỉ giao hàng</label>
            <textarea name="address" rows="4" class="form-control" required></textarea>
        </div>

        <div class="mb-4">
            <label class="fw-bold mb-2">Phương thức thanh toán</label>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="payment_method" id="payCod" value="cod" checked>
                <label class="form-check-label" for="payCod">
                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                    <div class="text-muted small">Bạn chỉ thanh toán khi nhận được hàng.</div>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="payQr" value="qr">
                <label class="form-check-label" for="payQr">
                    <strong>Quét QR nạp tiền trả thẳng</strong>
                    <div class="text-muted small">Nạp tiền vào tài khoản qua QR để thanh toán ngay.</div>
                </label>
            </div>
        </div>

        <button class="btn btn-primary">Đặt hàng</button>
    </form>
</div>
@endsection
