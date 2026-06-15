@extends('master')

@section('content')
<div class="admin-store-page py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <p class="text-uppercase text-muted mb-1" style="letter-spacing: .18em; font-size: 12px;">Admin panel</p>
                    <h2 class="mb-0">Doanh thu theo tháng</h2>
                </div>
                <a href="{{ route('admin.add_product') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.revenue') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label for="month" class="form-label">Chọn tháng</label>
                            <input type="month" name="month" id="month" class="form-control" value="{{ $selectedMonth }}">
                        </div>
                        <div class="col-12 col-md-auto">
                            <button type="submit" class="btn btn-primary">Xem doanh thu</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Tháng đang xem</p>
                            <h4 class="mb-0">{{ $selectedMonth }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Tổng doanh thu</p>
                            <h4 class="mb-0">{{ number_format($monthlyRevenue) }} đ</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Số đơn trong tháng</p>
                            <h4 class="mb-0">{{ $orders->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h5 class="mb-0">Doanh thu theo sản phẩm trong tháng {{ $selectedMonth }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Số lượng</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td class="text-end">{{ $product->total_quantity }}</td>
                                        <td class="text-end">{{ number_format($product->total_revenue) }} đ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Không có dữ liệu trong tháng này.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection