@extends('layouts.guest')
@section('title', 'Đăng ký tài khoản Sinh viên')
@section('content')
<h5 class="mb-3">Đăng ký tài khoản Sinh viên</h5>
<p class="text-muted small">Vui lòng nhập email trường (@{{domain}}) đã được Học viện cấp. Hệ thống sẽ gửi liên kết xác minh tới email của bạn.</p>
<form method="POST" action="{{ route('register.submit') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Email trường</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="svxxxxxxxx@hvnh.edu.vn" required autofocus>
    </div>
    <button class="btn btn-primary w-100">Đăng ký / Tiếp tục</button>
</form>
<div class="text-center mt-3 small">
    <a href="{{ route('login') }}">Đã có tài khoản? Đăng nhập</a>
</div>
@endsection
