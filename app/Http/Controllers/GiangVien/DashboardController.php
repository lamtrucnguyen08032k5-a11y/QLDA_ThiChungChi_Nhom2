<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use App\Models\PhucKhao;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $baiCanCham = BaiThi::whereHas('deThi', fn ($q) => $q->where('khoa_id', $user->khoa_id))
            ->where('cham_xong', false)
            ->whereNotNull('gio_nop')
            ->count();

        $phucKhaoCho = PhucKhao::whereHas('baiThi.deThi', fn ($q) => $q->where('khoa_id', $user->khoa_id))
            ->where('trang_thai', 'cho_xu_ly')
            ->count();

        return view('giangvien.dashboard', compact('baiCanCham', 'phucKhaoCho'));
    }
}
