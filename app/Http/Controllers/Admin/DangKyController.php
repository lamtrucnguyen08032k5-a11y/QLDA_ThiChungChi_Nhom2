<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKy;
use App\Models\LichThi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// M4 - Đăng ký thi (UC4.3 Xem danh sách đăng ký + duyệt/từ chối)
class DangKyController extends Controller
{
    public function index(Request $request, LichThi $lichthi)
    {
        $q = $lichthi->dangKys()->with('sinhVien');
        if ($request->filled('trang_thai')) {
            $q->where('trang_thai', $request->trang_thai);
        }
        $dangKys = $q->orderBy('created_at')->paginate(20)->withQueryString();
        return view('admin.dangky.index', compact('lichthi', 'dangKys'));
    }

    public function approve(LichThi $lichthi, DangKy $dangky)
    {
        if ($lichthi->dangKysDaDuyet()->count() >= $lichthi->so_luong_toi_da) {
            return back()->withErrors(['dangky' => 'Ca thi đã đủ số lượng tối đa, không thể duyệt thêm.']);
        }

        $dangky->update(['trang_thai' => 'da_duyet', 'ngay_duyet' => now()]);

        Mail::raw("Đăng ký thi của bạn cho kỳ thi \"{$lichthi->ten_ky_thi}\" ngày {$lichthi->ngay_thi->format('d/m/Y')} đã được DUYỆT.", function ($m) use ($dangky) {
            $m->to($dangky->sinhVien->email)->subject('Kết quả duyệt đăng ký thi');
        });

        return back()->with('status', 'Đã duyệt đăng ký.');
    }

    public function reject(Request $request, LichThi $lichthi, DangKy $dangky)
    {
        $data = $request->validate(['ly_do_tu_choi' => 'required|string']);
        $dangky->update([
            'trang_thai' => 'tu_choi',
            'ly_do_tu_choi' => $data['ly_do_tu_choi'],
            'ngay_duyet' => now(),
        ]);

        Mail::raw("Đăng ký thi của bạn cho kỳ thi \"{$lichthi->ten_ky_thi}\" đã bị TỪ CHỐI.\nLý do: {$data['ly_do_tu_choi']}", function ($m) use ($dangky) {
            $m->to($dangky->sinhVien->email)->subject('Kết quả duyệt đăng ký thi');
        });

        return back()->with('status', 'Đã từ chối đăng ký.');
    }
}
