<?php

namespace App\Http\Controllers\Khoa;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use Illuminate\Support\Facades\Auth;

// Khoa theo dõi tiến độ chấm của các Giảng viên trong khoa mình theo phòng/ngày thi
class TienDoChamController extends Controller
{
    public function index()
    {
        $khoa = Auth::user()->khoa;

        $baiThis = BaiThi::with(['dangKy.sinhVien', 'dangKy.lichThi', 'giangVien'])
            ->whereHas('deThi', fn ($q) => $q->where('khoa_id', $khoa->id))
            ->whereNotNull('gio_nop')
            ->orderByDesc('gio_nop')
            ->paginate(20);

        return view('khoa.tien-do-cham', compact('baiThis', 'khoa'));
    }
}
