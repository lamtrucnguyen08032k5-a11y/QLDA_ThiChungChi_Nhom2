<?php

namespace App\Http\Controllers\Khoa;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $khoa = Auth::user()->khoa;

        $giangViens = $khoa->giangViens()->get();

        $baiCanCham = BaiThi::whereHas('deThi', fn ($q) => $q->where('khoa_id', $khoa->id))
            ->where('cham_xong', false)
            ->whereNotNull('gio_nop')
            ->count();

        $baiDaCham = BaiThi::whereHas('deThi', fn ($q) => $q->where('khoa_id', $khoa->id))
            ->where('cham_xong', true)
            ->count();

        return view('khoa.dashboard', compact('khoa', 'giangViens', 'baiCanCham', 'baiDaCham'));
    }
}
