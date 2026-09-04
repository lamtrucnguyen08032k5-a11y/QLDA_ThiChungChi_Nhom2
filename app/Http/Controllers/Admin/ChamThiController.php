<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use App\Models\LichThi;
use Illuminate\Http\Request;

// M6 - Chấm thi (UC6.2 Admin theo dõi tiến độ chấm theo khoa/ca thi)
class ChamThiController extends Controller
{
    public function index(Request $request)
    {
        $lichThis = LichThi::with('khoa')
            ->withCount([
                'dangKys as tong_bai_da_nop' => function ($q) {
                    $q->whereHas('baiThi', fn ($b) => $b->whereNotNull('gio_nop'));
                },
                'dangKys as tong_bai_da_cham' => function ($q) {
                    $q->whereHas('baiThi', fn ($b) => $b->where('cham_xong', true));
                },
            ])
            ->whereIn('trang_thai', ['dang_thi', 'da_ket_thuc'])
            ->orderByDesc('ngay_thi')
            ->paginate(15);

        return view('admin.tochuc.cham-tien-do', compact('lichThis'));
    }
}
