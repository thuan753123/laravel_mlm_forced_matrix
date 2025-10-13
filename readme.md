# Laravel MLM Forced Matrix

Hệ thống MLM (Multi-Level Marketing) sử dụng mô hình Forced Matrix với Laravel 11.

## Cài đặt

1. **Cài đặt dependencies:**

   ```bash
   cd mlm-matrix
   composer install
   ```

2. **Tạo database:**

   - Tạo database tên `laravel_mlm` trong MySQL
   - Cập nhật thông tin database trong file `.env`

3. **Chạy migration:**

   ```bash
   php artisan migrate
   ```

4. **Tạo storage link:**

   ```bash
   php artisan storage:link
   ```

5. **Chạy server:**
   ```bash
   php artisan serve
   ```

## Tính năng

- Hệ thống MLM Forced Matrix
- Quản lý người dùng và nodes
- Tính toán hoa hồng
- Giao diện quản trị
- API endpoints

## Cấu trúc Database

- `users` - Thông tin người dùng
- `nodes` - Cấu trúc matrix
- `orders` - Đơn hàng
- `commissions` - Hoa hồng
- `cycles` - Chu kỳ thanh toán
- `payouts` - Thanh toán
- `settings` - Cấu hình hệ thống

## Công nghệ sử dụng

- Laravel 11
- MySQL
- Laravel Sanctum (API authentication)
- Kalnoy Nestedset (Matrix structure)
