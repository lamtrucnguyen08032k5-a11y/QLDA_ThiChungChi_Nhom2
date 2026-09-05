# Hệ thống Đăng ký & Tổ chức thi Chứng chỉ HVNH — project Laravel đầy đủ

Đây là **cấu trúc project Laravel hoàn chỉnh** (giống ảnh bạn gửi: có `app/`, `bootstrap/`, `config/`, `database/`,
`public/`, `resources/`, `routes/`, `storage/`, `tests/`, `artisan`, `composer.json`, `.env`...), đã tích hợp sẵn
toàn bộ mã nguồn nghiệp vụ (4 role, 9 module) + middleware phân quyền + cấu hình domain email + `.env` mẫu.

## Vì sao chưa mở lên chạy được ngay

Thư mục **`vendor/`** (chứa bản thân framework Laravel và các thư viện) **chưa có** trong gói này, vì môi trường tạo
ra gói không truy cập được Composer/Packagist để tải về. Đây là thư mục nặng (~50-100MB) luôn được `.gitignore` và
không bao giờ chia sẻ qua zip trong thực tế — ai cũng tự tải bằng lệnh `composer install`.

## Cách chạy (chỉ 3 lệnh)

```bash
# 1. Vào thư mục project vừa giải nén
cd hvnh-exam-app

# 2. Cài toàn bộ thư viện (tạo thư mục vendor/) — cần PHP >= 8.2 và Composer đã cài trên máy
composer install

# 3. Tạo APP_KEY (khoá mã hoá của Laravel)
php artisan key:generate

# 4. Chạy migration + tạo dữ liệu mẫu (mặc định dùng SQLite, không cần cài MySQL)
php artisan migrate --seed

# 5. Chạy server
php artisan serve
```

Mở trình duyệt: **http://127.0.0.1:8000**

## Tài khoản mẫu (sau khi `migrate --seed`)

| Vai trò | Email | Mật khẩu |
|---|---|---|
| Admin (Phòng khảo thí) | admin@hvnh.edu.vn | Admin@123 |
| Khoa CNTT | khoa.cntt@hvnh.edu.vn | Khoa@123 |
| Khoa Ngoại ngữ | khoa.nn@hvnh.edu.vn | Khoa@123 |
| Giảng viên (Khoa CNTT) | giangvien.cntt@hvnh.edu.vn | GiangVien@123 |

Sinh viên tự đăng ký tại `/dang-ky` bằng email đã có trong "Kho email Sinh viên" — đã seed sẵn 1 email mẫu
`sv22a4000001@hvnh.edu.vn` để bạn test (Admin quản lý danh sách này trong menu **Kho email Sinh viên**).

**Đổi mật khẩu mặc định trước khi dùng thật.**

## Nếu muốn dùng MySQL thay vì SQLite

Mở file `.env`, sửa:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hvnh_exam
DB_USERNAME=root
DB_PASSWORD=
```
Tạo sẵn database rỗng tên `hvnh_exam` trong phpMyAdmin/MySQL Workbench, rồi chạy lại bước `migrate --seed`.

## Cấu hình gửi email thật (không bắt buộc để chạy thử)

Mặc định `.env` để `MAIL_MAILER=log` — email đăng ký/xác minh/thông báo sẽ được ghi vào `storage/logs/laravel.log`
thay vì gửi thật (tiện để test luồng đăng ký/quên mật khẩu mà không cần SMTP). Muốn gửi thật, đổi
`MAIL_MAILER=smtp` và điền `MAIL_USERNAME`/`MAIL_PASSWORD` trong `.env`.

## Cập nhật lần này (giao diện + luồng đăng ký/thanh toán)

1. **Logo & tông màu xanh-trắng**: `public/images/logo.svg` + `public/css/theme.css` áp dụng cho toàn bộ giao diện.
2. **Admin/Khoa/Giảng viên**: vẫn giữ layout sidebar bên trái (`layouts/app.blade.php`), chỉ đổi màu + thêm logo.
3. **Sinh viên**: layout mới kiểu cổng thông tin (top bar liên hệ + navbar ngang) tại `layouts/sinhvien.blade.php`.
4. **Đăng ký dự thi** nay là quy trình 4 bước:
   - Bước 1 (`/sinh-vien/dang-ky-thi/{lichthi}/buoc-1`): form 2 cột — trái là thông tin cá nhân, phải là upload ảnh hồ sơ/CCCD.
   - Bước 2: trang xác nhận thông tin (bảng xanh, xem lại ảnh đã tải lên).
   - Bước 3: chọn phương thức thanh toán **VNPAY** hoặc **NAPAS**.
   - Bước 4: trang trạng thái thanh toán + thông tin đăng ký.
5. **Thanh toán VNPAY/NAPAS**: xem mục riêng bên dưới — **bắt buộc chạy thêm 1 lệnh migrate** vì có bảng/cột mới.
6. **Email tự động**: gửi khi thanh toán lệ phí thành công, và khi Admin duyệt / từ chối / yêu cầu bổ sung hồ sơ.

### Lệnh cần chạy thêm sau khi giải nén bản cập nhật này

```bash
composer install
php artisan migrate          # thêm các cột hồ sơ + thanh toán vào bảng dang_kys
php artisan storage:link     # bắt buộc, để ảnh hồ sơ/CCCD upload lên hiển thị được (link storage/app/public -> public/storage)
php artisan serve
```

### Cấu hình thanh toán VNPAY / NAPAS

Cả 2 phương thức đều đi qua cổng **VNPAY** (VNPAY là cổng trung gian, NAPAS là liên minh chuyển mạch thẻ nội địa mà
VNPAY kết nối tới): phương thức "VNPAY" dùng ví/VNPAY-QR/thẻ quốc tế, phương thức "NAPAS" ép luồng thẻ ATM nội địa
(`vnp_BankCode=VNBANK`). Cấu hình trong `.env`:

```
VNPAY_TMN_CODE=       # Mã Terminal do VNPAY cấp khi đăng ký merchant sandbox tại https://sandbox.vnpayment.vn
VNPAY_HASH_SECRET=    # Chuỗi bí mật để tạo/kiểm tra checksum, VNPAY cấp cùng lúc với TMN Code
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL="${APP_URL}/thanh-toan/vnpay/return"
```

**Nếu chưa có tài khoản merchant thật** (để trống `VNPAY_TMN_CODE`/`VNPAY_HASH_SECRET` như mặc định), hệ thống tự
động chuyển sang **cổng thanh toán mô phỏng** (`app/Http/Controllers/SinhVien/ThanhToanController.php@moPhong`) —
vẫn demo được trọn vẹn luồng "chọn phương thức → thanh toán → nhận email → trạng thái Chờ duyệt" mà không cần đăng
ký gì thêm, rất phù hợp để báo cáo/bảo vệ đồ án. Khi điền đủ 2 biến trên, hệ thống sẽ tự chuyển sang gọi VNPAY thật.



1. Xác thực & Tài khoản (đăng ký qua whitelist email trường + xác minh email, đăng nhập, quên mật khẩu, Admin tạo Khoa/Giảng viên)
2. Quản lý kỳ thi (lịch thi, mã ca thi tự sinh)
3. Kho đề thi (thêm câu hỏi thủ công hoặc import CSV)
4. Đăng ký thi (đăng ký/huỷ/tra cứu, Admin duyệt/từ chối)
5. Tổ chức thi (bắt đầu ca thi, nhập mã vào thi, làm bài có đếm ngược, tự chấm trắc nghiệm)
6. Chấm thi (Giảng viên chấm tự luận, Khoa/Admin theo dõi tiến độ)
7. Kết quả (công bố + tra cứu chi tiết)
8. Phúc khảo (yêu cầu, xử lý, điều chỉnh điểm)
9. Chứng nhận (đăng ký nhận, Admin duyệt & cấp số chứng nhận)

## Giả định thiết kế (tài liệu gốc chưa nêu cụ thể ở Chương 3/4)

- Ngưỡng điểm đạt để đăng ký chứng nhận: tạm đặt **50/100** (hằng số `DIEM_DAT` trong
  `app/Http/Controllers/SinhVien/ChungNhanController.php` — sửa theo quy chế thật).
- "Kho mail HVNH" hiện thực bằng bảng `sv_whitelists` do Admin import trước (CSV), vì hệ thống không có quyền
  truy cập trực tiếp hệ thống email/SIS thật của trường.
- Import câu hỏi dùng file **CSV** với mẫu cột cố định (xem trong màn hình "Tạo đề thi"). Có thể nâng cấp đọc
  `.xlsx` trực tiếp bằng `composer require maatwebsite/excel` nếu cần.
- Giao diện dùng Bootstrap 5 + Chart.js qua CDN (không cần `npm install`/build gì thêm).
