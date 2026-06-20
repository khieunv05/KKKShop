@extends('master')

@section('content')
@php
	$qrTriggerUrl = route('user.add-money', ['qr_pay' => 1]);
@endphp

<div
	id="addMoneyPage"
	class="row justify-content-center"
	data-qr-trigger-url="{{ $qrTriggerUrl }}"
	data-should-auto-pay="{{ request('qr_pay') == 1 ? '1' : '0' }}"
>
	<div class="col-md-7 col-lg-6">
		<div class="card shadow-sm border-0">
			<div class="card-body p-4">
				<h3 class="mb-3" style="color: #0a3a5c;">Nạp Tiền Bằng QR (Mô Phỏng)</h3>

				@if(session('success'))
					<div class="alert alert-success">{{ session('success') }}</div>
				@endif
				<button id="showQrBtn" class="btn" style="background-color: #0a3a5c; color: white;">
					Hiển thị mã QR
				</button>

				<div id="qrWrap" class="text-center mt-4" style="display: none;">
					<img
						id="qrImage"
						src=""
						alt="QR Nap Tien"
						width="250"
						height="250"
						class="border rounded p-2 bg-white"
					>
					<p class="text-muted mt-3 mb-0" style="font-size: 14px;">
						Quét mã để mở trang xác nhận và tự động gửi yêu cầu nạp tiền.
					</p>
				</div>

				<form id="addMoneyForm" method="POST" action="{{ route('user.add-money.post') }}" class="d-none">
					@csrf
					<input type="hidden" name="amount">
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	(function () {
		var addMoneyPage = document.getElementById('addMoneyPage');
		if (!addMoneyPage) {
			return;
		}

		var qrTriggerUrl = addMoneyPage.dataset.qrTriggerUrl || '';
		var shouldAutoPay = addMoneyPage.dataset.shouldAutoPay === '1';

		var showQrBtn = document.getElementById('showQrBtn');
		var qrWrap = document.getElementById('qrWrap');
		var qrImage = document.getElementById('qrImage');
		var addMoneyForm = document.getElementById('addMoneyForm');

		if (showQrBtn) {
			showQrBtn.addEventListener('click', function () {
				var qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(qrTriggerUrl);
				qrImage.src = qrApiUrl;
				qrWrap.style.display = 'block';
			});
		}

		// Mo phong: khi mo URL tu QR thi tu dong submit form POST /add-money.
		if (shouldAutoPay && addMoneyForm) {
			addMoneyForm.submit();
		}
	})();
</script>
@endsection
