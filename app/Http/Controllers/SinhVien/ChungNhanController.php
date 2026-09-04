<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use App\Models\ChungNhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// M9 - UC9.1 Sinh viên đăng ký nhận chứng nhận (chỉ áp dụng cho bài đạt yêu cầu)
class ChungNhanController extends Controller
{
    const DIEM_DAT = 50; // ngưỡng điểm đạt (thang điểm 100), có thể điều chỉnh theo quy chế

    public function create(BaiThi $baithi)
    {
        abort_unless($baithi->dangKy->sinh_vien_id === Auth::id(), 403);
        abort_unless($baithi->trang_thai === 'da_cong_bo', 404);
        abort_unless($baithi->diem_tong >= self::DIEM_DAT, 403, 'Bài thi chưa đạt điểm yêu cầu để đăng ký nhận chứng nhận.');

        return view('sinhvien.chung-nhan.create', compact('baithi'));
    }

    public function store(Request $request, BaiThi $baithi)
    {
        abort_unless($baithi->dangKy->sinh_vien_id === Auth::id(), 403);
        abort_unless($baithi->diem_tong >= self::DIEM_DAT, 403);

        if ($baithi->chungNhan()->exists()) {
            return back()->withErrors(['chungnhan' => 'Bạn đã đăng ký nhận chứng nhận cho bài thi này rồi.']);
        }

        $data = $request->validate([
            'dia_chi_nhan' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20',
        ]);

        ChungNhan::create([
            'bai_thi_id' => $baithi->id,
            'sinh_vien_id' => Auth::id(),
            'dia_chi_nhan' => $data['dia_chi_nhan'],
            'so_dien_thoai' => $data['so_dien_thoai'],
            'trang_thai' => 'cho_duyet',
        ]);

        return redirect()->route('sinhvien.chung-nhan.index')->with('status', 'Đăng ký nhận chứng nhận thành công.');
    }

    public function index()
    {
        $chungNhans = ChungNhan::with('baiThi.deThi')
            ->where('sinh_vien_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('sinhvien.chung-nhan.index', compact('chungNhans'));
    }
}
