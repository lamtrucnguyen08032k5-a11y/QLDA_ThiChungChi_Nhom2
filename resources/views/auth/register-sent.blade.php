@extends('layouts.guest')
@section('title', 'Kiểm tra email')
@section('content')
<h5 class="mb-3">Kiểm tra email của bạn</h5>
<p>Hệ thống đã gửi liên kết xác minh tới <strong>{{ $email }}</strong>. Vui lòng mở email và bấm vào liên kết để hoàn tất đăng ký (liên kết có hiệu lực 24 giờ).</p>
<a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Quay lại đăng nhập</a>
@endsection
