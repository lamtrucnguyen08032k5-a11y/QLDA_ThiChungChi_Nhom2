<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChungNhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// M9 - Chứng nhận (UC9.2 Admin xử lý & cấp chứng nhận)
class ChungNhanController extends Controller
{
    public function index(Request $request)
    {
        $q = ChungNhan::with(['sinhVien', 'baiThi.deThi']);
        if ($request->filled('trang_thai')) {
            $q->where('trang_thai', $request->trang_thai);
        }
        $chungNhans = $q->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('admin.chungnhan.index', compact('chungNhans'));
    }

    public function capNhan(ChungNhan $chungnhan)
    {
        $soChungNhan = 'HVNH-' . now()->format('Y') . '-' . str_pad($chungnhan->id, 5, '0', STR_PAD_LEFT);
        $chungnhan->update([
            'trang_thai' => 'da_cap',
            'so_chung_nhan' => $soChungNhan,
            'ngay_cap' => now(),
        ]);

        Mail::raw("Chứng nhận của bạn đã được cấp. Số chứng nhận: {$soChungNhan}. Vui lòng đăng nhập hệ thống để xem chi tiết.", function ($m) use ($chungnhan) {
            $m->to($chungnhan->sinhVien->email)->subject('Cấp chứng nhận thi chứng chỉ');
        });

        return back()->with('status', 'Đã cấp chứng nhận.');
    }

    public function tuChoi(Request $request, ChungNhan $chungnhan)
    {
        $chungnhan->update(['trang_thai' => 'tu_choi']);
        return back()->with('status', 'Đã từ chối yêu cầu cấp chứng nhận.');
    }
}
