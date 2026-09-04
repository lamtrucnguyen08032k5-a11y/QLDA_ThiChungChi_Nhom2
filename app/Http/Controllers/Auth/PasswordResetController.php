<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // UC1.3: Quên mật khẩu - luồng tương tự đăng ký (gửi liên kết xác minh qua email)
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', strtolower($request->email))->first();

        // Không tiết lộ email có tồn tại hay không, luôn báo đã gửi (nếu có) để tránh dò email
        if ($user) {
            $token = Str::random(48);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($token), 'created_at' => now()]
            );

            $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

            Mail::raw("Bạn vừa yêu cầu đặt lại mật khẩu.\n\nBấm vào liên kết sau để đặt lại mật khẩu (hiệu lực 60 phút):\n{$resetUrl}", function ($m) use ($user) {
                $m->to($user->email)->subject('Đặt lại mật khẩu - Hệ thống thi chứng chỉ HVNH');
            });
        }

        return back()->with('status', 'Nếu email tồn tại trong hệ thống, một liên kết đặt lại mật khẩu đã được gửi tới email đó.');
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $row = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (! $row || ! Hash::check($request->token, $row->token) || now()->diffInMinutes($row->created_at) > 60) {
            return back()->withErrors(['email' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
