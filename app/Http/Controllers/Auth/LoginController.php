<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    // UC1.2 Đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
        }

        $user = Auth::user();

        if (! $user->active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Phòng khảo thí.']);
        }

        $request->session()->regenerate();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'khoa' => redirect()->route('khoa.dashboard'),
            'giangvien' => redirect()->route('giangvien.dashboard'),
            default => redirect()->route('sinhvien.dashboard'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
