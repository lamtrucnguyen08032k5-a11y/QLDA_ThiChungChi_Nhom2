@extends('layouts.app')
@section('title', 'Chi tiết phúc khảo')
@section('content')
<div class="card"><div class="card-body">
    <p><strong>Sinh viên:</strong> {{ $phuckhao->baiThi->dangKy->sinhVien->name }} ({{ $phuckhao->baiThi->dangKy->sinhVien->ma_so }})</p>
    <p><strong>Lý do phúc khảo:</strong> {{ $phuckhao->ly_do }}</p>
    <p><strong>Trạng thái:</strong> {{ $phuckhao->trang_thai }}</p>
    <p><strong>Điểm trước:</strong> {{ $phuckhao->diem_truoc ?? $phuckhao->baiThi->diem_tong }} &nbsp; <strong>Điểm sau:</strong> {{ $phuckhao->diem_sau ?? '—' }}</p>
    <p><strong>Phản hồi từ Giảng viên/Khoa:</strong> {{ $phuckhao->phan_hoi ?? 'Chưa có phản hồi.' }}</p>
    <a href="{{ route('admin.phuckhao.index') }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
</div></div>
@endsection
