# KKKShop - Web bán hàng PC

Dự án web bán linh kiện và PC hoàn chỉnh, xây dựng bằng Laravel 12.

## Yêu cầu

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL hoặc SQLite

## Cài đặt & Chạy

### 1. Clone dự án

```bash
git clone <repo-url>
cd KKKShop
```

### 2. Cài đặt dependencies

```bash
composer install
npm install && npm run build
```

### 3. Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

Sửa file `.env`:

- **Dùng SQLite (nhanh, không cần cài DB):**
  ```
  DB_CONNECTION=sqlite
  ```
  ```bash
  touch database/database.sqlite
  ```

- **Hoặc dùng MySQL:** tạo database trước, sửa `.env`:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=ten_database
  DB_USERNAME=root
  DB_PASSWORD=
  ```

### 4. Tạo storage link

```bash
php artisan storage:link
```

### 5. Chạy migration & seed dữ liệu mẫu

```bash
php artisan migrate --seed
```

### 6. Chạy server

```bash
php artisan serve
```

Mở trình duyệt: `http://localhost:8000`

## Tài khoản mẫu

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Admin | admin@example.com | password |
| Người dùng | test@example.com | password |

## Tính năng chính

- **Trang chủ:** Xem danh sách sản phẩm, lọc theo danh mục, tìm kiếm
- **Admin:** Quản lý sản phẩm, danh mục, đơn hàng (CRUD)
- **Người dùng:** Đăng ký, đăng nhập, xem đơn hàng
- **Kho hàng:** Tự động trừ tồn kho khi thanh toán, hoàn lại khi hủy đơn
