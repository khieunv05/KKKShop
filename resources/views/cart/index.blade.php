```blade
@extends('master')

@section('content')

<div class="container mt-4">

    <h2>Giỏ hàng</h2>

    @if(session('cart') && count(session('cart')) > 0)

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th width="150">Số lượng</th>
                    <th>Thành tiền</th>
                    <th width="100">Xóa</th>
                </tr>
            </thead>

            <tbody>

            @php
                $total = 0;
            @endphp

            @foreach($cart as $item)

                @php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                @endphp

                <tr>

                    <td>{{ $item['name'] }}</td>

                    <td>{{ number_format($item['price']) }} VNĐ</td>

                    <td>

                        <input
                            type="number"
                            min="1"
                            value="{{ $item['quantity'] }}"
                            class="form-control quantity"
                            data-id="{{ $item['id'] }}">

                    </td>

                    <td>

                        {{ number_format($subtotal) }} VNĐ

                    </td>

                    <td>

                        <button
                            class="btn btn-danger remove-item"
                            data-id="{{ $item['id'] }}">
                            Xóa
                        </button>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

        <div class="text-end">

            <h4>

                Tổng tiền:
                {{ number_format($total) }} VNĐ

            </h4>

            <a
                href="{{ route('checkout.form') }}"
                class="btn btn-success">

                Tiến hành thanh toán

            </a>

        </div>

    @else

        <div class="alert alert-warning">
            Giỏ hàng đang trống
        </div>

    @endif

</div>

<script>

document.querySelectorAll('.quantity').forEach(item => {

    item.addEventListener('change', function(){

        fetch('/cart/update/' + this.dataset.id, {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    '{{ csrf_token() }}'
            },

            body: JSON.stringify({
                quantity: this.value
            })

        }).then(() => location.reload());

    });

});

document.querySelectorAll('.remove-item').forEach(item => {

    item.addEventListener('click', function(){

        fetch('/cart/remove/' + this.dataset.id, {

            method: 'DELETE',

            headers: {
                'X-CSRF-TOKEN':
                    '{{ csrf_token() }}'
            }

        }).then(() => location.reload());

    });

});

</script>

@endsection
```
