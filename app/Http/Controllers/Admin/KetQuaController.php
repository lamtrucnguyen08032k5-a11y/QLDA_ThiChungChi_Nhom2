<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use App\Models\LichThi;
use Illuminate\Support\Facades\Mail;

// M7 - Kết quả (UC7.1 Admin công bố kết quả)
class KetQuaController extends Controller
{
    public function index(LichThi $lichthi)
    {
        $baiThis = BaiThi::with('dangKy.sinhVien')
            ->whereHas('dangKy', fn ($q) => $q->where('lich_thi_id', $lichthi->id))
            ->orderByDesc('diem_tong')
            ->get();

        return view('admin.ketqua.index', compact('lichthi', 'baiThis'));
    }

    // Bấm "Công bố kết quả": chỉ công bố các bài đã chấm xong
    public function congBo(LichThi $lichthi)
    {
        $baiThis = BaiThi::with('dangKy.sinhVien')
            ->whereHas('dangKy', fn ($q) => $q->where('lich_thi_id', $lichthi->id))
            ->where('cham_xong', true)
            ->where('trang_thai', '!=', 'da_cong_bo')
            ->get();

        foreach ($baiThis as $bai) {
            $bai->update(['trang_thai' => 'da_cong_bo']);
            if ($bai->dangKy?->sinhVien?->email) {
                Mail::raw("Kết quả kỳ thi \"{$lichthi->ten_ky_thi}\" của bạn đã được công bố. Điểm: {$bai->diem_tong}. Vui lòng đăng nhập hệ thống để tra cứu chi tiết.", function ($m) use ($bai) {
                    $m->to($bai->dangKy->sinhVien->email)->subject('Công bố kết quả thi');
                });
            }
        }

        return back()->with('status', "Đã công bố kết quả cho {$baiThis->count()} bài thi đã chấm xong.");
    }
}
