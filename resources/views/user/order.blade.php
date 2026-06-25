@extends('master')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="p-3 border-bottom d-flex align-items-center">
                        <div class="me-2">
                            <span
                                class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center"
                                style="width:40px;height:40px;">{{ strtoupper(substr(auth()->user()->name ?? 'U',0,1))
                                }}</span>
                        </div>
                        <div>
                            <div class="fw-bold">{{ auth()->user()->name ?? 'Người dùng' }}</div>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">Thông tin
                            tài khoản</a>
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action active">Quản
                            lý đơn hàng</a>
                        <a href="{{ route('profile.password.edit') }}"
                            class="list-group-item list-group-item-action">Thay đổi mật khẩu</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="list-group-item list-group-item-action w-100 text-start border-0">Đăng
                                xuất</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-1">Đơn hàng của bạn</h4>
                            <div class="text-muted">Bấm vào từng đơn để xem sản phẩm đã mua</div>
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $orders->count() }} đơn hàng</span>
                    </div>

                    @if($orders->isEmpty())
                    <div class="alert alert-light border mb-0">
                        Bạn chưa có đơn hàng nào.
                    </div>
                    @else
                    <div class="accordion" id="ordersAccordion">
                        @foreach($orders as $order)
                        @php
                        $itemCount = $order->products->sum(fn ($product) => $product->pivot->quantity);
                        @endphp
                        <div class="accordion-item mb-3 border rounded overflow-hidden">
                            <h2 class="accordion-header" id="heading-{{ $order->id }}">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse-{{ $order->id }}"
                                    aria-expanded="false" aria-controls="collapse-{{ $order->id }}">
                                    <div
                                        class="w-100 d-flex flex-wrap justify-content-between align-items-center gap-1 gap-sm-2">
                                        <span class="fw-bold">Đơn #{{ $order->id }}</span>
                                        <span class="text-muted small">{{ $order->created_at?->format('d/m/Y H:i')
                                            }}</span>
                                        <span class="badge bg-secondary text-uppercase">{{ $order->status }}</span>
                                        <span
                                            class="badge {{ $order->is_paid === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{
                                            $order->is_paid === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</span>
                                        <span class="text-muted small">{{ $itemCount }} sản phẩm</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse-{{ $order->id }}" class="accordion-collapse collapse"
                                aria-labelledby="heading-{{ $order->id }}" data-bs-parent="#ordersAccordion">
                                <div class="accordion-body bg-white">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded bg-light h-100">
                                                <div class="fw-bold mt-2 mb-2">Địa chỉ nhận hàng</div>
                                                <div class="text-muted">{{ $order->address }}</div>
                                                <div class="fw-bold mt-2 mb-2">Tên người nhận</div>
                                                <div class="text-muted">{{ $order->receiver_name }}</div>
                                                <div class="fw-bold mt-2 mb-2">Số điện thoại</div>
                                                <div class="text-muted">{{ $order->phone }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded bg-light h-100">
                                                <div class="fw-bold mt-2 mb-2">Thông tin đơn</div>
                                                <div class="d-flex justify-content-between mb-1"><span>Tình
                                                        trạng</span><span class="text-uppercase">{{ $order->status
                                                        }}</span></div>
                                                <div class="d-flex justify-content-between mb-1"><span>Thanh
                                                        toán</span><span>{{ $order->is_paid === 'paid' ? 'Đã thanh toán'
                                                        : 'Chưa thanh toán' }}</span></div>
                                                <div class="d-flex justify-content-between"><span>Phí
                                                        ship</span><span>{{ number_format($order->shipping_fee) }}
                                                        đ</span></div>
                                                <div class="d-flex justify-content-between"><span>Tổng
                                                        tiền</span><span>{{ number_format($order->total_price) }}
                                                        đ</span></div>
                                                <form action="{{ route('orders.confirm', $order->id) }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success mt-3 w-100"
                                                        style="{{ $order->status!== 'completed' ? 'display:block;' : 'display:none;' }}">

                                                        Xác nhận đã nhận hàng
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 80px;">Ảnh</th>
                                                    <th>Sản phẩm</th>
                                                    <th class="text-center">SL</th>
                                                    <th class="text-end">Đơn giá</th>
                                                    <th class="text-end">Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->products as $product)
                                                @php
                                                $imageUrl = $product->image_url ??
                                                'https://placehold.co/60x60?text=No+Img';
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                                            class="rounded border"
                                                            style="width:60px;height:60px;object-fit:cover;">
                                                    </td>
                                                    <td>
                                                        <div class="fw-semibold">{{ $product->name }}</div>
                                                        <div class="text-muted small">ID: {{ $product->id }}</div>
                                                    </td>
                                                    <td class="text-center">{{ $product->pivot->quantity }}</td>
                                                    <td class="text-end">{{ number_format($product->pivot->price) }} đ
                                                    </td>
                                                    <td class="text-end fw-semibold">{{
                                                        number_format($product->pivot->price *
                                                        $product->pivot->quantity) }} đ</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection