@extends('master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Giỏ hàng</h2>
    @if (count($cartItems) > 0)
    <form action="{{ route('cart.clear') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger">Xóa tất cả</button>
    </form>
    @endif
</div>

@if (count($cartItems) === 0)
<div class="alert alert-info">Giỏ hàng đang trống.</div>
@else
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th style="width: 40px;">
                    <input type="checkbox" id="select-all" checked>
                </th>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th style="width: 180px;">Số lượng</th>
                <th>Tạm tính</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
            @php
            $inStock = $item['stock'] > 0;
            $maxQty = $item['stock'] > 0 ? $item['stock'] : 1;
            @endphp
            <tr>
                <td>
                    <input type="checkbox" class="cart-select" checked {{ $inStock ? '' : 'disabled' }}>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        @if (!empty($item['image']))
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px;">
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; border-radius: 8px;">
                            <i class="fa-solid fa-computer text-secondary"></i>
                        </div>
                        @endif
                        <div>
                            <div class="fw-semibold">{{ $item['name'] }}</div>
                            <div class="text-secondary small">Kho: {{ $item['stock'] }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="price-value" data-price="{{ $item['price'] }}">{{ number_format($item['price'], 0, ',', '.') }} đ</span>
                </td>
                <td>
                    <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center gap-2 qty-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-btn" data-step="-1" {{ $inStock ? '' : 'disabled' }}>
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input
                            type="number"
                            name="quantity"
                            class="form-control text-center qty-input"
                            min="1"
                            max="{{ $maxQty }}"
                            data-stock="{{ $item['stock'] }}"
                            data-price="{{ $item['price'] }}"
                            value="{{ $item['quantity'] }}"
                            {{ $inStock ? '' : 'disabled' }}>
                        <button type="button" class="btn btn-outline-secondary btn-sm qty-btn" data-step="1" {{ $inStock ? '' : 'disabled' }}>
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>
                </td>
                <td class="line-total" data-value="{{ $item['price'] * $item['quantity'] }}">
                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ
                </td>
                <td>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                        <button type="submit" class="btn btn-outline-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    <div class="card p-3" style="min-width: 280px;">
        <div class="d-flex justify-content-between mb-2">
            <span>Tổng đã chọn</span>
            <strong id="selected-total">{{ number_format($total, 0, ',', '.') }} đ</strong>
        </div>
        <div class="d-flex justify-content-between mb-2 text-secondary">
            <span>Tổng tất cả</span>
            <span id="all-total">{{ number_format($total, 0, ',', '.') }} đ</span>
        </div>
        <a class="btn btn-success w-100" href="{{ route('cart.checkout') }}">Thanh toán</a>
    </div>
</div>

<script>
    const formatter = new Intl.NumberFormat('vi-VN');
    const selectAll = document.getElementById('select-all');
    const selectedTotalEl = document.getElementById('selected-total');
    const allTotalEl = document.getElementById('all-total');

    function formatMoney(value) {
        return `${formatter.format(value)} đ`;
    }

    function computeTotals() {
        let selectedTotal = 0;
        let allTotal = 0;

        document.querySelectorAll('tbody tr').forEach((row) => {
            const qtyInput = row.querySelector('.qty-input');
            const price = parseFloat(qtyInput?.dataset.price || 0);
            const qty = parseInt(qtyInput?.value || 0, 10);
            const lineTotal = price * qty;
            const lineTotalCell = row.querySelector('.line-total');
            if (lineTotalCell) {
                lineTotalCell.dataset.value = lineTotal;
                lineTotalCell.textContent = formatMoney(lineTotal);
            }

            allTotal += lineTotal;

            const checkbox = row.querySelector('.cart-select');
            if (checkbox && checkbox.checked) {
                selectedTotal += lineTotal;
            }
        });

        if (selectedTotalEl) selectedTotalEl.textContent = formatMoney(selectedTotal);
        if (allTotalEl) allTotalEl.textContent = formatMoney(allTotal);
    }

    function clampQuantity(input) {
        const stock = parseInt(input.dataset.stock || 0, 10);
        if (!stock) return;
        if (parseInt(input.value, 10) > stock) {
            input.value = stock;
            if (typeof showToast === 'function') {
                showToast('Không thể vượt quá tồn kho.', 'danger');
            }
        }
        if (parseInt(input.value, 10) < 1) {
            input.value = 1;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', () => {
            document.querySelectorAll('.cart-select').forEach((checkbox) => {
                if (!checkbox.disabled) {
                    checkbox.checked = selectAll.checked;
                }
            });
            computeTotals();
        });
    }

    document.querySelectorAll('.cart-select').forEach((checkbox) => {
        checkbox.addEventListener('change', computeTotals);
    });

    document.querySelectorAll('.qty-input').forEach((input) => {
        input.addEventListener('input', () => {
            clampQuantity(input);
            computeTotals();
        });
    });

    document.querySelectorAll('.qty-btn').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const form = btn.closest('.qty-form');
            const input = form.querySelector('.qty-input');
            const step = parseInt(btn.dataset.step, 10);
            const newValue = parseInt(input.value, 10) + step;
            input.value = newValue;
            clampQuantity(input);
            computeTotals();

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                });

                const data = await response.json();
                if (!response.ok) {
                    showToast(data.message || 'Không thể cập nhật số lượng.', 'danger');
                    return;
                }

                const countEl = document.getElementById('cart-count');
                if (countEl && typeof data.cartCount !== 'undefined') {
                    countEl.textContent = data.cartCount;
                }

                if (typeof data.lineTotal !== 'undefined') {
                    const lineTotalCell = form.closest('tr').querySelector('.line-total');
                    if (lineTotalCell) {
                        lineTotalCell.textContent = formatMoney(data.lineTotal);
                    }
                }

                if (typeof data.total !== 'undefined' && allTotalEl) {
                    allTotalEl.textContent = formatMoney(data.total);
                }
            } catch (error) {
                showToast('Không thể cập nhật số lượng.', 'danger');
            }
        });
    });

    computeTotals();
</script>
@endif
@endsection