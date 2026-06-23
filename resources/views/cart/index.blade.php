@extends('master')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4">Giỏ hàng của bạn</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($cart) === 0)
        <div class="alert alert-info">Giỏ hàng của bạn đang trống.</div>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th style="width: 120px;">Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php
                            $product = $item['product'];
                            $subtotal = $product->price * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" style="width: 64px; height: 64px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="width: 64px; height: 64px; border-radius: 4px;">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $product->name }}</h6>
                                        @if($product->warranty)
                                            <small class="text-muted">Bảo hành: {{ $product->warranty }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
                            <td>
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $product->stock }}" class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                </form>
                            </td>
                            <td><strong>{{ number_format($subtotal, 0, ',', '.') }} ₫</strong></td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td><strong class="text-danger fs-5">{{ number_format($total, 0, ',', '.') }} ₫</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Tiếp tục mua sắm</a>
            <a href="{{ route('checkout.form') }}" class="btn btn-success">Thanh toán</a>
        </div>
    @endif
</div>
@endsection
