<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SvWhitelist;
use App\Models\User;
use App\Models\EmailVerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // UC1.1 bước 1-10: nhập email trường -> kiểm tra whitelist -> gửi link xác minh
    public function showForm()
    {
        return view('auth.register');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower($request->email);
        $domain = config('app.student_email_domain', '@hvnh.edu.vn');

        // Luồng phụ 1: email không đúng định dạng / không thuộc tên miền trường
        if (! str_ends_with($email, $domain)) {
            return back()->withErrors(['email' => "Email phải thuộc tên miền {$domain} của trường."])->withInput();
        }

        // Sinh viên phải có trong kho email (whitelist) do Admin import trước
        $sv = SvWhitelist::where('email', $email)->first();
        if (! $sv) {
            return back()->withErrors(['email' => 'Email này không có trong danh sách sinh viên của Học viện.'])->withInput();
        }

        // Luồng phụ 2: email đã được đăng ký
        if (User::where('email', $email)->exists()) {
            return back()->withErrors(['email' => 'Email đã được đăng ký tài khoản. Vui lòng đăng nhập hoặc dùng Quên mật khẩu.'])->withInput();
        }

        $token = Str::random(48);
        DB::table('email_verification_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'expires_at' => now()->addHours(24),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $verifyUrl = route('register.verify', ['token' => $token]);

        // Gửi email xác minh (dùng Notification/Mail thực tế khi cấu hình SMTP trong .env)
        Mail::raw("Xin chào {$sv->ho_ten},\n\nVui lòng bấm vào liên kết sau để xác minh và hoàn tất đăng ký tài khoản thi chứng chỉ HVNH:\n{$verifyUrl}\n\nLiên kết có hiệu lực trong 24 giờ.", function ($message) use ($email) {
            $message->to($email)->subject('Xác minh tài khoản - Hệ thống thi chứng chỉ HVNH');
        });

        return view('auth.register-sent', ['email' => $email]);
    }

    // UC1.1 bước 11-18: xác minh liên kết -> thiết lập mật khẩu -> tạo tài khoản
    public function showVerify(string $token)
    {
        $row = DB::table('email_verification_tokens')->where('token', $token)->first();

        if (! $row || now()->greaterThan($row->expires_at)) {
            return view('auth.verify-expired');
        }

        return view('auth.set-password', ['token' => $token, 'email' => $row->email]);
    }

    public function completeRegistration(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $row = DB::table('email_verification_tokens')->where('token', $request->token)->first();

        if (! $row || now()->greaterThan($row->expires_at)) {
            return view('auth.verify-expired');
        }

        $sv = SvWhitelist::where('email', $row->email)->first();
        if (! $sv) {
            abort(404);
        }

        $user = User::create([
            'role' => 'sinhvien',
            'ma_so' => $sv->ma_sv,
            'name' => $sv->ho_ten,
            'email' => $row->email,
            'password' => Hash::make($request->password),
            'lop' => $sv->lop,
            'khoa_hoc' => $sv->khoa_hoc,
            'email_verified_at' => now(),
        ]);

        $sv->update(['da_dang_ky' => true]);
        DB::table('email_verification_tokens')->where('token', $request->token)->delete();

        auth()->login($user);

        return redirect()->route('sinhvien.dashboard')->with('status', 'Đăng ký tài khoản thành công!');
    }
}
