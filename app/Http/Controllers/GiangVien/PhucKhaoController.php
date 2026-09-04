<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use App\Models\PhucKhao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

// M8 - UC8.2 Giảng viên/Khoa xử lý phúc khảo
class PhucKhaoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $q = PhucKhao::with(['baiThi.dangKy.sinhVien', 'baiThi.deThi'])
            ->whereHas('baiThi.deThi', fn ($qq) => $qq->where('khoa_id', $user->khoa_id));

        if ($request->filled('trang_thai')) {
            $q->where('trang_thai', $request->trang_thai);
        }

        $phucKhaos = $q->orderBy('created_at')->paginate(20)->withQueryString();
        return view('giangvien.phuc-khao.index', compact('phucKhaos'));
    }

    public function show(PhucKhao $phuckhao)
    {
        $phuckhao->load(['baiThi.cauTraLois.cauHoi', 'baiThi.dangKy.sinhVien']);
        return view('giangvien.phuc-khao.show', compact('phuckhao'));
    }

    // Xử lý phúc khảo: xem lại bài chấm, điều chỉnh điểm (nếu cần), gửi phản hồi
    public function xuLy(Request $request, PhucKhao $phuckhao)
    {
        $data = $request->validate([
            'phan_hoi' => 'required|string',
            'diem_sau' => 'nullable|numeric|min:0',
            'quyet_dinh' => 'required|in:da_xu_ly,tu_choi',
        ]);

        $diemTruoc = $phuckhao->baiThi->diem_tong;

        $phuckhao->update([
            'trang_thai' => $data['quyet_dinh'],
            'phan_hoi' => $data['phan_hoi'],
            'diem_truoc' => $diemTruoc,
            'diem_sau' => $data['quyet_dinh'] === 'da_xu_ly' ? ($data['diem_sau'] ?? $diemTruoc) : null,
            'xu_ly_boi' => Auth::id(),
            'ngay_xu_ly' => now(),
        ]);

        if ($data['quyet_dinh'] === 'da_xu_ly' && isset($data['diem_sau'])) {
            $phuckhao->baiThi->update(['diem_tong' => $data['diem_sau']]);
        }

        Mail::raw("Yêu cầu phúc khảo của bạn đã được xử lý.\nPhản hồi: {$data['phan_hoi']}", function ($m) use ($phuckhao) {
            $m->to($phuckhao->sinhVien->email)->subject('Kết quả xử lý phúc khảo');
        });

        return redirect()->route('giangvien.phuc-khao.index')->with('status', 'Đã gửi phản hồi phúc khảo.');
    }
}
