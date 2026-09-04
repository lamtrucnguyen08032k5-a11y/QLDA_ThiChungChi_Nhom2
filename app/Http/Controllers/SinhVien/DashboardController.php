<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\DangKy;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dangKys = DangKy::with(['lichThi', 'baiThi'])
            ->where('sinh_vien_id', Auth::id())
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('sinhvien.dashboard', compact('dangKys'));
    }
}
