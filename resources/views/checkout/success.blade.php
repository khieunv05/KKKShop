@extends('master')

@section('content')
<div class="container text-center py-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body p-5">
            <h2 class="text-success mb-4">Đặt hàng thành công!</h2>
            <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận.</p>
            <p>Mã đơn hàng của bạn là: <strong>#{{ $order->id }}</strong></p>
            <p>Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng.</p>
            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
                <a href="{{ route('order.history') }}" class="btn btn-outline-secondary">Xem lịch sử đơn hàng</a>
            </div>
        </div>
    </div>
</div>
@endsection