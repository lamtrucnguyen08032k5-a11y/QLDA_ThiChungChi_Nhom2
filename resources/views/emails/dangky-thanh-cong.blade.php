<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, Helvetica, sans-serif; background:#f2f5fa; margin:0; padding:24px;">
<div style="max-width:600px;margin:0 auto;background:#fff;border-radius:10px;overflow:hidden;border:1px solid #dbe4f0;">
    <div style="background:#0d2b57;padding:20px 28px;">
        <span style="color:#fff;font-size:18px;font-weight:700;">HỌC VIỆN NGÂN HÀNG</span><br>
        <span style="color:#bcd2f0;font-size:12px;">Hệ thống đăng ký thi chứng chỉ</span>
    </div>
    <div style="padding:28px;color:#1f2937;">
        <h2 style="color:#0d2b57;margin-top:0;">Đăng ký dự thi thành công ✅</h2>
        <p>Xin chào <strong>{{ $dangKy->sinhVien->name }}</strong>,</p>
        <p>Hồ sơ đăng ký dự thi của bạn đã được ghi nhận và thanh toán lệ phí thành công. Hồ sơ hiện đang ở trạng thái <strong>Chờ duyệt</strong>. Phòng Khảo thí sẽ xét duyệt và gửi kết quả qua email trong thời gian sớm nhất.</p>
        <table style="width:100%;border-collapse:collapse;margin:20px 0;">
            <tr><td style="padding:8px 0;color:#64748b;">Mã đăng ký</td><td style="padding:8px 0;text-align:right;font-weight:700;">{{ $dangKy->ma_dang_ky }}</td></tr>
            <tr><td style="padding:8px 0;color:#64748b;">Bài thi</td><td style="padding:8px 0;text-align:right;">{{ $dangKy->lichThi->ten_ky_thi }}</td></tr>
            <tr><td style="padding:8px 0;color:#64748b;">Ngày thi</td><td style="padding:8px 0;text-align:right;">{{ optional($dangKy->lichThi->ngay_thi)->format('d/m/Y') }}</td></tr>
            <tr><td style="padding:8px 0;color:#64748b;">Phòng thi</td><td style="padding:8px 0;text-align:right;">{{ $dangKy->lichThi->phong_thi }}</td></tr>
            <tr><td style="padding:8px 0;color:#64748b;">Phương thức thanh toán</td><td style="padding:8px 0;text-align:right;">{{ strtoupper($dangKy->phuong_thuc_thanh_toan) }}</td></tr>
            <tr><td style="padding:8px 0;color:#64748b;">Mã giao dịch</td><td style="padding:8px 0;text-align:right;">{{ $dangKy->ma_giao_dich }}</td></tr>
            <tr><td style="padding:8px 0;color:#64748b;">Số tiền</td><td style="padding:8px 0;text-align:right;font-weight:700;color:#e08e00;">{{ number_format($dangKy->so_tien) }}đ</td></tr>
        </table>
        <p style="font-size:13px;color:#64748b;">Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>
    </div>
</div>
</body>
</html>
