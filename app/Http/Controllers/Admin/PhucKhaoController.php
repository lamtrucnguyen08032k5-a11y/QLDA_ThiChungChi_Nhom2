<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhucKhao;
use Illuminate\Http\Request;

// M8 - Phúc khảo (UC8.2 Admin theo dõi & tổng hợp phản hồi phúc khảo, Giảng viên/Khoa xử lý)
class PhucKhaoController extends Controller
{
    public function index(Request $request)
    {
        $q = PhucKhao::with(['baiThi.dangKy.sinhVien', 'baiThi.deThi.khoa']);
        if ($request->filled('trang_thai')) {
            $q->where('trang_thai', $request->trang_thai);
        }
        $phucKhaos = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('admin.phuckhao.index', compact('phucKhaos'));
    }

    public function show(PhucKhao $phuckhao)
    {
        $phuckhao->load(['baiThi.cauTraLois.cauHoi', 'baiThi.dangKy.sinhVien']);
        return view('admin.phuckhao.show', compact('phuckhao'));
    }
}
