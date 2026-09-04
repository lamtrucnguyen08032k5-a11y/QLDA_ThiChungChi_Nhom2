<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khoa;
use App\Models\LichThi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// M2 - Quản lý kỳ thi (UC2.1 Quản lý lịch thi)
class LichThiController extends Controller
{
    public function index(Request $request)
    {
        $q = LichThi::with('khoa')->withCount('dangKys');
        if ($request->filled('loai_chung_chi')) {
            $q->where('loai_chung_chi', $request->loai_chung_chi);
        }
        $lichThis = $q->orderByDesc('ngay_thi')->paginate(15)->withQueryString();
        return view('admin.lichthi.index', compact('lichThis'));
    }

    public function create()
    {
        $khoas = Khoa::where('active', true)->orderBy('ten_khoa')->get();
        return view('admin.lichthi.create', compact('khoas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_ky_thi' => 'required|string|max:255',
            'loai_chung_chi' => 'required|in:cntt,tienganh',
            'khoa_id' => 'required|exists:khoas,id',
            'ngay_thi' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required',
            'thoi_gian_thi_phut' => 'required|integer|min:10|max:300',
            'phong_thi' => 'required|string|max:100',
            'so_luong_toi_da' => 'required|integer|min:1',
            'han_dang_ky' => 'required|date|before:ngay_thi',
            'le_phi' => 'required|numeric|min:0',
        ]);

        $data['ma_ca_thi'] = strtoupper(Str::random(8));
        $data['trang_thai'] = 'dang_mo_dang_ky';

        $lichThi = LichThi::create($data);

        return redirect()->route('admin.lichthi.index')->with('status', "Tạo lịch thi thành công. Mã ca thi: {$lichThi->ma_ca_thi}");
    }

    public function edit(LichThi $lichthi)
    {
        $khoas = Khoa::where('active', true)->orderBy('ten_khoa')->get();
        return view('admin.lichthi.edit', compact('lichthi', 'khoas'));
    }

    public function update(Request $request, LichThi $lichthi)
    {
        $data = $request->validate([
            'ten_ky_thi' => 'required|string|max:255',
            'loai_chung_chi' => 'required|in:cntt,tienganh',
            'khoa_id' => 'required|exists:khoas,id',
            'ngay_thi' => 'required|date',
            'gio_bat_dau' => 'required',
            'thoi_gian_thi_phut' => 'required|integer|min:10|max:300',
            'phong_thi' => 'required|string|max:100',
            'so_luong_toi_da' => 'required|integer|min:1',
            'han_dang_ky' => 'required|date',
            'le_phi' => 'required|numeric|min:0',
            'trang_thai' => 'required|in:dang_mo_dang_ky,da_dong_dang_ky,dang_thi,da_ket_thuc',
        ]);
        $lichthi->update($data);

        return redirect()->route('admin.lichthi.index')->with('status', 'Cập nhật lịch thi thành công.');
    }

    public function destroy(LichThi $lichthi)
    {
        if ($lichthi->dangKys()->exists()) {
            return back()->withErrors(['lichthi' => 'Không thể xoá lịch thi đã có sinh viên đăng ký.']);
        }
        $lichthi->delete();
        return back()->with('status', 'Đã xoá lịch thi.');
    }
}
