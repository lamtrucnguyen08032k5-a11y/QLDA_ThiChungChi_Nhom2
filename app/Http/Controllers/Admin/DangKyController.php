<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\KetQuaDuyetMail;
use App\Models\DangKy;
use App\Models\LichThi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// M4 - Đăng ký thi (UC4.3 Xem danh sách đăng ký + duyệt/yêu cầu bổ sung/từ chối)
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

        Mail::to($dangky->sinhVien->email)->send(new KetQuaDuyetMail($dangky));

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

        Mail::to($dangky->sinhVien->email)->send(new KetQuaDuyetMail($dangky));

        return back()->with('status', 'Đã từ chối đăng ký.');
    }

    // Yêu cầu bổ sung hồ sơ: chọn các trường cần sửa + lý do + hạn bổ sung
    public function yeuCauBoSung(Request $request, LichThi $lichthi, DangKy $dangky)
    {
        $data = $request->validate([
            'truong_can_bo_sung' => ['required', 'array', 'min:1'],
            'ly_do_bo_sung' => ['required', 'string'],
            'han_bo_sung' => ['required', 'date', 'after:now'],
        ]);

        $dangky->update([
            'trang_thai' => 'cho_bo_sung',
            'truong_can_bo_sung' => $data['truong_can_bo_sung'],
            'ly_do_bo_sung' => $data['ly_do_bo_sung'],
            'han_bo_sung' => $data['han_bo_sung'],
        ]);

        Mail::to($dangky->sinhVien->email)->send(new KetQuaDuyetMail($dangky));

        return back()->with('status', 'Đã gửi yêu cầu bổ sung hồ sơ cho sinh viên.');
    }
}
