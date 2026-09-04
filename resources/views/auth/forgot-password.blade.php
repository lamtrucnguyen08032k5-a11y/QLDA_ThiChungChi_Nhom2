@extends('layouts.guest')
@section('title', 'Quên mật khẩu')
@section('content')
<h5 class="mb-3">Quên mật khẩu</h5>
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required autofocus>
    </div>
    <button class="btn btn-primary w-100">Gửi liên kết đặt lại mật khẩu</button>
</form>
<div class="text-center mt-3 small">
    <a href="{{ route('login') }}">Quay lại đăng nhập</a>
</div>
@endsection
