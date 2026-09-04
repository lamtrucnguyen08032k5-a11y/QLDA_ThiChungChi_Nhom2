@extends('layouts.guest')
@section('title', 'Đăng nhập')
@section('content')
<h5 class="mb-3">Đăng nhập</h5>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
    </div>
    <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
    </div>
    <button class="btn btn-primary w-100">Đăng nhập</button>
</form>
<div class="d-flex justify-content-between mt-3 small">
    <a href="{{ route('register') }}">Đăng ký tài khoản Sinh viên</a>
    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
</div>
@endsection
