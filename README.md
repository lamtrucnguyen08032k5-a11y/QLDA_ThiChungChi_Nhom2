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

## Danh sách 9 module đã cài đặt

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
