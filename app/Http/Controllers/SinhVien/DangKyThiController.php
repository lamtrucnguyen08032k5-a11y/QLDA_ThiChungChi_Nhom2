<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Mail\DangKyThanhCongMail;
use App\Models\DangKy;
use App\Models\LichThi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

// M4 - UC4.1 Đăng ký thi (quy trình 4 bước: Thông tin -> Xác nhận -> Thanh toán -> Trạng thái)
class DangKyThiController extends Controller
{
    // Danh sách các lịch thi đang mở đăng ký
    public function index(Request $request)
    {
        $q = LichThi::with('khoa')
            ->where('trang_thai', 'dang_mo_dang_ky')
            ->where('han_dang_ky', '>', now());

        if ($request->filled('loai_chung_chi')) {
            $q->where('loai_chung_chi', $request->loai_chung_chi);
        }

        $lichThis = $q->orderBy('ngay_thi')->paginate(9)->withQueryString();

        $daDangKyIds = DangKy::where('sinh_vien_id', Auth::id())
            ->whereNotIn('trang_thai', ['da_huy', 'tu_choi'])
            ->pluck('lich_thi_id')->toArray();

        return view('sinhvien.dangky.index', compact('lichThis', 'daDangKyIds'));
    }

    private function kiemTraDieuKien(LichThi $lichThi)
    {
        if ($lichThi->daHetHanDangKy() || $lichThi->trang_thai !== 'dang_mo_dang_ky') {
            return 'Ca thi này đã đóng đăng ký.';
        }
        if ($lichThi->dangKysDaDuyet()->count() >= $lichThi->so_luong_toi_da) {
            return 'Ca thi đã đủ số lượng thí sinh tối đa.';
        }
        $daDangKy = DangKy::where('sinh_vien_id', Auth::id())
            ->where('lich_thi_id', $lichThi->id)
            ->whereNotIn('trang_thai', ['da_huy', 'tu_choi'])
            ->exists();
        if ($daDangKy) {
            return 'Bạn đã đăng ký ca thi này rồi.';
        }
        return null;
    }

    // ==== BƯỚC 1: Cập nhật thông tin cá nhân ====
    public function buoc1(LichThi $lichthi)
    {
        if ($loi = $this->kiemTraDieuKien($lichthi)) {
            return redirect()->route('sinhvien.dangky.index')->withErrors(['dangky' => $loi]);
        }

        $draft = session('dangky_draft.' . $lichthi->id, []);

        return view('sinhvien.dangky.buoc1', compact('lichthi', 'draft'));
    }

    public function luuBuoc1(Request $request, LichThi $lichthi)
    {
        if ($loi = $this->kiemTraDieuKien($lichthi)) {
            return redirect()->route('sinhvien.dangky.index')->withErrors(['dangky' => $loi]);
        }

        $data = $request->validate([
            'so_dien_thoai' => ['required', 'string', 'max:15'],
            'ngay_sinh' => ['required', 'date', 'before:today'],
            'gioi_tinh' => ['required', 'in:nam,nu,khac'],
            'dan_toc' => ['required', 'string', 'max:100'],
            'noi_sinh' => ['required', 'string', 'max:255'],
            'so_cccd' => ['required', 'string', 'max:20'],
            'anh_cccd_truoc' => ['nullable', 'image', 'max:2048'],
            'anh_cccd_sau' => ['nullable', 'image', 'max:2048'],
            'anh_ho_so' => ['nullable', 'image', 'max:2048'],
            'anh_the_sv' => ['nullable', 'image', 'max:2048'],
        ]);

        $draft = session('dangky_draft.' . $lichthi->id, []);

        foreach (['anh_cccd_truoc', 'anh_cccd_sau', 'anh_ho_so', 'anh_the_sv'] as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('hoso/' . Auth::id(), 'public');
                $data[$field] = $path;
            } elseif (isset($draft[$field])) {
                $data[$field] = $draft[$field];
            }
        }

        // Bắt buộc: CCCD mặt trước/sau, ảnh hồ sơ dự thi (ảnh thẻ SV không bắt buộc)
        $thieuAnh = [];
        foreach (['anh_cccd_truoc' => 'Ảnh CCCD mặt trước', 'anh_cccd_sau' => 'Ảnh CCCD mặt sau', 'anh_ho_so' => 'Ảnh hồ sơ dự thi 4x6'] as $field => $nhan) {
            if (empty($data[$field])) {
                $thieuAnh[] = $nhan;
            }
        }
        if (! empty($thieuAnh)) {
            return back()->withInput()->withErrors(['anh_ho_so' => 'Vui lòng tải lên: ' . implode(', ', $thieuAnh) . '.']);
        }

        session(['dangky_draft.' . $lichthi->id => $data]);

        return redirect()->route('sinhvien.dangky.buoc2', $lichthi);
    }

    // ==== BƯỚC 2: Chọn thông tin đăng ký thi / Xác nhận hồ sơ ====
    public function buoc2(LichThi $lichthi)
    {
        $draft = session('dangky_draft.' . $lichthi->id);
        if (! $draft) {
            return redirect()->route('sinhvien.dangky.buoc1', $lichthi);
        }

        return view('sinhvien.dangky.buoc2-xac-nhan', compact('lichthi', 'draft'));
    }

    public function xacNhanBuoc2(LichThi $lichthi)
    {
        if ($loi = $this->kiemTraDieuKien($lichthi)) {
            return redirect()->route('sinhvien.dangky.index')->withErrors(['dangky' => $loi]);
        }

        $draft = session('dangky_draft.' . $lichthi->id);
        if (! $draft) {
            return redirect()->route('sinhvien.dangky.buoc1', $lichthi);
        }

        $dangKy = DangKy::create(array_merge($draft, [
            'sinh_vien_id' => Auth::id(),
            'lich_thi_id' => $lichthi->id,
            'trang_thai' => 'cho_duyet',
            'trang_thai_thanh_toan' => 'cho_thanh_toan',
            'so_tien' => $lichthi->le_phi,
        ]));

        session()->forget('dangky_draft.' . $lichthi->id);

        return redirect()->route('sinhvien.dangky.buoc3', $dangKy)
            ->with('status', 'Đã ghi nhận hồ sơ. Vui lòng hoàn tất thanh toán lệ phí để gửi đăng ký.');
    }

    // ==== BƯỚC 4: Trạng thái thanh toán / kết quả đăng ký ====
    public function buoc4(DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);

        return view('sinhvien.dangky.buoc4-trang-thai', compact('dangky'));
    }

    // Tra cứu đăng ký của bản thân
    public function cuaToi()
    {
        $dangKys = DangKy::with(['lichThi.khoa', 'baiThi'])
            ->where('sinh_vien_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('sinhvien.dangky.cua-toi', compact('dangKys'));
    }

    public function huy(DangKy $dangky)
    {
        abort_unless($dangky->sinh_vien_id === Auth::id(), 403);

        if (! in_array($dangky->trang_thai, ['cho_duyet', 'cho_bo_sung'])) {
            return back()->withErrors(['dangky' => 'Chỉ có thể huỷ đăng ký khi chưa được duyệt.']);
        }

        $dangky->update(['trang_thai' => 'da_huy']);
        return back()->with('status', 'Đã huỷ đăng ký.');
    }
}
