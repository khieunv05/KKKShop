```blade
@extends('master')

@section('content')

<div class="container mt-4">

    <h2>Thông tin thanh toán</h2>

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif

    <form
        method="POST"
        action="{{ route('checkout.store') }}">

        @csrf

        <div class="mb-3">

            <label>Tên người nhận</label>

            <input
                type="text"
                name="receiver_name"
                class="form-control"
                required>

        </div>

        <div class="mb-3">

            <label>Số điện thoại</label>

            <input
                type="text"
                name="phone"
                class="form-control"
                required>

        </div>

        <div class="mb-3">

            <label>Địa chỉ giao hàng</label>

            <textarea
                name="address"
                rows="4"
                class="form-control"
                required></textarea>

        </div>

        <button
            class="btn btn-primary">

            Đặt hàng

        </button>

    </form>

</div>

@endsection
```
`
