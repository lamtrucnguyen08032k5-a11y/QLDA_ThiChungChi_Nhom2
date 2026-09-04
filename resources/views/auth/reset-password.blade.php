@extends('layouts.guest')
@section('title', 'Đặt lại mật khẩu')
@section('content')
<h5 class="mb-3">Đặt lại mật khẩu</h5>
<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $email }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Mật khẩu mới</label>
        <input type="password" name="password" class="form-control" minlength="8" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Xác nhận mật khẩu mới</label>
        <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
    </div>
    <button class="btn btn-primary w-100">Đặt lại mật khẩu</button>
</form>
@endsection
