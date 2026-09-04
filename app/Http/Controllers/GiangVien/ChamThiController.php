<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use App\Models\CauTraLoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// M6 - UC6.1 Giảng viên chấm bài thi (phần tự luận; trắc nghiệm đã tự động chấm)
class ChamThiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $q = BaiThi::with(['dangKy.sinhVien', 'dangKy.lichThi', 'deThi'])
            ->whereHas('deThi', fn ($qq) => $qq->where('khoa_id', $user->khoa_id))
            ->whereNotNull('gio_nop');

        if ($request->get('filter') === 'chua_cham') {
            $q->where('cham_xong', false);
        } elseif ($request->get('filter') === 'da_cham') {
            $q->where('cham_xong', true);
        }

        $baiThis = $q->orderBy('gio_nop')->paginate(20)->withQueryString();

        return view('giangvien.cham-thi.index', compact('baiThis'));
    }

    public function show(BaiThi $baithi)
    {
        $baithi->load(['cauTraLois.cauHoi', 'dangKy.sinhVien', 'dangKy.lichThi']);
        return view('giangvien.cham-thi.show', compact('baithi'));
    }

    // Nhập điểm cho các câu tự luận, hệ thống tính lại điểm tổng
    public function luuDiem(Request $request, BaiThi $baithi)
    {
        $data = $request->validate([
            'diem.*' => 'nullable|numeric|min:0',
        ]);

        $tongDiemTuLuan = 0;
        foreach ($data['diem'] ?? [] as $cauTraLoiId => $diem) {
            $ctl = CauTraLoi::where('bai_thi_id', $baithi->id)->findOrFail($cauTraLoiId);
            $diemToiDa = $ctl->cauHoi->diem;
            $diem = min((float) $diem, (float) $diemToiDa);
            $ctl->update(['diem_dat' => $diem, 'da_cham' => true]);
            $tongDiemTuLuan += $diem;
        }

        $diemTong = $baithi->diem_tu_dong + $tongDiemTuLuan;

        $baithi->update([
            'diem_cham_tay' => $tongDiemTuLuan,
            'diem_tong' => $diemTong,
            'cham_xong' => true,
            'trang_thai' => 'da_cham',
            'giang_vien_id' => Auth::id(),
            'ngay_cham' => now(),
        ]);

        return redirect()->route('giangvien.cham-thi.index')->with('status', 'Đã lưu điểm chấm bài.');
    }
}
