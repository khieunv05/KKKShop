@extends('master')

@section('content')
<style>
    .category-title-block {
        display: inline-block;
        background: #d32f2f;
        color: #fff;
        font-weight: 700;
        font-size: 18px;
        padding: 8px 24px 8px 16px;
        clip-path: polygon(0 0, 88% 0, 100% 50%, 88% 100%, 0 100%);
        white-space: nowrap;
    }
    .tag-pill {
        display: inline-block;
        padding: 4px 14px;
        background: #f0f0f0;
        color: #333;
        border-radius: 20px;
        font-size: 13px;
        text-decoration: none;
        transition: background 0.2s;
    }
    .tag-pill:hover {
        background: #ddd;
        color: #000;
    }
    .product-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        background: #fff;
        flex: 0 0 calc((100% - 3 * 16px) / 4);
        min-width: 220px;
        transition: box-shadow 0.25s;
    }
    .product-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .product-card .img-wrap {
        aspect-ratio: 4 / 3;
        overflow: hidden;
        border-radius: 12px 12px 0 0;
    }
    .product-card .img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-card .info-wrap {
        padding: 14px 14px 14px;
    }
    .product-card .prod-name {
        font-size: 14px;
        font-weight: 600;
        color: #444;
        text-transform: uppercase;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.45;
        min-height: 3em;
    }
    .product-card .prod-price {
        font-size: 19px;
        font-weight: 700;
        color: #d32f2f;
    }
    .product-card .prod-price .vnd {
        font-size: 13px;
        font-weight: 400;
    }
    .product-card .discount-badge {
        display: inline-block;
        background: #d32f2f;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        margin-left: 8px;
    }
    .product-card .prod-old-price {
        font-size: 14px;
        color: #999;
        text-decoration: line-through;
    }
    .product-card .add-cart-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        background: none;
        border: none;
        padding: 0;
        font-size: 13px;
        font-weight: 600;
        color: #1a3a5c;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .product-card .add-cart-btn:hover {
        opacity: 0.75;
    }
    .product-card .cart-icon-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #1a3a5c;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .section-divider {
        height: 1px;
        background: #e9ecef;
        margin: 32px 0;
    }
    .carousel-wrap {
        position: relative;
    }
    .carousel-wrap .scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        cursor: pointer;
        z-index: 2;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #666;
        transition: all 0.2s;
    }
    .carousel-wrap:hover .scroll-btn {
        display: flex;
    }
    .carousel-wrap .scroll-btn:hover {
        background: #f5f5f5;
        color: #333;
    }
    .carousel-wrap .scroll-btn.prev {
        left: -18px;
    }
    .carousel-wrap .scroll-btn.next {
        right: -18px;
    }
    .carousel-track {
        display: flex;
        gap: 16px;
        overflow: hidden;
        scroll-behavior: smooth;
        padding: 4px 0;
    }
    .search-result-header {
        font-size: 16px;
        color: #666;
        margin-bottom: 20px;
    }
    .search-result-header strong {
        color: #333;
    }

    @media (max-width: 767.98px) {
        .product-card {
            flex: 0 0 calc((100% - 16px) / 2) !important;
            min-width: 160px !important;
        }
        .carousel-track {
            gap: 10px;
        }
        .carousel-wrap .scroll-btn {
            display: flex !important;
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
        .carousel-wrap .scroll-btn.prev {
            left: -6px;
        }
        .carousel-wrap .scroll-btn.next {
            right: -6px;
        }
        .carousel-item > div {
            height: 180px !important;
            padding: 20px !important;
        }
        .carousel-item h2 {
            font-size: 18px !important;
        }
        .carousel-item p {
            font-size: 14px !important;
        }
        .category-title-block {
            font-size: 14px !important;
            padding: 6px 16px 6px 12px !important;
        }
        .product-card .prod-name {
            font-size: 12px !important;
            min-height: 2.4em !important;
            -webkit-line-clamp: 2 !important;
        }
        .product-card .prod-price {
            font-size: 15px !important;
        }
        .product-card .prod-price .vnd {
            font-size: 11px !important;
        }
        .product-card .prod-old-price {
            font-size: 12px !important;
        }
        .product-card .add-cart-btn {
            font-size: 11px !important;
        }
        .product-card .cart-icon-circle {
            width: 30px;
            height: 30px;
            font-size: 13px;
        }
        .product-card .info-wrap {
            padding: 10px 10px 12px !important;
        }
        .search-result-header {
            font-size: 14px;
        }
    }

    @media (max-width: 575.98px) {
        .product-card {
            flex: 0 0 calc(100% - 0px) !important;
            min-width: 0 !important;
        }
        .carousel-item > div {
            height: 150px !important;
            padding: 14px !important;
        }
        .carousel-item h2 {
            font-size: 16px !important;
        }
        .carousel-item p {
            font-size: 13px !important;
        }
    }
</style>

<div class="container py-3">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div id="bannerCarousel" class="carousel slide mb-4 rounded-4 overflow-hidden" data-bs-ride="carousel" data-bs-interval="4000" style="box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div style="height:320px; background:linear-gradient(135deg,#0a3a5c,#1a6a9c); display:flex; align-items:center; justify-content:center; padding:40px;">
                    <div class="text-center text-white">
                        <div><i class="fas fa-laptop fa-4x mb-3" style="opacity:0.6;"></i></div>
                        <h2 class="fw-bold">PC SHOP — Công nghệ đỉnh cao</h2>
                        <p class="fs-5 mb-0" style="opacity:0.85;">Sản phẩm chính hãng, giá tốt nhất thị trường</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div style="height:320px; background:linear-gradient(135deg,#ff6b35,#e55a25); display:flex; align-items:center; justify-content:center; padding:40px;">
                    <div class="text-center text-white">
                        <div><i class="fas fa-tags fa-4x mb-3" style="opacity:0.6;"></i></div>
                        <h2 class="fw-bold">Khuyến mãi đặc biệt</h2>
                        <p class="fs-5 mb-0" style="opacity:0.85;">Giảm đến 30% cho loạt sản phẩm PC & Laptop chọn lọc</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div style="height:320px; background:linear-gradient(135deg,#2d6a4f,#40916c); display:flex; align-items:center; justify-content:center; padding:40px;">
                    <div class="text-center text-white">
                        <div><i class="fas fa-truck fa-4x mb-3" style="opacity:0.6;"></i></div>
                        <h2 class="fw-bold">Miễn phí vận chuyển</h2>
                        <p class="fs-5 mb-0" style="opacity:0.85;">Giao hàng tận nơi toàn quốc — Đổi trả trong 30 ngày</p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    @if(isset($cat))
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="category-title-block">{{ $cat->name }}</span>
            <a href="{{ route('products.index') }}" class="text-decoration-none ms-auto" style="color:#1a3a5c; font-size:13px;">
                &larr; Tất cả danh mục
            </a>
        </div>
        <div class="row g-3">
            @forelse($products as $product)
                @include('products._card', ['product' => $product])
            @empty
                <div class="col-12">
                    <div class="alert alert-warning mb-0">Không có sản phẩm trong danh mục này.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-4">{{ $products->links() }}</div>

    @elseif(isset($products))
        <div class="search-result-header">
            Kết quả tìm kiếm cho: <strong>"{{ request('q') }}"</strong>
        </div>
        <div class="row g-3">
            @forelse($products as $product)
                @include('products._card', ['product' => $product])
            @empty
                <div class="col-12">
                    <div class="alert alert-warning mb-0">Không tìm thấy sản phẩm nào.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-4">{{ $products->links() }}</div>

    @elseif(isset($categoryGroups))
        @php $first = true; @endphp
        @foreach($categoryGroups as $group)
            @if(!$group->products->count()) @continue @endif
            @if(!$first) <div class="section-divider"></div> @endif
            @php $first = false; @endphp

            <div class="category-section mb-4">
                <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                    <span class="category-title-block">{{ $group->name }}</span>
                    <a href="{{ route('products.index', ['category' => $group->id]) }}" class="ms-auto text-decoration-none" style="color:#1a3a5c; font-size:13px; white-space:nowrap;">
                        Xem tất cả &gt;&gt;
                    </a>
                </div>

                <div class="carousel-wrap">
                    <button class="scroll-btn prev" type="button"><i class="fas fa-chevron-left"></i></button>
                    <button class="scroll-btn next" type="button"><i class="fas fa-chevron-right"></i></button>
                    <div class="carousel-track">
                        @foreach($group->products as $product)
                            @include('products._card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.carousel-wrap').forEach(function(wrap) {
        var track = wrap.querySelector('.carousel-track');
        var prev = wrap.querySelector('.scroll-btn.prev');
        var next = wrap.querySelector('.scroll-btn.next');
        var scrollAmount = 0;

        function updateButtons() {
            var maxScroll = track.scrollWidth - track.clientWidth;
            prev.style.display = track.scrollLeft > 4 ? 'flex' : 'none';
            next.style.display = track.scrollLeft < maxScroll - 4 ? 'flex' : 'none';
            if (maxScroll <= 0) { prev.style.display = 'none'; next.style.display = 'none'; }
        }

        prev.addEventListener('click', function() {
            var card = track.querySelector('.product-card');
            var step = card ? card.offsetWidth + 12 : 200;
            track.scrollBy({ left: -step, behavior: 'smooth' });
            setTimeout(updateButtons, 350);
        });

        next.addEventListener('click', function() {
            var card = track.querySelector('.product-card');
            var step = card ? card.offsetWidth + 12 : 200;
            track.scrollBy({ left: step, behavior: 'smooth' });
            setTimeout(updateButtons, 350);
        });

        track.addEventListener('scroll', updateButtons);
        setTimeout(updateButtons, 100);
        window.addEventListener('resize', updateButtons);
    });
});
</script>
@endsection
