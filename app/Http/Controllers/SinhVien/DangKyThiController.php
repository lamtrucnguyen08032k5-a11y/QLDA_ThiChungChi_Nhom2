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
        $u = Auth::user();

        return view('sinhvien.dangky.buoc1', compact('lichthi', 'draft', 'u'));
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
            'tinh_thanh_pho' => ['required', 'string'],
            'xa_phuong' => ['required', 'string'],
            'dia_chi_chi_tiet' => ['required', 'string', 'max:255'],
            'email_lien_he' => ['required', 'email', 'max:255'],
            'anh_cccd_truoc' => ['nullable', 'image', 'max:2048'],
            'anh_cccd_sau' => ['nullable', 'image', 'max:2048'],
            'anh_ho_so' => ['nullable', 'image', 'max:2048'],
            'anh_the_sv' => ['nullable', 'image', 'max:2048'],
        ], [
            'so_dien_thoai.required' => 'Vui lòng nhập số điện thoại.',
            'ngay_sinh.required' => 'Vui lòng chọn ngày sinh.',
            'ngay_sinh.before' => 'Ngày sinh không hợp lệ.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'dan_toc.required' => 'Vui lòng nhập dân tộc.',
            'noi_sinh.required' => 'Vui lòng nhập nơi sinh.',
            'so_cccd.required' => 'Vui lòng nhập số CCCD.',
            'tinh_thanh_pho.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'xa_phuong.required' => 'Vui lòng chọn xã/phường.',
            'dia_chi_chi_tiet.required' => 'Vui lòng nhập số nhà, đường/phố.',
            'email_lien_he.required' => 'Vui lòng nhập email liên hệ.',
            'email_lien_he.email' => 'Email liên hệ không hợp lệ.',
            'anh_cccd_truoc.image' => 'Ảnh CCCD mặt trước phải là file ảnh (jpg, png...).',
            'anh_cccd_sau.image' => 'Ảnh CCCD mặt sau phải là file ảnh (jpg, png...).',
            'anh_ho_so.image' => 'Ảnh hồ sơ dự thi phải là file ảnh (jpg, png...).',
            'anh_the_sv.image' => 'Ảnh thẻ sinh viên phải là file ảnh (jpg, png...).',
            'anh_cccd_truoc.max' => 'Dung lượng ảnh CCCD mặt trước tối đa 2MB.',
            'anh_cccd_sau.max' => 'Dung lượng ảnh CCCD mặt sau tối đa 2MB.',
            'anh_ho_so.max' => 'Dung lượng ảnh hồ sơ dự thi tối đa 2MB.',
            'anh_the_sv.max' => 'Dung lượng ảnh thẻ sinh viên tối đa 2MB.',
        ]);

        // Tra tên Tỉnh/Thành phố + Xã/Phường từ mã đã chọn (không tin tên do client gửi lên)
        [$tenTinh, $tenXa] = $this->layTenTinhXa($request->tinh_thanh_pho, $request->xa_phuong);
        if (! $tenTinh) {
            return back()->withInput()->withErrors(['tinh_thanh_pho' => 'Tỉnh/thành phố không hợp lệ.']);
        }
        if (! $tenXa) {
            return back()->withInput()->withErrors(['xa_phuong' => 'Xã/phường không hợp lệ, vui lòng chọn lại.']);
        }
        $data['tinh_thanh_pho_code'] = $request->tinh_thanh_pho;
        $data['tinh_thanh_pho_ten'] = $tenTinh;
        $data['xa_phuong_code'] = $request->xa_phuong;
        $data['xa_phuong_ten'] = $tenXa;
        unset($data['tinh_thanh_pho'], $data['xa_phuong']);

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
        // Mỗi ảnh thiếu sẽ báo lỗi RIÊNG dưới đúng ô upload của nó.
        $loiAnh = [];
        foreach ([
            'anh_ho_so' => 'Vui lòng tải lên ảnh hồ sơ dự thi.',
            'anh_cccd_truoc' => 'Vui lòng tải lên ảnh CCCD mặt trước.',
            'anh_cccd_sau' => 'Vui lòng tải lên ảnh CCCD mặt sau.',
        ] as $field => $thongBao) {
            if (empty($data[$field])) {
                $loiAnh[$field] = $thongBao;
            }
        }
        if (! empty($loiAnh)) {
            return back()->withInput()->withErrors($loiAnh);
        }

        session(['dangky_draft.' . $lichthi->id => $data]);

        return redirect()->route('sinhvien.dangky.buoc2', $lichthi);
    }

    // Tra tên Tỉnh/Thành phố và Xã/Phường từ mã, dựa vào public/data/vn-address.json
    // (không tin dữ liệu tên do client gửi lên, chỉ tin mã rồi tự tra lại trên server)
    private function layTenTinhXa(?string $maTinh, ?string $maXa): array
    {
        static $ds = null;
        if ($ds === null) {
            $path = public_path('data/vn-address.json');
            $ds = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        }

        foreach ($ds as $tinh) {
            if ($tinh['c'] === $maTinh) {
                foreach ($tinh['w'] as $xa) {
                    if ($xa['c'] === $maXa) {
                        return [$tinh['n'], $xa['n']];
                    }
                }
                return [$tinh['n'], null];
            }
        }
        return [null, null];
    }

    // ==== BƯỚC 2: Chọn thông tin đăng ký thi / Xác nhận hồ sơ ====
    public function buoc2(LichThi $lichthi)
    {
        $draft = session('dangky_draft.' . $lichthi->id);
        if (! $draft) {
            return redirect()->route('sinhvien.dangky.buoc1', $lichthi);
        }

        $u = Auth::user();

        return view('sinhvien.dangky.buoc2-xac-nhan', compact('lichthi', 'draft', 'u'));
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
