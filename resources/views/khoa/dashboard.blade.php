@extends('layouts.app')
@section('title', 'Tổng quan - ' . $khoa->ten_khoa)
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-bg-primary"><div class="card-body">
            <div class="small">Số Giảng viên</div><div class="fs-3 fw-bold">{{ $giangViens->count() }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-warning"><div class="card-body">
            <div class="small">Bài cần chấm</div><div class="fs-3 fw-bold">{{ $baiCanCham }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success"><div class="card-body">
            <div class="small">Bài đã chấm xong</div><div class="fs-3 fw-bold">{{ $baiDaCham }}</div>
        </div></div>
    </div>
</div>
@endsection
