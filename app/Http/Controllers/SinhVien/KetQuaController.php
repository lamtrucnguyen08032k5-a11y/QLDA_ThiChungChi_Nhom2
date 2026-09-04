<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use Illuminate\Support\Facades\Auth;

// M7 - UC7.2 Sinh viên tra cứu kết quả thi (chỉ xem bài đã công bố)
class KetQuaController extends Controller
{
    public function index()
    {
        $baiThis = BaiThi::with(['dangKy.lichThi', 'deThi'])
            ->whereHas('dangKy', fn ($q) => $q->where('sinh_vien_id', Auth::id()))
            ->where('trang_thai', 'da_cong_bo')
            ->orderByDesc('gio_nop')
            ->get();

        return view('sinhvien.ketqua.index', compact('baiThis'));
    }

    public function show(BaiThi $baithi)
    {
        abort_unless($baithi->dangKy->sinh_vien_id === Auth::id(), 403);
        abort_unless($baithi->trang_thai === 'da_cong_bo', 404, 'Kết quả chưa được công bố.');

        $baithi->load('cauTraLois.cauHoi', 'dangKy.lichThi');

        return view('sinhvien.ketqua.show', compact('baithi'));
    }
}
