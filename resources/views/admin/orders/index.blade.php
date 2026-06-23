@extends('master')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Quản lý đơn hàng</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Quay lại</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending">Chờ xử lý</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#shipping">Đang giao</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#completed">Hoàn thành</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancelled">Đã hủy</button>
        </li>
    </ul>

    <div class="tab-content mt-3">
        @foreach(['pending' => $pending, 'shipping' => $shipping, 'completed' => $completed, 'cancelled' => $cancelled]
        as $tab => $orders)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $tab }}">
            @if($orders->isEmpty())
            <div class="alert alert-light border">Không có đơn hàng nào.</div>
            @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>SĐT</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->receiver_name ?? $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->phone ?? 'N/A' }}</td>
                        <td>{{ $order->address }}</td>
                        <td>
                            @switch($order->status)
                            @case('pending') <span class="badge bg-warning text-dark">Chờ xử lý</span> @break
                            @case('shipping') <span class="badge bg-info text-dark">Đang giao</span> @break
                            @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                            @case('cancelled') <span class="badge bg-secondary">Đã hủy</span> @break
                            @default <span class="badge bg-light text-dark">{{ $order->status }}</span>
                            @endswitch
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal{{$order->id}}">Chi tiết</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        @endforeach
    </div>
</div>

@foreach(['pending' => $pending, 'shipping' => $shipping, 'completed' => $completed, 'cancelled' => $cancelled] as $tab
=> $orders)
@foreach($orders as $order)
<div class="modal fade" id="modal{{$order->id}}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đơn hàng #{{ $order->id }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Người nhận:</strong> {{ $order->receiver_name ?? $order->user->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>SĐT:</strong> {{ $order->phone ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Địa chỉ:</strong> {{ $order->address }}
                    </div>
                    <div class="col-md-6">
                        <strong>Trạng thái:</strong>
                        @switch($order->status)
                        @case('pending') <span class="badge bg-warning text-dark">Chờ xử lý</span> @break
                        @case('shipping') <span class="badge bg-info text-dark">Đang giao</span> @break
                        @case('completed') <span class="badge bg-success">Hoàn thành</span> @break
                        @case('cancelled') <span class="badge bg-secondary">Đã hủy</span> @break
                        @endswitch
                    </div>
                    <div class="col-md-6">
                        <strong>Thanh toán:</strong>
                        @if($order->is_paid === 'paid')
                        <span class="badge bg-success">Đã thanh toán</span>
                        @else
                        <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                        @endif
                    </div>
                </div>

                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($order->products as $product)
                        @php
                        $subtotal = $product->pivot->price * $product->pivot->quantity;
                        $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td class="text-center">{{ $product->pivot->quantity }}</td>
                            <td class="text-end">{{ number_format($product->pivot->price, 0, ',', '.') }} ₫</td>
                            <td class="text-end">{{ number_format($subtotal, 0, ',', '.') }} ₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Tạm tính:</th>
                            <th class="text-end">{{ number_format($total, 0, ',', '.') }} ₫</th>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Phí ship:</td>
                            <td class="text-end">{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Tổng cộng:</th>
                            <th class="text-end text-danger">{{ number_format($total + $order->shipping_fee, 0, ',',
                                '.') }} ₫</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                @if($order->status == 'pending')
                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="d-inline">
                    @csrf
                    <button name="status" value="shipping" class="btn btn-success">Chuyển giao hàng</button>
                    <button name="status" value="cancelled" class="btn btn-danger">Hủy đơn</button>
                </form>
                @endif
                @if($order->status == 'shipping')
                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="d-inline">
                    @csrf
                    <button name="status" value="completed" class="btn btn-success">Xác nhận hoàn thành</button>
                    <button name="status" value="cancelled" class="btn btn-danger">Hủy đơn</button>
                </form>
                @endif
                <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endforeach
@endsection