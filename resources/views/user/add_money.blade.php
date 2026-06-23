@extends('master')

@section('content')
@php
    $user = auth()->user();
    $balance = $user->current_balance ?? 0;
    $totalDue = $order ? $order->total_price + $order->shipping_fee : 0;
    $canPay = $order && $balance >= $totalDue;
    $qrTriggerUrl = route('user.add-money', $order ? ['order_id' => $order->id, 'qr_pay' => 1] : ['qr_pay' => 1]);
@endphp

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="mb-3" style="color: #0a3a5c;">Nạp tiền & Thanh toán</h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="bg-light p-3 rounded mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Số dư hiện tại:</span>
                        <strong class="text-primary">{{ number_format($balance, 0, ',', '.') }} ₫</strong>
                    </div>
                    @if($order)
                        <div class="d-flex justify-content-between mb-1">
                            <span>Đơn hàng #{{ $order->id }} - Tổng thanh toán:</span>
                            <strong class="text-danger">{{ number_format($totalDue, 0, ',', '.') }} ₫</strong>
                        </div>
                        <hr>
                        @if($canPay)
                            <div class="alert alert-success mb-0 py-2">Bạn có đủ số dư để thanh toán đơn hàng này.</div>
                        @else
                            <div class="alert alert-warning mb-0 py-2">Số dư không đủ. Vui lòng nạp thêm <strong>{{ number_format($totalDue - $balance, 0, ',', '.') }} ₫</strong> để thanh toán.</div>
                        @endif
                    @endif
                </div>

                @if($canPay)
                    <form method="POST" action="{{ route('orders.pay', $order->id) }}" class="mb-3">
                        @csrf
                        <button class="btn btn-success w-100 py-2 fw-bold">Thanh toán ngay ({{ number_format($totalDue, 0, ',', '.') }} ₫)</button>
                    </form>
                    <p class="text-center text-muted small mb-2">Hoặc nạp thêm tiền bằng QR bên dưới</p>
                @endif

                <button id="showQrBtn" class="btn w-100 py-2" style="background-color: #0a3a5c; color: white;">
                    <i class="fas fa-qrcode me-2"></i>Hiển thị mã QR nạp tiền
                </button>

                <div id="qrWrap" class="text-center mt-4" style="display: none;">
                    <img id="qrImage" src="" alt="QR Nap Tien" width="250" height="250" class="border rounded p-2 bg-white">
                    <p class="text-muted mt-3 mb-0" style="font-size: 14px;">
                        Quét mã để nạp <strong>100,000,000 ₫</strong> vào tài khoản.
                    </p>
                </div>

                <form id="addMoneyForm" method="POST" action="{{ route('user.add-money.post') }}{{ $order ? '?order_id=' . $order->id : '' }}" class="d-none">
                    @csrf
                    <input type="hidden" name="amount" value="100000000">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var showQrBtn = document.getElementById('showQrBtn');
        var qrWrap = document.getElementById('qrWrap');
        var qrImage = document.getElementById('qrImage');
        var addMoneyForm = document.getElementById('addMoneyForm');
        var qrTriggerUrl = '{{ $qrTriggerUrl }}';
        var shouldAutoPay = {{ request('qr_pay') == 1 ? 'true' : 'false' }};

        if (showQrBtn) {
            showQrBtn.addEventListener('click', function () {
                var qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(qrTriggerUrl);
                qrImage.src = qrApiUrl;
                qrWrap.style.display = 'block';
            });
        }

        if (shouldAutoPay && addMoneyForm) {
            addMoneyForm.submit();
        }
    })();
</script>
@endsection
