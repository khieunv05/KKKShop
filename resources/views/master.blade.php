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
                        <span class="text-white me-3">{{ number_format(auth()->user()->current_balance) }} VND</span>
                        <span class="text-white me-3">|</span>
                        <a href="{{ route('profile.edit') }}" class="text-white text-decoration-none">Thông tin tài khoản</a>
                        <span class="text-white me-3"> |</span>
                        <a href="{{ route('user.add-money') }}" class="text-white text-decoration-none">Nạp tiền</a>
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
                    <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                        <input class="form-control" type="text" name="q" placeholder="Tìm kiếm sản phẩm..." required style="background-color: #f8f9fa; border-color: #e9ecef;">
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
                    <a href="#" class="text-decoration-none text-dark text-center" style="font-size: 12px; white-space: nowrap;">
                        <i class="fas fa-shopping-cart d-block" style="font-size: 20px; margin-bottom: 4px;"></i>
                        <span>Giỏ hàng (0)</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Search -->
    <div class="d-lg-none bg-white px-3 py-2 border-top">
        <form action="{{ url('/search') }}" method="GET">
            <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                <input class="form-control form-control-sm" type="text" name="q" placeholder="Tìm kiếm sản phẩm..." required style="background-color: #f8f9fa;">
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
                        <a href="#" class="text-decoration-none text-dark" style="font-size: 13px; padding-top: 15px; padding-bottom: 12px;">PC Gaming</a>
                        <a href="#" class="text-decoration-none text-dark" style="font-size: 13px; padding-top: 15px; padding-bottom: 12px;">PC Workstation</a>
                        <a href="#" class="text-decoration-none text-dark" style="font-size: 13px; padding-top: 15px; padding-bottom: 12px;">PC AMD Gaming</a>
                        <a href="#" class="text-decoration-none text-dark" style="font-size: 13px; padding-top: 15px; padding-bottom: 12px;">PC Mini</a>
                        <a href="#" class="text-decoration-none text-dark" style="font-size: 13px; padding-top: 15px; padding-bottom: 12px;">PC Văn Phòng</a>
                        <a href="#" class="text-decoration-none text-dark" style="font-size: 13px; padding-top: 15px; padding-bottom: 12px;">Linh kiện máy tính</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>