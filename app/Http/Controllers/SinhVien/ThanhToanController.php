<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Mail\DangKyThanhCongMail;
use App\Models\DangKy;
use App\Services\VnPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

// M4 - Bước 3: Chọn phương thức thanh toán (VNPAY / NAPAS) & xử lý kết quả thanh toán
class ThanhToanController extends Controller
{
    public function __construct(private VnPayService $vnPay)
    {
    }

    // Bước 3a: chọn phương thức thanh toán
    public function chon(DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);

        if ($dangky->trang_thai_thanh_toan === 'da_thanh_toan') {
            return redirect()->route('sinhvien.dangky.buoc4', $dangky);
        }

        return view('sinhvien.dangky.buoc3-thanh-toan', compact('dangky'));
    }

    // Bước 3b: khởi tạo giao dịch, chuyển sang cổng thanh toán
    public function khoiTao(Request $request, DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);

        $data = $request->validate([
            'phuong_thuc_thanh_toan' => ['required', 'in:vnpay,napas'],
        ]);

        $maGiaoDich = 'GD' . now()->format('ymdHis') . strtoupper(Str::random(4));

        $dangky->update([
            'phuong_thuc_thanh_toan' => $data['phuong_thuc_thanh_toan'],
            'ma_giao_dich' => $maGiaoDich,
        ]);

        // Nếu đã cấu hình tài khoản merchant VNPAY thật -> chuyển sang cổng VNPAY thật.
        if ($this->vnPay->daCauHinh()) {
            $noiDung = 'Thanh toan le phi thi ' . $dangky->ma_dang_ky;
            $url = $this->vnPay->taoUrlThanhToan($maGiaoDich, (int) $dangky->so_tien, $noiDung, $data['phuong_thuc_thanh_toan']);
            return redirect()->away($url);
        }

        // Chưa cấu hình merchant thật -> dùng cổng thanh toán MÔ PHỎNG để vẫn demo được toàn bộ luồng.
        return redirect()->route('sinhvien.dangky.thanhtoan.mophong', $dangky);
    }

    // Cổng thanh toán mô phỏng (dùng khi chưa có tài khoản VNPAY/NAPAS thật)
    public function moPhong(DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);
        return view('sinhvien.dangky.thanh-toan-mo-phong', compact('dangky'));
    }

    public function xuLyMoPhong(Request $request, DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);

        $ketQua = $request->input('ket_qua', 'thanh_cong');

        if ($ketQua === 'thanh_cong') {
            $this->danhDauThanhCong($dangky);
        } else {
            $dangky->update(['trang_thai_thanh_toan' => 'thanh_toan_that_bai']);
        }

        return redirect()->route('sinhvien.dangky.buoc4', $dangky);
    }

    // Điểm trả về (return URL) thật từ VNPAY khi đã cấu hình merchant thật
    public function vnpayReturn(Request $request)
    {
        $params = $request->all();
        $hopLe = $this->vnPay->xacThucChuKy($params);

        $dangky = DangKy::where('ma_giao_dich', $params['vnp_TxnRef'] ?? null)->first();

        if (! $dangky) {
            abort(404);
        }

        if ($hopLe && ($params['vnp_ResponseCode'] ?? null) === '00') {
            $this->danhDauThanhCong($dangky);
        } else {
            $dangky->update(['trang_thai_thanh_toan' => 'thanh_toan_that_bai']);
        }

        return redirect()->route('sinhvien.dangky.buoc4', $dangky);
    }

    private function danhDauThanhCong(DangKy $dangky): void
    {
        $dangky->update([
            'trang_thai_thanh_toan' => 'da_thanh_toan',
            'ngay_thanh_toan' => now(),
        ]);

        Mail::to($dangky->sinhVien->email)->send(new DangKyThanhCongMail($dangky));
    }
}
