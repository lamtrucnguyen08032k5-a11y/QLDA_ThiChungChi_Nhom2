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
        @if ($dangKy->trang_thai === 'da_duyet')
            <h2 style="color:#16794f;margin-top:0;">Hồ sơ đăng ký dự thi đã được DUYỆT ✅</h2>
            <p>Xin chào <strong>{{ $dangKy->sinhVien->name }}</strong>, hồ sơ đăng ký dự thi <strong>{{ $dangKy->ma_dang_ky }}</strong> cho bài thi <strong>{{ $dangKy->lichThi->ten_ky_thi }}</strong> ngày {{ optional($dangKy->lichThi->ngay_thi)->format('d/m/Y') }} đã được duyệt. Bạn đã có tên chính thức trong danh sách dự thi.</p>
        @elseif ($dangKy->trang_thai === 'cho_bo_sung')
            <h2 style="color:#b45309;margin-top:0;">Yêu cầu BỔ SUNG hồ sơ ⚠️</h2>
            <p>Xin chào <strong>{{ $dangKy->sinhVien->name }}</strong>, hồ sơ đăng ký dự thi <strong>{{ $dangKy->ma_dang_ky }}</strong> cần được bổ sung thông tin.</p>
            <p><strong>Lý do / nội dung cần bổ sung:</strong><br>{{ $dangKy->ly_do_bo_sung }}</p>
            <p><strong>Hạn bổ sung trực tuyến:</strong> {{ optional($dangKy->han_bo_sung)->format('d/m/Y H:i') }}</p>
            <p>Vui lòng đăng nhập hệ thống, vào mục <em>Đăng ký của tôi</em> để bổ sung hồ sơ trước thời hạn trên.</p>
        @else
            <h2 style="color:#b91c1c;margin-top:0;">Hồ sơ đăng ký dự thi bị TỪ CHỐI ❌</h2>
            <p>Xin chào <strong>{{ $dangKy->sinhVien->name }}</strong>, hồ sơ đăng ký dự thi <strong>{{ $dangKy->ma_dang_ky }}</strong> cho bài thi <strong>{{ $dangKy->lichThi->ten_ky_thi }}</strong> đã bị từ chối.</p>
            <p><strong>Lý do:</strong> {{ $dangKy->ly_do_tu_choi }}</p>
        @endif
        <p style="font-size:13px;color:#64748b;margin-top:24px;">Đây là email tự động, vui lòng không trả lời trực tiếp email này. Mọi thắc mắc xin liên hệ Phòng Khảo thí.</p>
    </div>
</div>
</body>
</html>
