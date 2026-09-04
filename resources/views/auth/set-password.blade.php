@extends('layouts.guest')
@section('title', 'Thiết lập mật khẩu')
@section('content')
<h5 class="mb-3">Xác minh thành công</h5>
<p class="text-muted small">Vui lòng thiết lập mật khẩu cho tài khoản <strong>{{ $email }}</strong> để hoàn tất đăng ký.</p>
<form method="POST" action="{{ route('register.complete') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control" minlength="8" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
    </div>
    <button class="btn btn-primary w-100">Xác nhận / Hoàn tất</button>
</form>
@endsection
