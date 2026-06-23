<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
        :root {
            --primary-color: #0a3a5c;
            --secondary-color: #ff6b35;
        }

        body {
            background-color: #f5f5f5;
        }

        /* Hover effects */
        .header-top a {
            transition: color 0.3s;
        }

        .header-top a:hover {
            color: var(--secondary-color);
        }

        .header-nav-item {
            transition: color 0.3s;
        }

        .header-nav-item:hover {
            color: var(--secondary-color);
        }

        .category-btn {
            transition: background-color 0.3s;
        }

        .category-btn:hover {
            background-color: #062a44;
        }

        .category-nav a {
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
        }

        .category-nav a:hover {
            color: var(--secondary-color);
            border-bottom-color: var(--secondary-color);
        }

        .search-form input:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .search-form button:hover {
            background-color: #e55a25;
        }

        .suggestions-dropdown {
            display: none;
            position: fixed;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 1050;
            max-height: 400px;
            overflow-y: auto;
        }
        .suggestions-dropdown.active {
            display: block;
        }
        .suggestion-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            gap: 12px;
        }
        .suggestion-item:last-child {
            border-bottom: none;
        }
        .suggestion-item:hover,
        .suggestion-item.active {
            background-color: #f8f9fa;
        }
        .suggestion-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            background: #f0f0f0;
        }
        .suggestion-item .suggestion-info {
            flex: 1;
            min-width: 0;
        }
        .suggestion-item .suggestion-name {
            font-size: 14px;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .suggestion-item .suggestion-price {
            font-size: 13px;
            color: #ff6b35;
            font-weight: 600;
        }
        .suggestion-no-results {
            padding: 12px;
            text-align: center;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <div style="background-color: #0a3a5c; color: white; padding: 12px 0; font-size: 13px;">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <a href="#" class="text-white text-decoration-none me-4">
                        <i class="fas fa-phone"></i> Hotline: 098.655.2233
                    </a>
                    <a href="#" class="text-white text-decoration-none">
                        <i class="fas fa-map-marker-alt"></i> Hệ thống cửa hàng
                    </a>
                </div>
                <div class="col-auto d-flex align-items-center gap-3">
                    @guest
                        <a href="{{ route('viewRegister') }}" class="text-white text-decoration-none me-3">Đăng kí</a>
                        <span class="text-white me-3">|</span>
                        <a href="{{ route('viewLogin') }}" class="text-white text-decoration-none">Đăng nhập</a>
                    @else
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.products.index') }}" class="text-white text-decoration-none me-3">Quản trị</a>
                            <span class="text-white category-btn">|</span>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="text-white text-decoration-none">Thông tin tài khoản</a>
                        <span class="text-white me-3"> |</span>
                         <form method="POST" action="{{ route('logout') }}" class="d-inline-block">
                             @csrf
                            <button class="dropdown-item" type="submit">Đăng xuất</button>
                            </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top" style="background-color: white; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); padding: 12px 0;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="{{ url('/') }}" style="font-size: 20px; color: #0a3a5c !important; white-space: nowrap;">
                <i class="fas fa-cube" style="font-size: 28px; color: #ff6b35; margin-right: 8px;"></i>PC SHOP
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Search Bar -->
                <form class="d-lg-flex d-none flex-grow-1 mx-4" action="{{ url('/search') }}" method="GET">
                    <div class="input-group" style="border-radius: 8px;">
                        <input class="form-control search-input" type="text" name="q" id="searchInputDesktop" placeholder="Tìm kiếm sản phẩm..." autocomplete="off" required style="background-color: #f8f9fa; border-color: #e9ecef;">
                        <button class="btn" type="submit" style="background-color: #ff6b35; color: white; border: none;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Right Menu -->
                <div class="ms-auto d-flex gap-4">
                    <a href="#" class="text-decoration-none text-dark text-center" style="font-size: 12px; white-space: nowrap;">
                        <i class="fas fa-phone-alt d-block" style="font-size: 20px; margin-bottom: 4px;"></i>
                        <span>Hotline mua hàng</span>
                    </a>
                    <a href="#" class="text-decoration-none text-dark text-center" style="font-size: 12px; white-space: nowrap;">
                        <i class="fas fa-cube d-block" style="font-size: 20px; margin-bottom: 4px;"></i>
                        <span>Cấu hình PC</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="text-decoration-none text-dark text-center" style="font-size: 12px; white-space: nowrap;">
                        <i class="fas fa-shopping-cart d-block" style="font-size: 20px; margin-bottom: 4px;"></i>
                        <span>Giỏ hàng ({{ count(session()->get('cart', [])) }})</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Search -->
    <div class="d-lg-none bg-white px-3 py-2 border-top">
        <form action="{{ url('/search') }}" method="GET">
            <div class="input-group" style="border-radius: 8px;">
                <input class="form-control form-control-sm search-input" type="text" name="q" id="searchInputMobile" placeholder="Tìm kiếm sản phẩm..." autocomplete="off" required style="background-color: #f8f9fa;">
                <button class="btn btn-sm" style="background-color: #ff6b35; color: white; border: none;" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Category Menu -->
    <div style="background-color: #f8f9fa; border-top: 1px solid #e9ecef;" class="sticky-top" style="top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <button class="btn btn-sm category-btn fw-bold" type="button" style="background-color: #0a3a5c; color: white; font-size: 13px; white-space: nowrap;">
                        <i class="fas fa-bars me-2"></i>DANH MỤC SẢN PHẨM
                    </button>
                </div>
                <div class="col">
                    <div class="category-nav d-flex gap-3">
                        @php $navCategories = \App\Models\Category::orderBy('name')->get(); @endphp
                        @foreach($navCategories as $cat)
                            <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="text-decoration-none text-dark" style="font-size: 13px; padding-top: 15px; padding-bottom: 12px;">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        @yield('content')
    </div>

    <div class="suggestions-dropdown" id="suggestionsDropdown"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdown = document.getElementById('suggestionsDropdown');
        var searchInputs = [
            document.getElementById('searchInputDesktop'),
            document.getElementById('searchInputMobile')
        ].filter(Boolean);

        if (!searchInputs.length || !dropdown) return;

        var debounceTimer;
        var activeIndex = -1;
        var currentInput = null;
        var currentItems = [];

        function positionDropdown(input) {
            var rect = input.getBoundingClientRect();
            dropdown.style.top = rect.bottom + 'px';
            dropdown.style.left = rect.left + 'px';
            dropdown.style.width = rect.width + 'px';
        }

        function hideDropdown() {
            dropdown.classList.remove('active');
            dropdown.innerHTML = '';
            activeIndex = -1;
            currentInput = null;
            currentItems = [];
        }

        function renderSuggestions(products) {
            dropdown.innerHTML = '';

            if (!products.length) {
                dropdown.innerHTML = '<div class="suggestion-no-results">Không tìm thấy sản phẩm</div>';
                dropdown.classList.add('active');
                currentItems = [];
                activeIndex = -1;
                return;
            }

            products.forEach(function(product, index) {
                var item = document.createElement('div');
                item.className = 'suggestion-item';
                item.dataset.index = index;

                var imgSrc = product.image_url || 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect fill="#e9ecef" width="40" height="40"/><text fill="#999" font-size="16" x="50%" y="50%" text-anchor="middle" dominant-baseline="central">?</text></svg>');

                item.innerHTML =
                    '<img src="' + imgSrc + '" alt="" loading="lazy" onerror="this.src=\'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><rect fill="#e9ecef" width="40" height="40"/><text fill="#999" font-size="16" x="50%" y="50%" text-anchor="middle" dominant-baseline="central">?</text></svg>') + '\'">' +
                    '<div class="suggestion-info">' +
                        '<div class="suggestion-name">' + escapeHtml(product.name) + '</div>' +
                        '<div class="suggestion-price">' + (product.price ? Number(product.price).toLocaleString('vi-VN') + '₫' : '') + '</div>' +
                    '</div>';

                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    currentInput.value = product.name;
                    hideDropdown();
                    currentInput.closest('form').submit();
                });

                dropdown.appendChild(item);
            });

            dropdown.classList.add('active');
            currentItems = dropdown.querySelectorAll('.suggestion-item');
            activeIndex = -1;
        }

        function escapeHtml(str) {
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }

        function fetchSuggestions(keyword) {
            if (keyword.length < 1) {
                hideDropdown();
                return;
            }

            fetch('/api/suggestions?q=' + encodeURIComponent(keyword))
                .then(function(res) { return res.json(); })
                .then(function(products) {
                    if (currentInput && document.activeElement === currentInput) {
                        renderSuggestions(products);
                    }
                })
                .catch(function() {
                    hideDropdown();
                });
        }

        searchInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                var value = this.value.trim();
                clearTimeout(debounceTimer);

                if (value.length < 1) {
                    hideDropdown();
                    return;
                }

                currentInput = this;
                positionDropdown(this);

                debounceTimer = setTimeout(function() {
                    fetchSuggestions(value);
                }, 300);
            });

            input.addEventListener('keydown', function(e) {
                if (!dropdown.classList.contains('active') || !currentItems.length) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (activeIndex < currentItems.length - 1) {
                        if (activeIndex >= 0) currentItems[activeIndex].classList.remove('active');
                        activeIndex++;
                        currentItems[activeIndex].classList.add('active');
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (activeIndex > 0) {
                        currentItems[activeIndex].classList.remove('active');
                        activeIndex--;
                        currentItems[activeIndex].classList.add('active');
                    } else if (activeIndex === 0) {
                        currentItems[activeIndex].classList.remove('active');
                        activeIndex = -1;
                    }
                } else if (e.key === 'Enter') {
                    if (activeIndex >= 0 && currentItems[activeIndex]) {
                        e.preventDefault();
                        var name = currentItems[activeIndex].querySelector('.suggestion-name').textContent;
                        this.value = name;
                        hideDropdown();
                        this.closest('form').submit();
                    }
                } else if (e.key === 'Escape') {
                    hideDropdown();
                }
            });

            input.addEventListener('blur', function() {
                setTimeout(function() {
                    hideDropdown();
                }, 200);
            });

            input.addEventListener('focus', function() {
                var value = this.value.trim();
                if (value.length >= 1) {
                    currentInput = this;
                    positionDropdown(this);
                    fetchSuggestions(value);
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#suggestionsDropdown') && !e.target.closest('.search-input')) {
                hideDropdown();
            }
        });

        window.addEventListener('scroll', function() {
            if (dropdown.classList.contains('active') && currentInput) {
                positionDropdown(currentInput);
            }
        }, true);

        window.addEventListener('resize', function() {
            if (dropdown.classList.contains('active') && currentInput) {
                positionDropdown(currentInput);
            }
        });
    });
    </script>

    @yield('scripts')
</body>
</html>