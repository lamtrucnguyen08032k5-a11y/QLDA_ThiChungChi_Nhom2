<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\DangKy;
use App\Models\LichThi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// M4 - UC4.1 Đăng ký thi, UC4.2 Tra cứu đăng ký của sinh viên
class DangKyThiController extends Controller
{
    // Danh sách các lịch thi đang mở đăng ký
    public function index(Request $request)
    {
        $q = LichThi::with('khoa')
            ->where('trang_thai', 'dang_mo_dang_ky')
            ->where('han_dang_ky', '>', now());

        if ($request->filled('loai_chung_chi')) {
            $q->where('loai_chung_chi', $request->loai_chung_chi);
        }

        $lichThis = $q->orderBy('ngay_thi')->paginate(10)->withQueryString();

        $daDangKyIds = DangKy::where('sinh_vien_id', Auth::id())->pluck('lich_thi_id')->toArray();

        return view('sinhvien.dangky.index', compact('lichThis', 'daDangKyIds'));
    }

    public function store(LichThi $lichthi)
    {
        if ($lichthi->daHetHanDangKy() || $lichthi->trang_thai !== 'dang_mo_dang_ky') {
            return back()->withErrors(['dangky' => 'Ca thi này đã đóng đăng ký.']);
        }

        if ($lichthi->dangKysDaDuyet()->count() >= $lichthi->so_luong_toi_da) {
            return back()->withErrors(['dangky' => 'Ca thi đã đủ số lượng thí sinh tối đa.']);
        }

        $existing = DangKy::where('sinh_vien_id', Auth::id())->where('lich_thi_id', $lichthi->id)->first();
        if ($existing) {
            return back()->withErrors(['dangky' => 'Bạn đã đăng ký ca thi này rồi.']);
        }

        DangKy::create([
            'sinh_vien_id' => Auth::id(),
            'lich_thi_id' => $lichthi->id,
            'trang_thai' => 'cho_duyet',
        ]);

        return redirect()->route('sinhvien.dangky.cua-toi')->with('status', 'Đăng ký thi thành công. Vui lòng chờ Phòng khảo thí duyệt.');
    }

    // Tra cứu đăng ký của bản thân
    public function cuaToi()
    {
        $dangKys = DangKy::with(['lichThi.khoa', 'baiThi'])
            ->where('sinh_vien_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('sinhvien.dangky.cua-toi', compact('dangKys'));
    }

    public function huy(DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);

        if ($dangky->trang_thai !== 'cho_duyet') {
            return back()->withErrors(['dangky' => 'Chỉ có thể huỷ đăng ký khi chưa được duyệt.']);
        }

        $dangky->update(['trang_thai' => 'da_huy']);
        return back()->with('status', 'Đã huỷ đăng ký.');
    }
}
