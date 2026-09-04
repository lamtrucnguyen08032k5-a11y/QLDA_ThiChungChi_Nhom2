<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use App\Models\CauTraLoi;
use App\Models\DangKy;
use App\Models\LichThi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// M5 - Tổ chức thi (UC5.2 Làm bài thi): nhập mã ca thi -> vào phòng thi -> làm bài -> nộp -> tự chấm trắc nghiệm
class ThiController extends Controller
{
    // Menu "Thi": các ca đã hết hạn đăng ký / đang thi mà SV đã được duyệt
    public function index()
    {
        $dangKys = DangKy::with('lichThi')
            ->where('sinh_vien_id', Auth::id())
            ->where('trang_thai', 'da_duyet')
            ->whereHas('lichThi', fn ($q) => $q->whereIn('trang_thai', ['da_dong_dang_ky', 'dang_thi', 'da_ket_thuc']))
            ->orderByDesc('created_at')
            ->get();

        return view('sinhvien.thi.index', compact('dangKys'));
    }

    public function chiTiet(DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);
        $dangky->load('lichThi', 'baiThi');
        return view('sinhvien.thi.chi-tiet', compact('dangky'));
    }

    // Nhập mã ca thi để bắt đầu làm bài
    public function nhapMa(Request $request, DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);

        $request->validate(['ma_ca_thi' => 'required|string']);

        $lichThi = $dangky->lichThi;

        if ($lichThi->ma_ca_thi !== strtoupper($request->ma_ca_thi)) {
            return back()->withErrors(['ma_ca_thi' => 'Mã ca thi không đúng.']);
        }

        if ($lichThi->trang_thai !== 'dang_thi') {
            return back()->withErrors(['ma_ca_thi' => 'Ca thi chưa bắt đầu hoặc đã kết thúc.']);
        }

        if (! $lichThi->de_thi_id) {
            return back()->withErrors(['ma_ca_thi' => 'Chưa có đề thi được áp dụng cho ca thi này.']);
        }

        $baiThi = $dangky->baiThi;
        if (! $baiThi) {
            $baiThi = BaiThi::create([
                'dang_ky_id' => $dangky->id,
                'de_thi_id' => $lichThi->de_thi_id,
                'gio_bat_dau' => now(),
                'trang_thai' => 'dang_thi',
            ]);
        }

        if ($baiThi->gio_nop) {
            return redirect()->route('sinhvien.thi.chi-tiet', $dangky)->withErrors(['ma_ca_thi' => 'Bạn đã nộp bài thi này rồi.']);
        }

        return redirect()->route('sinhvien.thi.lam-bai', $baiThi);
    }

    public function lamBai(BaiThi $baithi)
    {
        abort_unless($baithi->dangKy->sinh_vien_id === Auth::id(), 403);
        abort_if($baithi->gio_nop, 403, 'Bài thi đã được nộp.');

        $baithi->load('deThi.cauHois');
        $lichThi = $baithi->dangKy->lichThi;

        $hanNop = $baithi->gio_bat_dau->copy()->addMinutes($lichThi->thoi_gian_thi_phut);

        return view('sinhvien.thi.lam-bai', compact('baithi', 'hanNop'));
    }

    // Nộp bài: lưu câu trả lời, tự động chấm phần trắc nghiệm
    public function nopBai(Request $request, BaiThi $baithi)
    {
        abort_unless($baithi->dangKy->sinh_vien_id === Auth::id(), 403);
        abort_if($baithi->gio_nop, 403, 'Bài thi đã được nộp.');

        $traLoi = $request->input('tra_loi', []); // [cau_hoi_id => 'A'/'B'/... hoặc text tự luận]

        DB::transaction(function () use ($baithi, $traLoi) {
            $diemTuDong = 0;

            foreach ($baithi->deThi->cauHois as $cauHoi) {
                $giaTri = $traLoi[$cauHoi->id] ?? null;

                if ($cauHoi->loai_cau === 'tracnghiem') {
                    $dung = $giaTri && strtoupper($giaTri) === strtoupper($cauHoi->dap_an_dung);
                    $diem = $dung ? $cauHoi->diem : 0;
                    $diemTuDong += $diem;

                    CauTraLoi::create([
                        'bai_thi_id' => $baithi->id,
                        'cau_hoi_id' => $cauHoi->id,
                        'dap_an_chon' => $giaTri,
                        'diem_dat' => $diem,
                        'da_cham' => true, // trắc nghiệm tự động chấm xong ngay
                    ]);
                } else {
                    // Tự luận: chưa chấm, điểm mặc định 0 chờ Giảng viên chấm tay
                    CauTraLoi::create([
                        'bai_thi_id' => $baithi->id,
                        'cau_hoi_id' => $cauHoi->id,
                        'bai_lam_tu_luan' => $giaTri,
                        'diem_dat' => 0,
                        'da_cham' => false,
                    ]);
                }
            }

            $coTuLuan = $baithi->deThi->cauHois->contains('loai_cau', 'tuluan');

            $baithi->update([
                'gio_nop' => now(),
                'diem_tu_dong' => $diemTuDong,
                'diem_tong' => $coTuLuan ? null : $diemTuDong, // nếu không có tự luận thì chấm xong luôn
                'cham_xong' => ! $coTuLuan,
                'trang_thai' => $coTuLuan ? 'dang_cham' : 'da_cham',
            ]);
        });

        return redirect()->route('sinhvien.thi.chi-tiet', $baithi->dangKy)->with('status', 'Nộp bài thành công.');
    }
}
