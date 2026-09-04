@extends('layouts.guest')
@section('title', 'Liên kết không hợp lệ')
@section('content')
<h5 class="mb-3 text-danger">Liên kết không hợp lệ hoặc đã hết hạn</h5>
<p>Vui lòng thực hiện lại thao tác đăng ký để nhận liên kết xác minh mới.</p>
<a href="{{ route('register') }}" class="btn btn-primary w-100">Đăng ký lại</a>
@endsection
