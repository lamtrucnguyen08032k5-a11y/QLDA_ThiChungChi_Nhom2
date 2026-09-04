<?php

namespace App\Http\Controllers\Khoa;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

// Khoa có thể xem/quản lý danh sách Giảng viên trong khoa mình (hỗ trợ thêm cho Admin)
class GiangVienController extends Controller
{
    public function index()
    {
        $khoa = Auth::user()->khoa;
        $giangViens = $khoa->giangViens()->orderBy('name')->get();
        return view('khoa.giangvien', compact('khoa', 'giangViens'));
    }

    public function store(Request $request)
    {
        $khoa = Auth::user()->khoa;
        $data = $request->validate([
            'ma_giang_vien' => 'required|string|max:50|unique:users,ma_so',
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $matKhauTam = Str::random(10);
        User::create([
            'role' => 'giangvien',
            'ma_so' => $data['ma_giang_vien'],
            'name' => $data['ho_ten'],
            'email' => $data['email'],
            'password' => Hash::make($matKhauTam),
            'khoa_id' => $khoa->id,
            'email_verified_at' => now(),
        ]);

        Mail::raw("Tài khoản Giảng viên đã được tạo.\nEmail: {$data['email']}\nMật khẩu tạm thời: {$matKhauTam}", function ($m) use ($data) {
            $m->to($data['email'])->subject('Tài khoản Giảng viên - Hệ thống thi chứng chỉ HVNH');
        });

        return back()->with('status', 'Thêm Giảng viên thành công.');
    }
}
