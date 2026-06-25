<div class="product-card">
    <div class="img-wrap">
        @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
        @else
            <div style="width:100%;height:100%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;color:#bbb;font-size:32px;">
                <i class="fa-solid fa-image"></i>
            </div>
        @endif
    </div>
    <div class="info-wrap">
        <div class="prod-name">{{ $product->name }}</div>
        @if($product->description)
            <div class="text-muted small mt-1" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.3;">{{ $product->description }}</div>
        @endif
        <div class="d-flex align-items-center mt-2 flex-wrap" style="gap:4px;">
            <span class="prod-price">{{ number_format($product->price, 0, ',', '.') }} <span class="vnd">VNĐ</span></span>
            @if($product->old_price && $product->old_price > $product->price)
                @php $discount = round((1 - $product->price / $product->old_price) * 100); @endphp
                <span class="discount-badge">-{{ $discount }}%</span>
            @endif
        </div>
        @if($product->old_price && $product->old_price > $product->price)
            <div class="prod-old-price">{{ number_format($product->old_price, 0, ',', '.') }} VNĐ</div>
        @endif
        <div class="d-flex align-items-center gap-2 mt-2">
            @if((int) $product->stock > 0)
                <span class="small text-muted">SL: {{ $product->stock }}</span>
                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin-bottom:0;">
                    @csrf
                    <button class="add-cart-btn" type="submit">
                        <span class="cart-icon-circle"><i class="fa-solid fa-cart-shopping"></i></span>
                        THÊM VÀO GIỎ
                    </button>
                </form>
            @else
                <span class="text-danger small fw-semibold">Hết hàng</span>
                <span class="add-cart-btn" style="cursor:default;opacity:0.5;">
                    <span class="cart-icon-circle" style="background:#999;"><i class="fa-solid fa-cart-shopping"></i></span>
                    HẾT HÀNG
                </span>
            @endif
        </div>
    </div>
</div>
