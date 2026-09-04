@extends('layouts.app')
@section('title', 'Đăng ký nhận chứng nhận')
@section('content')
<div class="card"><div class="card-body">
    <p><strong>Đề thi:</strong> {{ $baithi->deThi->ten_de }} • <strong>Điểm:</strong> {{ $baithi->diem_tong }}</p>
    <form method="POST" action="{{ route('sinhvien.chung-nhan.store', $baithi) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Địa chỉ nhận chứng nhận</label>
            <input name="dia_chi_nhan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại liên hệ</label>
            <input name="so_dien_thoai" class="form-control" required>
        </div>
        <button class="btn btn-primary">Đăng ký nhận</button>
    </form>
</div></div>
@endsection
