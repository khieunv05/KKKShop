<div class="product-card">
    <div class="img-wrap">
        @php
            $imgUrl = null;
            foreach ($product->categories as $pc) {
                if (isset($categoryImageMap[$pc->id])) {
                    $imgUrl = $categoryImageMap[$pc->id];
                    break;
                }
            }
        @endphp
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" loading="lazy">
        @elseif($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
        @else
            <div style="width:100%;height:100%;background:#f5f5f5;display:flex;align-items:center;justify-content:center;color:#bbb;font-size:32px;">
                <i class="fa-solid fa-image"></i>
            </div>
        @endif
    </div>
    <div class="info-wrap">
        <div class="prod-name">{{ $product->name }}</div>
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
        @if((int) $product->stock > 0)
            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2" style="margin-bottom:0;">
                @csrf
                <button class="add-cart-btn" type="submit">
                    <span class="cart-icon-circle"><i class="fa-solid fa-cart-shopping"></i></span>
                    THÊM VÀO GIỎ
                </button>
            </form>
        @else
            <div class="mt-2">
                <span class="add-cart-btn" style="cursor:default;opacity:0.5;">
                    <span class="cart-icon-circle" style="background:#999;"><i class="fa-solid fa-cart-shopping"></i></span>
                    HẾT HÀNG
                </span>
            </div>
        @endif
    </div>
</div>
