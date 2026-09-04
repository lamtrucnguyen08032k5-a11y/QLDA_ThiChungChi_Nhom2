<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khoa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

// M1 module con: Admin quản lý Khoa và tạo tài khoản Khoa/Giảng viên (Use case "Tạo tài khoản")
class KhoaController extends Controller
{
    public function index()
    {
        $khoas = Khoa::withCount('giangViens')->orderBy('ten_khoa')->paginate(15);
        return view('admin.khoa.index', compact('khoas'));
    }

    public function create()
    {
        return view('admin.khoa.create');
    }

    // Bước 3-9 kịch bản "Tạo tài khoản": Thêm mới Khoa -> tạo tài khoản Khoa, vai trò Khoa
    public function store(Request $request)
    {
        $data = $request->validate([
            'ma_khoa' => 'required|string|max:50|unique:khoas,ma_khoa',
            'ten_khoa' => 'required|string|max:255',
            'email' => 'required|email|unique:khoas,email|unique:users,email',
            'mo_ta' => 'nullable|string',
        ]);
        // Luồng phụ 2: Khoa đã tồn tại -> unique rules ở trên đã chặn (trả lỗi tương ứng)

        $matKhauTam = Str::random(10);

        DB::transaction(function () use ($data, $matKhauTam) {
            $khoa = Khoa::create($data);

            User::create([
                'role' => 'khoa',
                'ma_so' => $data['ma_khoa'],
                'name' => 'Tài khoản Khoa ' . $data['ten_khoa'],
                'email' => $data['email'],
                'password' => Hash::make($matKhauTam),
                'khoa_id' => $khoa->id,
                'email_verified_at' => now(),
            ]);

            Mail::raw("Tài khoản Khoa {$data['ten_khoa']} đã được tạo.\nEmail: {$data['email']}\nMật khẩu tạm thời: {$matKhauTam}\nVui lòng đăng nhập và đổi mật khẩu.", function ($m) use ($data) {
                $m->to($data['email'])->subject('Tài khoản Khoa - Hệ thống thi chứng chỉ HVNH');
            });
        });

        return redirect()->route('admin.khoa.index')->with('status', 'Thêm Khoa thành công.');
    }

    // Bước 10-11: màn hình chi tiết Khoa gồm Thông tin Khoa và Danh sách Giảng viên
    public function show(Khoa $khoa)
    {
        $giangViens = $khoa->giangViens()->orderBy('name')->get();
        return view('admin.khoa.show', compact('khoa', 'giangViens'));
    }

    public function edit(Khoa $khoa)
    {
        return view('admin.khoa.edit', compact('khoa'));
    }

    // Luồng phụ 1: Thông tin Khoa không hợp lệ -> validate bên dưới
    public function update(Request $request, Khoa $khoa)
    {
        $data = $request->validate([
            'ma_khoa' => 'required|string|max:50|unique:khoas,ma_khoa,' . $khoa->id,
            'ten_khoa' => 'required|string|max:255',
            'email' => 'required|email|unique:khoas,email,' . $khoa->id,
            'mo_ta' => 'nullable|string',
            'active' => 'nullable|boolean',
        ]);
        $data['active'] = $request->boolean('active');
        $khoa->update($data);

        return redirect()->route('admin.khoa.show', $khoa)->with('status', 'Cập nhật Khoa thành công.');
    }

    // Bước 12-19: Thêm Giảng viên vào Khoa
    public function storeGiangVien(Request $request, Khoa $khoa)
    {
        $data = $request->validate([
            'ma_giang_vien' => 'required|string|max:50|unique:users,ma_so',
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);
        // Luồng phụ 4: Giảng viên đã tồn tại -> unique ở trên chặn -> "Giảng viên đã tồn tại"

        $matKhauTam = Str::random(10);

        $gv = User::create([
            'role' => 'giangvien',
            'ma_so' => $data['ma_giang_vien'],
            'name' => $data['ho_ten'],
            'email' => $data['email'],
            'password' => Hash::make($matKhauTam),
            'khoa_id' => $khoa->id,
            'email_verified_at' => now(),
        ]);

        Mail::raw("Tài khoản Giảng viên đã được tạo.\nEmail: {$data['email']}\nMật khẩu tạm thời: {$matKhauTam}\nVui lòng đăng nhập và đổi mật khẩu.", function ($m) use ($data) {
            $m->to($data['email'])->subject('Tài khoản Giảng viên - Hệ thống thi chứng chỉ HVNH');
        });

        return back()->with('status', "Thêm Giảng viên {$gv->name} thành công.");
    }

    public function updateGiangVien(Request $request, Khoa $khoa, User $giangvien)
    {
        $data = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $giangvien->id,
            'active' => 'nullable|boolean',
        ]);

        $giangvien->update([
            'name' => $data['ho_ten'],
            'email' => $data['email'],
            'active' => $request->boolean('active'),
        ]);

        return back()->with('status', 'Cập nhật Giảng viên thành công.');
    }

    public function destroyGiangVien(Khoa $khoa, User $giangvien)
    {
        $giangvien->delete();
        return back()->with('status', 'Đã xoá Giảng viên.');
    }
}
