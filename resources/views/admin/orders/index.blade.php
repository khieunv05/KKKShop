@extends('master')

@section('content')

<div class="container mt-4">

    <h2>Quản lý đơn hàng</h2>

    <ul class="nav nav-tabs">

        <li class="nav-item">
            <button
                class="nav-link active"
                data-bs-toggle="tab"
                data-bs-target="#pending">
                Chờ xử lý
            </button>
        </li>

        <li class="nav-item">
            <button
                class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#shipping">
                Đang giao
            </button>
        </li>

        <li class="nav-item">
            <button
                class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#completed">
                Hoàn thành
            </button>
        </li>

        <li class="nav-item">
            <button
                class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#cancelled">
                Đã hủy
            </button>
        </li>

    </ul>

    <div class="tab-content mt-3">

        @foreach([
        'pending' => $pending,
        'shipping' => $shipping,
        'completed' => $completed,
        'cancelled' => $cancelled
        ] as $tab => $orders)

        <div
            class="tab-pane fade {{ $tab == 'pending' ? 'show active' : '' }}"
            id="{{ $tab }}">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($orders as $order)

                    <tr>

                        <td>{{ $order->id }}</td>

                        <td>
                            {{ $order->user->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $order->address }}
                        </td>

                        <td>
                            {{ $order->status }}
                        </td>

                        <td>

                            <button
                                class="btn btn-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal{{$order->id}}">
                                Chi tiết
                            </button>

                        </td>

                    </tr>

                    <div
                        class="modal fade"
                        id="modal{{$order->id}}">

                        <div class="modal-dialog modal-lg">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h5>
                                        Đơn hàng #{{$order->id}}
                                    </h5>

                                    <button
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <p>

                                        <strong>Khách:</strong>

                                        {{ $order->user->name ?? '' }}

                                    </p>

                                    <p>

                                        <strong>Địa chỉ:</strong>

                                        {{ $order->address }}

                                    </p>

                                    <table
                                        class="table table-bordered">

                                        <thead>

                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>SL</th>
                                                <th>Giá</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            @php
                                            $total = 0;
                                            @endphp

                                            @foreach($order->products as $product)

                                            @php
                                            $total +=
                                            $product->price *
                                            $product->pivot->quantity;
                                            @endphp

                                            <tr>

                                                <td>
                                                    {{ $product->name }}
                                                </td>

                                                <td>
                                                    {{ $product->pivot->quantity }}
                                                </td>

                                                <td>
                                                    {{ number_format($product->price) }}
                                                </td>

                                            </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                    <h5>

                                        Tổng tiền:
                                        {{ number_format($total + $order->shipping_fee) }}
                                        VNĐ

                                    </h5>

                                    @if($order->status == 'pending')

                                    <form
                                        method="POST"
                                        action="{{ route('admin.orders.status',$order->id) }}">

                                        @csrf

                                        <button
                                            name="status"
                                            value="shipping"
                                            class="btn btn-success">

                                            Chuyển giao hàng

                                        </button>

                                        <button
                                            name="status"
                                            value="cancelled"
                                            class="btn btn-danger">

                                            Hủy đơn

                                        </button>

                                    </form>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </tbody>

            </table>

        </div>

        @endforeach

    </div>

</div>

@endsection